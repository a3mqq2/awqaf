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
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Log;

class ExamineesImport implements 
    ToModel, 
    WithChunkReading, 
    WithCalculatedFormulas, 
    WithMultipleSheets, 
    WithBatchInserts
{
    public function model(array $row)
    {
        try {
            // تخطي الصفوف الفارغة أو رؤوس الأعمدة
            if (empty(array_filter($row, fn ($v) => $v !== null && $v !== ''))) {
                return null;
            }
            if ((isset($row[0]) && (string)$row[0] === 'Submission ID') || (isset($row[2]) && (string)$row[2] === 'Submitted at')) {
                return null;
            }

            $idx = [
                'submitted_at' => 2,
                'first_name'   => 3,
                'father_name'  => 4,
                'grand_name'   => 5,
                'last_name'    => 6,
                'full_name'    => 7,
                'nationality'  => 8,
                'national_id'  => 9,
                'passport_no'  => 10,
                'residence'    => 11,
                'gender'       => 12,
                'birth_date'   => 13,
                'office'       => 14,
                'phone'        => 15,
                'whatsapp'     => 16,
                'narration_alt'=> 17,
                'cluster'      => 18,
                'drawing_opt'  => 19,
                'narration'    => 22,
                'drawing'      => 23,
            ];

            $get = function (int $i) use ($row) {
                return array_key_exists($i, $row) ? $this->cleanCell($row[$i]) : null;
            };

            $firstName  = $get($idx['first_name']) ?: '-';
            $fatherName = $get($idx['father_name']) ?: '-';
            $grandName  = $get($idx['grand_name']) ?: '-';
            $lastName   = $get($idx['last_name']) ?: '-';
            $fullName   = $get($idx['full_name']) ?: trim("{$firstName} {$fatherName} {$grandName} {$lastName}");

            $genderRaw = $get($idx['gender']);
            $gender    = ($genderRaw === 'ذكر') ? 'male' : (($genderRaw === 'أنثى') ? 'female' : null);

            $officeName  = $get($idx['office']);
            $clusterName = $get($idx['cluster']);

            $narrationName = $get($idx['narration']) ?: $get($idx['narration_alt']);
            $drawingName   = $get($idx['drawing_opt']) ?: $get($idx['drawing']);

            $narrationId = !empty($narrationName)
                ? Narration::firstOrCreate(['name' => $narrationName])->id
                : null;

            $drawingId = !empty($drawingName)
                ? Drawing::firstOrCreate(['name' => $drawingName])->id
                : null;

            return Examinee::firstOrCreate(
                ['full_name' => $fullName],
                [
                    'first_name'        => $firstName,
                    'father_name'       => $fatherName,
                    'grandfather_name'  => $grandName,
                    'last_name'         => $lastName,
                    'national_id'       => $this->normalizeNationalId($get($idx['national_id']) ?? null),
                    'nationality'       => $get($idx['nationality']) ?: '-',
                    'passport_no'       => $get($idx['passport_no']) ?: '-',
                    'current_residence' => $get($idx['residence']) ?: '-',
                    'gender'            => $gender,
                    'birth_date'        => $this->transformDate($get($idx['birth_date'])),
                    'office_id'         => !empty($officeName) ? Office::firstOrCreate(['name' => $officeName])->id : null,
                    'cluster_id'        => !empty($clusterName) ? Cluster::firstOrCreate(['name' => $clusterName])->id : null,
                    'narration_id'      => $narrationId,
                    'drawing_id'        => $drawingId,
                    'status'            => 'pending',
                    'phone'             => $this->normalizePhone($get($idx['phone'])),
                    'whatsapp'          => $this->normalizePhone($get($idx['whatsapp'])),
                    'created_at'        => $this->transformDateTime($get($idx['submitted_at'])),
                ]
            );

        } catch (\Throwable $e) {
            Log::error("Import error in row: ".json_encode($row, JSON_UNESCAPED_UNICODE)." | ".$e->getMessage());
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function sheets(): array
    {
        return [
            'الورقة1' => $this,
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
            $timestamp = strtotime((string)$value);
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
            $timestamp = strtotime((string)$value);
            return $timestamp ? date('Y-m-d H:i:s', $timestamp) : now();
        } catch (\Exception $e) {
            return now();
        }
    }

    protected function normalizeNationalId($value)
    {
        if (empty($value)) {
            return null;
        }
        $clean = preg_replace('/[^0-9]/', '', (string) $value);
        return $clean ?: null;
    }

    protected function cleanCell($value)
    {
        if ($value === null) {
            return null;
        }
        $str = is_string($value) ? $value : (is_float($value) && floor($value) != $value ? (string)$value : (string)$value);
        if (is_string($str) && str_starts_with($str, '=')) {
            return null;
        }
        return trim((string)$str);
    }

    protected function normalizePhone($value)
    {
        if (empty($value)) {
            return null;
        }
        $phone = (string)$value;
        $phone = ltrim($phone, '-');
        $phone = preg_replace('/\s+/', '', $phone);
        return $phone ?: null;
    }
}
