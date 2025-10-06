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
        // لو الصف كله فاضي نتجاهله
        if (empty(array_filter($row))) {
            return null;
        }

        // الاسم الكامل
        $fullName = trim(($row[1] ?? '') . ' ' . ($row[2] ?? '') . ' ' . ($row[3] ?? '') . ' ' . ($row[4] ?? ''));

        if (!$fullName) {
            return null;
        }

        // الرواية (العمود 15)
        $narrationId = !empty($row[15] ?? null)
            ? Narration::firstOrCreate(['name' => trim($row[15])])->id
            : null;

        // الرسم (العمود 16)
        $drawingId = !empty($row[16] ?? null)
            ? Drawing::firstOrCreate(['name' => trim($row[16])])->id
            : null;

        // المكتب (العمود 11)
        $officeId = !empty($row[11] ?? null)
            ? Office::firstOrCreate(['name' => trim($row[11])])->id
            : null;

        // مكان الامتحان (العمود 14)
        $clusterId = !empty($row[14] ?? null)
            ? Cluster::firstOrCreate(['name' => trim($row[14])])->id
            : null;

        // تاريخ الميلاد (العمود 10)
        $birthDate = null;
        if (!empty($row[10] ?? null)) {
            if (is_numeric($row[10])) {
                try {
                    $birthDate = ExcelDate::excelToDateTimeObject($row[10]);
                } catch (\Exception $e) {
                    $birthDate = null;
                }
            } else {
                try {
                    $birthDate = Carbon::parse($row[10]);
                } catch (\Exception $e) {
                    $birthDate = null;
                }
            }
        }

        return new Examinee([
            'submitted_at'     => !empty($row[0]) ? Carbon::parse($row[0]) : now(),
            'first_name'       => trim($row[1] ?? ''),
            'father_name'      => trim($row[2] ?? ''),
            'grandfather_name' => trim($row[3] ?? ''),
            'last_name'        => trim($row[4] ?? ''),
            'full_name'        => $fullName,
            'nationality'      => trim($row[5] ?? 'ليبي'),
            'national_id'      => !empty($row[6]) ? strval(intval($row[6])) : null,
            'passport_no'      => !empty($row[7]) ? trim($row[7]) : null,
            'current_residence'=> trim($row[8] ?? ''),
            'gender'           => (trim($row[9] ?? '') === 'أنثى') ? 'female' : 'male',
            'birth_date'       => $birthDate,
            'office_id'        => $officeId,
            'cluster_id'       => $clusterId,
            'narration_id'     => $narrationId,
            'drawing_id'       => $drawingId,
            'status'           => 'pending',
            'phone'            => !empty($row[12]) ? strval($row[12]) : '',
            'whatsapp'         => !empty($row[13]) ? strval($row[13]) : '',
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function startRow(): int
    {
        return 2; // نتجاهل الصف الأول (العناوين)
    }
}
