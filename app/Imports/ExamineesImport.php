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
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExamineesImport implements 
    ToModel, 
    WithChunkReading, 
    WithCalculatedFormulas, 
    WithHeadingRow, 
    WithMultipleSheets
{
    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            return null;
        }

        // الاسم الكامل للتفرد
        $fullName = trim(
            ($row['الاسم الأول'] ?? '') . ' ' .
            ($row['اسم الأب'] ?? '') . ' ' .
            ($row['اسم الجد'] ?? '') . ' ' .
            ($row['اللقب'] ?? '')
        );

        if (!$fullName) {
            return null;
        }

        // منع التكرار
        if (Examinee::where('full_name', $fullName)->exists()) {
            return null;
        }

        $narrationId = !empty($row['الرواية المشارك بها'] ?? null)
            ? Narration::firstOrCreate(['name' => trim($row['الرواية المشارك بها'])])->id
            : null;

        $drawingId = !empty($row['الرسم'] ?? null)
            ? Drawing::firstOrCreate(['name' => trim($row['الرسم'])])->id
            : null;

        $officeId = !empty($row['اسم مكتب الأوقاف التابع له'] ?? null)
            ? Office::firstOrCreate(['name' => trim($row['اسم مكتب الأوقاف التابع له'])])->id
            : null;

        $clusterId = !empty($row['مكان الامتحان'] ?? null)
            ? Cluster::firstOrCreate(['name' => trim($row['مكان الامتحان'])])->id
            : null;

        $birthDate = null;
        if (!empty($row['تاريخ الميلاد'] ?? null)) {
            try {
                $birthDate = ExcelDate::excelToDateTimeObject($row['تاريخ الميلاد']);
            } catch (\Exception $e) {
                $birthDate = Carbon::parse($row['تاريخ الميلاد']);
            }
        }

        return new Examinee([
            'submitted_at'     => !empty($row['Submitted at']) ? Carbon::parse($row['Submitted at']) : now(),
            'first_name'       => trim($row['الاسم الأول'] ?? ''),
            'father_name'      => trim($row['اسم الأب'] ?? ''),
            'grandfather_name' => trim($row['اسم الجد'] ?? ''),
            'last_name'        => trim($row['اللقب'] ?? ''),
            'full_name'        => $fullName,
            'nationality'      => trim($row['الجنسية'] ?? 'ليبي'),
            'national_id'      => !empty($row['الرقم الوطني']) ? trim($row['الرقم الوطني']) : null,
            'passport_no'      => !empty($row['رقم جواز السفر']) ? trim($row['رقم جواز السفر']) : null,
            'current_residence'=> trim($row['مكان الإقامة الحالي'] ?? ''),
            'gender'           => (trim($row['الجنس'] ?? '') === 'أنثى') ? 'female' : 'male',
            'birth_date'       => $birthDate,
            'office_id'        => $officeId,
            'cluster_id'       => $clusterId,
            'narration_id'     => $narrationId,
            'drawing_id'       => $drawingId,
            'status'           => 'pending',
            'phone'            => trim($row['رقم الهاتف للتواصل'] ?? ''),
            'whatsapp'         => trim($row['رقم الوتس أب'] ?? ''),
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function sheets(): array
    {
        return [
            'الورقة1' => $this,
        ];
    }
}
