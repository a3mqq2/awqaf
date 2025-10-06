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
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExamineesImport implements ToModel, WithChunkReading, WithCalculatedFormulas
{
    public function model(array $row)
    {
        // تجاهل العنوان أو الصفوف الفارغة
        if (!isset($row[1]) || empty(array_filter($row))) {
            return null;
        }

        // الاسم الكامل
        $fullName = trim(($row[1] ?? '') . ' ' . ($row[2] ?? '') . ' ' . ($row[3] ?? '') . ' ' . ($row[4] ?? ''));

        if (!$fullName) {
            return null;
        }

        // الرواية (مثلاً العمود 15 حسب التمبلت)
        $narrationId = !empty($row[15] ?? null)
            ? Narration::firstOrCreate(['name' => trim($row[15])])->id
            : null;

        // الرسم (مثلاً العمود 16)
        $drawingId = !empty($row[16] ?? null)
            ? Drawing::firstOrCreate(['name' => trim($row[16])])->id
            : null;

        // المكتب (مثلاً العمود 11)
        $officeId = !empty($row[11] ?? null)
            ? Office::firstOrCreate(['name' => trim($row[11])])->id
            : null;

        // مكان الامتحان (مثلاً العمود 14)
        $clusterId = !empty($row[14] ?? null)
            ? Cluster::firstOrCreate(['name' => trim($row[14])])->id
            : null;

            $birthDate = null;
            if (!empty($row[10])) {
                if (is_numeric($row[10])) {
                    // Excel serial number
                    try {
                        $birthDate = ExcelDate::excelToDateTimeObject($row[10]);
                    } catch (\Exception $e) {
                        $birthDate = null;
                    }
                } else {
                    // نص (String date)
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
}
