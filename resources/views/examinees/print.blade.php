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
            font-size: 12px;
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
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        
        .filters {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .filters h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .filter-item {
            display: inline-block;
            margin-left: 20px;
            margin-bottom: 5px;
        }
        
        .filter-item strong {
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #333;
            color: white;
        }
        
        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: right;
        }
        
        table th {
            font-weight: bold;
            font-size: 13px;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .status-confirmed {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-withdrawn {
            color: #dc3545;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 2px solid #ddd;
            padding-top: 15px;
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
            font-size: 13px;
        }
        
        .summary-item strong {
            color: #333;
        }
        
        @media print {
            body {
                padding: 0;
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
            <strong>إجمالي الممتحنين:</strong> {{ $examinees->count() }}
        </div>
        <div class="summary-item">
            <strong>مؤكد:</strong> <span class="status-confirmed">{{ $examinees->where('status', 'confirmed')->count() }}</span>
        </div>
        <div class="summary-item">
            <strong>قيد التأكيد:</strong> <span class="status-pending">{{ $examinees->where('status', 'pending')->count() }}</span>
        </div>
        <div class="summary-item">
            <strong>منسحب:</strong> <span class="status-withdrawn">{{ $examinees->where('status', 'withdrawn')->count() }}</span>
        </div>
    </div>

    <!-- Active Filters -->
    @if(request()->hasAny(['name', 'national_id', 'passport_no', 'phone', 'gender', 'status', 'nationality', 'office_id', 'cluster_id', 'current_residence']))
    <div class="filters">
        <h3>الفلاتر المطبقة:</h3>
        @if(request('name'))
            <div class="filter-item"><strong>الاسم:</strong> {{ request('name') }}</div>
        @endif
        @if(request('national_id'))
            <div class="filter-item"><strong>الرقم الوطني/ او الاداري:</strong> {{ request('national_id') }}</div>
        @endif
        @if(request('passport_no'))
            <div class="filter-item"><strong>رقم الجواز:</strong> {{ request('passport_no') }}</div>
        @endif
        @if(request('phone'))
            <div class="filter-item"><strong>الهاتف:</strong> {{ request('phone') }}</div>
        @endif
        @if(request('gender'))
            <div class="filter-item"><strong>الجنس:</strong> {{ request('gender') == 'male' ? 'ذكر' : 'أنثى' }}</div>
        @endif
        @if(request('status'))
            <div class="filter-item">
                <strong>الحالة:</strong>
                @if(request('status') == 'confirmed') مؤكد
                @elseif(request('status') == 'pending') قيد التأكيد
                @else منسحب
                @endif
            </div>
        @endif
        @if(request('nationality'))
            <div class="filter-item"><strong>الجنسية:</strong> {{ request('nationality') }}</div>
        @endif
    </div>
    @endif

    <!-- Examinees Table -->
    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th>الاسم الكامل</th>
                <th>الرقم الوطني/ او الاداري</th>
                <th>رقم الجواز</th>
                <th>الهاتف</th>
                <th>الجنسية</th>
                <th>المكتب</th>
                <th>التجمع</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($examinees as $index => $examinee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $examinee->full_name ?? $examinee->first_name }}</td>
                    <td>{{ $examinee->national_id ?? '-' }}</td>
                    <td>{{ $examinee->passport_no ?? '-' }}</td>
                    <td>{{ $examinee->phone ?? '-' }}</td>
                    <td>{{ $examinee->nationality ?? '-' }}</td>
                    <td>{{ $examinee->office->name ?? '-' }}</td>
                    <td>{{ $examinee->cluster->name ?? '-' }}</td>
                    <td>
                        @if($examinee->status == 'confirmed')
                            <span class="status-confirmed">مؤكد</span>
                        @elseif($examinee->status == 'pending')
                            <span class="status-pending">قيد التأكيد</span>
                        @else
                            <span class="status-withdrawn">منسحب</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">لا توجد بيانات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>تم الطباعة من نظام إدارة الممتحنين | {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>