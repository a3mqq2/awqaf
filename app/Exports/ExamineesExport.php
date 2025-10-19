<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamineesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $examinees;
    protected $columns;
    
    public function __construct($examinees, $columns)
    {
        $this->examinees = $examinees;
        $this->columns = $columns;
    }
    
    public function collection()
    {
        return $this->examinees->map(function($examinee, $index) {
            $row = [];
            
            foreach ($this->columns as $column) {
                switch ($column) {
                    case 'id':
                        $row[] = $index + 1;
                        break;
                    case 'full_name':
                        $row[] = $examinee->full_name;
                        break;
                    case 'first_name':
                        $row[] = $examinee->first_name;
                        break;
                    case 'father_name':
                        $row[] = $examinee->father_name;
                        break;
                    case 'grandfather_name':
                        $row[] = $examinee->grandfather_name;
                        break;
                    case 'last_name':
                        $row[] = $examinee->last_name;
                        break;
                    case 'national_id':
                        $row[] = $examinee->national_id ?? '-';
                        break;
                    case 'passport_no':
                        $row[] = $examinee->passport_no ?? '-';
                        break;
                    case 'phone':
                        $row[] = $examinee->phone ?? '-';
                        break;
                    case 'whatsapp':
                        $row[] = $examinee->whatsapp ?? '-';
                        break;
                    case 'email':
                        $row[] = $examinee->email ?? '-';
                        break;
                    case 'gender':
                        $row[] = $examinee->gender == 'male' ? 'ذكر' : 'أنثى';
                        break;
                    case 'birth_date':
                        $row[] = $examinee->birth_date ? $examinee->birth_date->format('Y-m-d') : '-';
                        break;
                    case 'nationality':
                        $row[] = $examinee->nationality ?? '-';
                        break;
                    case 'current_residence':
                        $row[] = $examinee->current_residence ?? '-';
                        break;
                    case 'office':
                        $row[] = $examinee->office->name ?? '-';
                        break;
                    case 'cluster':
                        $row[] = $examinee->cluster->name ?? '-';
                        break;
                    case 'narration':
                        $row[] = $examinee->narration->name ?? '-';
                        break;
                    case 'drawing':
                        $row[] = $examinee->drawing->name ?? '-';
                        break;
                    case 'status':
                        $statusMap = [
                            'confirmed' => 'مؤكد',
                            'attended' => 'حضر',
                            'under_review' => 'قيد المراجعة',
                            'pending' => 'قيد التأكيد',
                            'rejected' => 'مرفوض',
                            'withdrawn' => 'منسحب',
                        ];
                        $row[] = $statusMap[$examinee->status] ?? $examinee->status;
                        break;
                    case 'notes':
                        $row[] = $examinee->notes ?? '-';
                        break;
                }
            }
            
            return $row;
        });
    }
    
    public function headings(): array
    {
        $headings = [];
        
        $columnLabels = [
            'id' => '#',
            'full_name' => 'الاسم الكامل',
            'first_name' => 'الاسم الأول',
            'father_name' => 'اسم الأب',
            'grandfather_name' => 'اسم الجد',
            'last_name' => 'اللقب',
            'national_id' => 'الرقم الوطني',
            'passport_no' => 'رقم الجواز',
            'phone' => 'الهاتف',
            'whatsapp' => 'واتساب',
            'email' => 'البريد الإلكتروني',
            'gender' => 'الجنس',
            'birth_date' => 'تاريخ الميلاد',
            'nationality' => 'الجنسية',
            'current_residence' => 'مكان الإقامة',
            'office' => 'المكتب',
            'cluster' => 'التجمع',
            'narration' => 'الرواية',
            'drawing' => 'الرسم',
            'status' => 'الحالة',
            'notes' => 'الملاحظات',
        ];
        
        foreach ($this->columns as $column) {
            $headings[] = $columnLabels[$column] ?? $column;
        }
        
        return $headings;
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
        ];
    }
}