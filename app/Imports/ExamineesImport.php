<?php

namespace App\Imports;

use App\Models\Examinee;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\Narration;
use App\Models\Drawing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExamineesImport implements ToModel, WithChunkReading, WithCalculatedFormulas, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            return null;
        }
        if (isset($row[0]) && is_numeric($row[0])) {
            unset($row[0]);
        }

        $narrationId = !empty($this->cleanCell($row[18] ?? null))
            ? Narration::firstOrCreate(['name' => $this->cleanCell($row[18])])->id
            : null;

        $drawingId = !empty($this->cleanCell($row[20] ?? null))
            ? Drawing::firstOrCreate(['name' => $this->cleanCell($row[20])])->id
            : null;

        $firstName  = $this->cleanCell($row[4] ?? '-');
        $fatherName = $this->cleanCell($row[5] ?? '-');
        $grandName  = $this->cleanCell($row[6] ?? '-');
        $lastName   = $this->cleanCell($row[7] ?? '-');

        return Examinee::firstOrCreate(
            ['national_id' => $this->normalizeNationalId($row[10] ?? '-')],
            [
                'first_name'        => $firstName,
                'father_name'       => $fatherName,
                'grandfather_name'  => $grandName,
                'last_name'         => $lastName,
                'full_name'         => trim("$firstName $fatherName $grandName $lastName"),
                'nationality'       => $this->cleanCell($row[9] ?? '-'),
                'passport_no'       => $this->cleanCell($row[11] ?? '-'),
                'current_residence' => $this->cleanCell($row[12] ?? '-'),
                'gender'            => ($this->cleanCell($row[13] ?? '') === 'ذكر') ? 'male' : 'female',
                'birth_date'        => $this->transformDate($row[14] ?? null),
                'office_id'         => !empty($this->cleanCell($row[15]))
                    ? Office::firstOrCreate(['name' => $this->cleanCell($row[15])])->id
                    : null,
                'cluster_id'        => !empty($this->cleanCell($row[19]))
                    ? Cluster::firstOrCreate(['name' => $this->cleanCell($row[19])])->id
                    : null,
                'narration_id'      => $narrationId,
                'drawing_id'        => $drawingId,
                'status'            => 'pending',
                'phone'             => $this->normalizePhone($row[16] ?? null),
                'whatsapp'          => $this->normalizePhone($row[17] ?? null),
                'created_at'        => $this->transformDateTime($row[3] ?? null),
            ]
        );
    }

    /**
     * تحديد رقم الصف الذي يبدأ منه القراءة (تخطي الصف الأول)
     */
    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    protected function transformDate($value)
    {
        try {
            if (empty($value)) {
                return null;
            }
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            }
            $timestamp = strtotime($value);
            return $timestamp ? date('Y-m-d', $timestamp) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function transformDateTime($value)
    {
        try {
            if (empty($value)) {
                return now();
            }
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
            }
            $timestamp = strtotime($value);
            return $timestamp ? date('Y-m-d H:i:s', $timestamp) : now();
        } catch (\Exception $e) {
            return now();
        }
    }

    protected function normalizeNationalId($value)
    {
        if (empty($value)) {
            return '-';
        }
        $clean = preg_replace('/[^0-9]/', '', (string) $value);
        return $clean ?: '-';
    }

    protected function cleanCell($value)
    {
        if (is_string($value) && str_starts_with($value, '=')) {
            return null;
        }
        return trim((string) $value);
    }

    /**
     * تنظيف الهاتف/الواتساب (منع السالب وحفظه كنص)
     */
    protected function normalizePhone($value)
    {
        if (empty($value)) {
            return null;
        }

        // حول القيمة لنص
        $phone = (string) $this->cleanCell($value);

        // امسح أي رمز سالب أو مسافات
        $phone = ltrim($phone, '-');
        $phone = preg_replace('/\s+/', '', $phone);

        return $phone ?: null;
    }
}