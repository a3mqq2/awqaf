<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة قائمة الممتحنين</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            padding: 20px;
            font-size: 11px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .header .info {
            font-size: 13px;
            color: #666;
            margin-top: 10px;
        }
        
        .summary {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .summary-item {
            display: inline-block;
            margin-left: 20px;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .summary-item strong {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        table thead {
            background: #333;
            color: white;
        }
        
        table th,
        table td {
            padding: 8px 5px;
            border: 1px solid #ddd;
            text-align: right;
        }
        
        table th {
            font-weight: bold;
            font-size: 11px;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .status-confirmed {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-attended {
            color: #17a2b8;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        
        .status-withdrawn {
            color: #6c757d;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>قائمة الممتحنين</h1>
        <div class="info">
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <h3>ملخص الإحصائيات:</h3>
        <div class="summary-item">
            <strong>إجمالي:</strong> {{ $examinees->count() }}
        </div>
        <div class="summary-item">
            <strong>مؤكد:</strong> <span class="status-confirmed">{{ $examinees->where('status', 'confirmed')->count() }}</span>
        </div>
        <div class="summary-item">
            <strong>حضر:</strong> <span class="status-attended">{{ $examinees->where('status', 'attended')->count() }}</span>
        </div>
        <div class="summary-item">
            <strong>قيد التأكيد:</strong> <span class="status-pending">{{ $examinees->where('status', 'pending')->count() }}</span>
        </div>
        <div class="summary-item">
            <strong>مرفوض:</strong> <span class="status-rejected">{{ $examinees->where('status', 'rejected')->count() }}</span>
        </div>
        <div class="summary-item">
            <strong>منسحب:</strong> <span class="status-withdrawn">{{ $examinees->where('status', 'withdrawn')->count() }}</span>
        </div>
    </div>

    <!-- Examinees Table -->
    <table>
        <thead>
            <tr>
                @php
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
                
                @foreach($columns as $column)
                    <th>{{ $columnLabels[$column] ?? $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($examinees as $index => $examinee)
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
                                {{ $examinee->father_name ?? '-' }}
                            @elseif($column == 'grandfather_name')
                                {{ $examinee->grandfather_name ?? '-' }}
                            @elseif($column == 'last_name')
                                {{ $examinee->last_name ?? '-' }}
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
                                @if($examinee->status == 'confirmed')
                                    <span class="status-confirmed">مؤكد</span>
                                @elseif($examinee->status == 'attended')
                                    <span class="status-attended">حضر</span>
                                @elseif($examinee->status == 'pending')
                                    <span class="status-pending">قيد التأكيد</span>
                                @elseif($examinee->status == 'rejected')
                                    <span class="status-rejected">مرفوض</span>
                                @elseif($examinee->status == 'withdrawn')
                                    <span class="status-withdrawn">منسحب</span>
                                @else
                                    {{ $examinee->status }}
                                @endif
                            @elseif($column == 'notes')
                                {{ $examinee->notes ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" style="text-align: center;">لا توجد بيانات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>تم الطباعة من نظام إدارة الممتحنين | {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>إجمالي السجلات: {{ $examinees->count() }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>