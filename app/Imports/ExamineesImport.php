<?php

namespace App\Imports;

use App\Models\Examinee;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\Narration;
use App\Models\Drawing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExamineesImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        $narrationId = !empty($row['alroay_almshark_bha'])
            ? Narration::firstOrCreate(['name' => $row['alroay_almshark_bha']])->id
            : null;

        $drawingId = !empty($row['akhtyar_alrsm'])
            ? Drawing::firstOrCreate(['name' => $row['akhtyar_alrsm']])->id
            : null;

        return Examinee::firstOrCreate(
            ['national_id' => $row['alrkm_alotny'] ?? '-'],
            [
                'first_name'        => $row['alasm_alaol'] ?? '-',
                'father_name'       => $row['asm_alab'] ?? '-',
                'grandfather_name'  => $row['asm_algd'] ?? '-',
                'last_name'         => $row['allkb'] ?? '-',
                'full_name'         => $row['alasm_alrbaaay'] ?? '-',
                'nationality'       => $row['algnsy'] ?? '-',
                'passport_no'       => $row['rkm_goaz_alsfr'] ?? '-',
                'current_residence' => $row['mkan_alakam_alhaly'] ?? '-',
                'gender'            => ($row['algns'] ?? '') === 'ذكر' ? 'male' : 'female',
                'birth_date'        => $this->transformDate($row['tarykh_almylad'] ?? null),
                'office_id'         => !empty($row['asm_mktb_alaokaf_altabaa_lh'])
                    ? Office::firstOrCreate(['name' => $row['asm_mktb_alaokaf_altabaa_lh']])->id
                    : null,
                'cluster_id'        => !empty($row['mkan_alamthan'])
                    ? Cluster::firstOrCreate(['name' => $row['mkan_alamthan']])->id
                    : null,
                'narration_id'      => $narrationId,
                'drawing_id'        => $drawingId,
                'status'            => 'pending',
            ]
        );
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

            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return null;
        }
    }
}
