<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>كشف الممتحنين</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; direction: rtl; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        thead { background: #333; color: white; }
        th, td { padding: 8px 5px; border: 1px solid #ddd; text-align: right; font-size: 9px; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>كشف الممتحنين</h1>
        <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                @foreach($columns as $column)
                    @php
                        $columnLabels = [
                            'id' => '#',
                            'full_name' => 'الاسم',
                            'first_name' => 'الأول',
                            'father_name' => 'الأب',
                            'grandfather_name' => 'الجد',
                            'last_name' => 'اللقب',
                            'national_id' => 'الرقم الوطني',
                            'passport_no' => 'الجواز',
                            'phone' => 'الهاتف',
                            'whatsapp' => 'واتساب',
                            'email' => 'البريد',
                            'gender' => 'الجنس',
                            'birth_date' => 'الميلاد',
                            'nationality' => 'الجنسية',
                            'current_residence' => 'الإقامة',
                            'office' => 'المكتب',
                            'cluster' => 'التجمع',
                            'narration' => 'الرواية',
                            'drawing' => 'الرسم',
                            'status' => 'الحالة',
                            'notes' => 'ملاحظات',
                        ];
                    @endphp
                    <th>{{ $columnLabels[$column] ?? $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($examinees as $index => $examinee)
                <tr>
                    @foreach($columns as $column)
                        <td>
                            @if($column == 'id')
                                {{ $index + 1 }}
                            @elseif($column == 'full_name')
                                {{ $examinee->full_name }}
                            @elseif($column == 'first_name')
                                {{ $examinee->first_name }}
                            @elseif($column == 'father_name')
                                {{ $examinee->father_name }}
                            @elseif($column == 'grandfather_name')
                                {{ $examinee->grandfather_name }}
                            @elseif($column == 'last_name')
                                {{ $examinee->last_name }}
                            @elseif($column == 'national_id')
                                {{ $examinee->national_id ?? '-' }}
                            @elseif($column == 'passport_no')
                                {{ $examinee->passport_no ?? '-' }}
                            @elseif($column == 'phone')
                                {{ $examinee->phone ?? '-' }}
                            @elseif($column == 'whatsapp')
                                {{ $examinee->whatsapp ?? '-' }}
                            @elseif($column == 'email')
                                {{ $examinee->email ?? '-' }}
                            @elseif($column == 'gender')
                                {{ $examinee->gender == 'male' ? 'ذكر' : 'أنثى' }}
                            @elseif($column == 'birth_date')
                                {{ $examinee->birth_date ? $examinee->birth_date->format('Y-m-d') : '-' }}
                            @elseif($column == 'nationality')
                                {{ $examinee->nationality ?? '-' }}
                            @elseif($column == 'current_residence')
                                {{ $examinee->current_residence ?? '-' }}
                            @elseif($column == 'office')
                                {{ $examinee->office->name ?? '-' }}
                            @elseif($column == 'cluster')
                                {{ $examinee->cluster->name ?? '-' }}
                            @elseif($column == 'narration')
                                {{ $examinee->narration->name ?? '-' }}
                            @elseif($column == 'drawing')
                                {{ $examinee->drawing->name ?? '-' }}
                            @elseif($column == 'status')
                                @php
                                    $statusMap = [
                                        'confirmed' => 'مؤكد',
                                        'attended' => 'حضر',
                                        'under_review' => 'قيد المراجعة',
                                        'pending' => 'قيد التأكيد',
                                        'rejected' => 'مرفوض',
                                        'withdrawn' => 'منسحب',
                                    ];
                                @endphp
                                {{ $statusMap[$examinee->status] ?? $examinee->status }}
                            @elseif($column == 'notes')
                                {{ $examinee->notes ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>تم الطباعة من نظام إدارة الممتحنين | {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>إجمالي السجلات: {{ $examinees->count() }}</p>
    </div>
</body>
</html>