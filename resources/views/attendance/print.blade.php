<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير حضور اللجنة - {{ $committee->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            direction: rtl;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3c5e7f;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #3c5e7f;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            margin: 5px 0;
        }
        
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-item strong {
            color: #3c5e7f;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-card h2 {
            color: #3c5e7f;
            font-size: 2rem;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        table thead {
            background: #3c5e7f;
            color: white;
        }
        
        table th,
        table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .attended {
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .not-attended {
            background: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            border-top: 2px solid #e9ecef;
            padding-top: 20px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>وزارة الأوقاف والشؤون الإسلامية</h1>
        <h2>تقرير حضور الممتحنين</h2>
        <p>لجنة: {{ $committee->name }}</p>
    </div>

    <!-- معلومات اللجنة -->
    <div class="info-section">
        <div>
            <div class="info-item">
                <strong>اسم اللجنة:</strong> {{ $committee->name }}
            </div>
            <div class="info-item">
                <strong>التجمع:</strong> {{ $committee->cluster->name }}
            </div>
        </div>
        <div>
            <div class="info-item">
                <strong>التاريخ:</strong> {{ now()->format('Y-m-d') }}
            </div>
            <div class="info-item">
                <strong>الوقت:</strong> {{ now()->format('H:i:s') }}
            </div>
        </div>
    </div>

    <!-- إحصائيات الحضور -->
    <div class="stats">
        <div class="stat-card">
            <h2>{{ $totalExaminees }}</h2>
            <p>إجمالي الممتحنين</p>
        </div>
        <div class="stat-card">
            <h2 style="color: #28a745;">{{ $attendedCount }}</h2>
            <p>حضر</p>
        </div>
        <div class="stat-card">
            <h2 style="color: #dc3545;">{{ $notAttendedCount }}</h2>
            <p>لم يحضر</p>
        </div>
        <div class="stat-card">
            <h2 style="color: #17a2b8;">{{ $attendancePercentage }}%</h2>
            <p>نسبة الحضور</p>
        </div>
    </div>

    <!-- جدول الممتحنين -->
    <table>
        <thead>
            <tr>
                <th width="50">#</th>
                <th>الاسم الكامل</th>
                <th>الرقم الوطني</th>
                <th>رقم الهاتف</th>
                <th>الرواية</th>
                <th>الحالة</th>
                <th>وقت الحضور</th>
            </tr>
        </thead>
        <tbody>
            @forelse($examinees as $examinee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $examinee->full_name }}</td>
                    <td>{{ $examinee->national_id ?? $examinee->passport_no }}</td>
                    <td>{{ $examinee->phone ?? 'غير متوفر' }}</td>
                    <td>{{ $examinee->narration->name ?? 'غير محدد' }}</td>
                    <td>
                        @if($examinee->is_attended)
                            <span class="attended">✓ حضر</span>
                        @else
                            <span class="not-attended">✗ لم يحضر</span>
                        @endif
                    </td>
                    <td>
                        @if($examinee->attended_at)
                            {{ $examinee->attended_at->format('Y-m-d H:i') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                        لا يوجد ممتحنين مؤكدين في هذه اللجنة
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة: {{ Auth::user()->name }}</p>
        <p>© {{ now()->format('Y') }} وزارة الأوقاف والشؤون الإسلامية - جميع الحقوق محفوظة</p>
    </div>

    <!-- زر الطباعة -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #3c5e7f; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            🖨️ طباعة
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">
            ✖️ إغلاق
        </button>
    </div>

    <script>
        // طباعة تلقائية عند فتح الصفحة (اختياري)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>