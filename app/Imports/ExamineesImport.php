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
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExamineesImport implements ToModel, WithChunkReading, WithCalculatedFormulas, WithHeadingRow, WithMultipleSheets
{
    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            return null;
        }

        $narrationId = !empty($this->cleanCell($row['الرواية'] ?? null))
            ? Narration::firstOrCreate(['name' => $this->cleanCell($row['الرواية'])])->id
            : null;

        $drawingId = !empty($this->cleanCell($row['الرسم'] ?? null))
            ? Drawing::firstOrCreate(['name' => $this->cleanCell($row['الرسم'])])->id
            : null;

        $firstName  = $this->cleanCell($row['الاسم الأول'] ?? '-');
        $fatherName = $this->cleanCell($row['اسم الأب'] ?? '-');
        $grandName  = $this->cleanCell($row['اسم الجد'] ?? '-');
        $lastName   = $this->cleanCell($row['اللقب'] ?? '-');

        return Examinee::firstOrCreate(
            ['national_id' => $this->normalizeNationalId($row['الرقم الوطني'] ?? '-')],
            [
                'first_name'        => $firstName,
                'father_name'       => $fatherName,
                'grandfather_name'  => $grandName,
                'last_name'         => $lastName,
                'full_name'         => trim("$firstName $fatherName $grandName $lastName"),
                'nationality'       => $this->cleanCell($row['الجنسية'] ?? '-'),
                'passport_no'       => $this->cleanCell($row['رقم جواز السفر'] ?? '-'),
                'current_residence' => $this->cleanCell($row['مكان الإقامة الحالي'] ?? '-'),
                'gender'            => ($this->cleanCell($row['الجنس'] ?? '') === 'ذكر') ? 'male' : 'female',
                'birth_date'        => $this->transformDate($row['تاريخ الميلاد'] ?? null),
                'office_id'         => !empty($this->cleanCell($row['اسم مكتب الأوقاف التابع له'] ?? null))
                    ? Office::firstOrCreate(['name' => $this->cleanCell($row['اسم مكتب الأوقاف التابع له'])])->id
                    : null,
                'cluster_id'        => null, // مش موجود في هذا الشيت
                'narration_id'      => $narrationId,
                'drawing_id'        => $drawingId,
                'status'            => 'pending',
                'phone'             => $this->normalizePhone($row['رقم الهاتف للتواصل'] ?? null),
                'whatsapp'          => $this->normalizePhone($row['رقم الوتس أب '] ?? null),
                'created_at'        => $this->transformDateTime($row['Submitted at'] ?? null),
            ]
        );
    }

    public function headingRow(): int
    {
        return 1; // الصف الأول يحتوي أسماء الأعمدة
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function sheets(): array
    {
        return [
            'الورقة1' => $this, // نستهدف الشيت بالاسم
        ];
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

    protected function normalizePhone($value)
    {
        if (empty($value)) {
            return null;
        }
        $phone = (string) $this->cleanCell($value);
        $phone = ltrim($phone, '-');
        $phone = preg_replace('/\s+/', '', $phone);

        return $phone ?: null;
    }
}
