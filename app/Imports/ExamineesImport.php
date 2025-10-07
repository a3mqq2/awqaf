<?php

namespace App\Imports;

use App\Models\Examinee;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\Narration;
use App\Models\Drawing;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExamineesImport implements
    ToModel,
    WithChunkReading,
    WithCalculatedFormulas,
    WithStartRow
{
    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            return null;
        }

        $firstName       = trim((string)($row[1] ?? ''));
        $fatherName      = trim((string)($row[2] ?? ''));
        $grandfatherName = trim((string)($row[3] ?? ''));
        $lastName        = trim((string)($row[4] ?? ''));

        $fullName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
            $firstName, $fatherName, $grandfatherName, $lastName,
        ], fn($v) => $v != ''))));

        if ($fullName == '') {
            return null;
        }

        $submittedAt = $this->parseDate($row[0] ?? null);
        $birthDate   = $this->parseDate($row[10] ?? null);

        $officeId = !empty($row[11] ?? null)
            ? Office::firstOrCreate(['name' => trim((string)$row[11])])->id
            : null;

        $narrationId = !empty($row[14] ?? null)
            ? Narration::firstOrCreate(['name' => trim((string)$row[14])])->id
            : null;

        $clusterId = !empty($row[15] ?? null)
            ? Cluster::firstOrCreate(['name' => trim((string)$row[15])])->id
            : null;

        $drawingId = !empty($row[16] ?? null)
            ? Drawing::firstOrCreate(['name' => trim((string)$row[16])])->id
            : null;

        $genderRaw = trim((string)($row[9] ?? ''));
        $gender    = $genderRaw == 'أنثى' ? 'female' : 'male';

        $nationalId = $this->digits($row[6] ?? null);
        $phone      = $this->digits($row[12] ?? null);
        $whatsapp   = $this->digits($row[13] ?? null);

        return new Examinee([
            'submitted_at'      => $submittedAt ?? now(),
            'first_name'        => $firstName,
            'father_name'       => $fatherName,
            'grandfather_name'  => $grandfatherName,
            'last_name'         => $lastName,
            'full_name'         => $fullName,
            'nationality'       => trim((string)($row[5] ?? 'ليبي')),
            'national_id'       => $nationalId != '' ? $nationalId : null,
            'passport_no'       => !empty($row[7]) ? trim((string)$row[7]) : null,
            'current_residence' => trim((string)($row[8] ?? '')),
            'gender'            => $gender,
            'birth_date'        => $birthDate,
            'office_id'         => $officeId,
            'cluster_id'        => $clusterId,
            'narration_id'      => $narrationId,
            'drawing_id'        => $drawingId,
            'status'            => 'pending',
            'phone'             => $phone != '' ? $phone : null,
            'whatsapp'          => $whatsapp != '' ? $whatsapp : null,
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function startRow(): int
    {
        return 2;
    }

    private function parseDate($value): ?Carbon
    {
        if (empty($value) && $value != 0) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                $dt = ExcelDate::excelToDateTimeObject((float)$value);
                return Carbon::instance($dt);
            }
            return Carbon::parse((string)$value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function digits($value): string
    {
        if ($value == null) {
            return '';
        }

        $s = (string)$value;

        $map = [
            '٠' => '0','١' => '1','٢' => '2','٣' => '3','٤' => '4',
            '٥' => '5','٦' => '6','٧' => '7','٨' => '8','٩' => '9',
        ];
        $s = strtr($s, $map);

        $s = preg_replace('/\D+/', '', $s) ?? '';

        return ltrim($s, '0') == '' && $s != '' ? '0' : $s;
    }
}
