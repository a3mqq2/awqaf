<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات الممتحن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body {
            background: #3c5e7f;
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .details-card {
            max-width: 800px;
            margin: 0 auto;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .card-header {
            background: #3c5e7f;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 2rem;
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .status-confirmed {
            background-color: #28a745;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-withdrawn {
            background-color: #dc3545;
        }
        
        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-section h5 {
            color: #667eea;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .info-value {
            color: #212529;
            text-align: left;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .btn-withdraw {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        
        .btn-withdraw:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .btn-back {
            background: #6c757d;
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
        }
        
        .attempts-table {
            margin-top: 1rem;
        }
        
        .attempts-table th {
            background-color: #e9ecef;
            font-weight: 600;
            padding: 0.75rem;
        }
        
        .attempts-table td {
            padding: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="details-card card">
            <div class="card-header">
                <i class="ti ti-user-check" style="font-size: 3rem;"></i>
                <h3>بيانات الممتحن</h3>
                <p>{{ $examinee->full_name ?? ($examinee->first_name . ' ' . ($examinee->father_name ?? '') . ' ' . ($examinee->grandfather_name ?? '') . ' ' . ($examinee->last_name ?? '')) }}</p>
                <span class="status-badge status-{{ $examinee->status }}">
                    @if($examinee->status == 'confirmed')
                        <i class="ti ti-circle-check me-1"></i> مؤكد
                    @elseif($examinee->status == 'pending')
                        <i class="ti ti-clock me-1"></i> قيد التأكيد
                    @else
                        <i class="ti ti-circle-x me-1"></i> منسحب
                    @endif
                </span>
            </div>
            
            <div class="card-body p-4">
                <!-- Personal Information -->
                <div class="info-section">
                    <h5><i class="ti ti-user me-2"></i>البيانات الشخصية</h5>
                    <div class="info-row">
                        <span class="info-label">الاسم الكامل:</span>
                        <span class="info-value">{{ $examinee->full_name ?? ($examinee->first_name . ' ' . $examinee->father_name . ' ' . $examinee->grandfather_name . ' ' . $examinee->last_name) }}</span>
                    </div>
                    @if($examinee->gender)
                    <div class="info-row">
                        <span class="info-label">الجنس:</span>
                        <span class="info-value">{{ $examinee->gender == 'male' ? 'ذكر' : 'أنثى' }}</span>
                    </div>
                    @endif
                    @if($examinee->birth_date)
                    <div class="info-row">
                        <span class="info-label">تاريخ الميلاد:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($examinee->birth_date)->format('Y-m-d') }}</span>
                    </div>
                    @endif
                    @if($examinee->nationality)
                    <div class="info-row">
                        <span class="info-label">الجنسية:</span>
                        <span class="info-value">{{ $examinee->nationality }}</span>
                    </div>
                    @endif
                </div>

                <!-- Contact Information -->
                <div class="info-section">
                    <h5><i class="ti ti-id me-2"></i>معلومات الهوية والاتصال</h5>
                    <div class="info-row">
                        <span class="info-label">الرقم الوطني:</span>
                        <span class="info-value">{{ $examinee->national_id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم الجواز:</span>
                        <span class="info-value">{{ $examinee->passport_no }}</span>
                    </div>
                
                    @if($examinee->current_residence)
                    <div class="info-row">
                        <span class="info-label">مكان الإقامة:</span>
                        <span class="info-value">{{ $examinee->current_residence }}</span>
                    </div>
                    @endif
                </div>

                <!-- Organization Information -->
                <div class="info-section">
                    <h5><i class="ti ti-building me-2"></i>البيانات التنظيمية</h5>
                    @if($examinee->office)
                    <div class="info-row">
                        <span class="info-label">المكتب:</span>
                        <span class="info-value">{{ $examinee->office->name }}</span>
                    </div>
                    @endif
                    @if($examinee->cluster)
                    <div class="info-row">
                        <span class="info-label">التجمع:</span>
                        <span class="info-value">{{ $examinee->cluster->name }}</span>
                    </div>
                    @endif
                </div>

                <!-- Exam Attempts -->
                @if($examinee->examAttempts && $examinee->examAttempts->count() > 0)
                <div class="info-section">
                    <h5><i class="ti ti-certificate me-2"></i>محاولات الامتحان</h5>
                    <div class="table-responsive attempts-table">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>السنة</th>
                                    <th>الرواية</th>
                                    <th>الرسم</th>
                                    <th>الجانب</th>
                                    <th>النتيجة</th>
                                    <th>النسبة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examinee->examAttempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->year ?? '-' }}</td>
                                    <td>{{ $attempt->narration->name ?? '-' }}</td>
                                    <td>{{ $attempt->drawing->name ?? '-' }}</td>
                                    <td>{{ $attempt->side ?? '-' }}</td>
                                    <td>{{ $attempt->result ?? '-' }}</td>
                                    <td>{{ $attempt->percentage ? $attempt->percentage . '%' : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($examinee->status != 'confirmed')
                    <form method="POST" action="{{ route('examinee.confirm', $examinee) }}" class="flex-fill" onsubmit="return confirm('هل أنت متأكد من تأكيد مشاركتك؟')">
                        @csrf
                        <input type="hidden" name="national_id" value="{{ $examinee->national_id }}">
                        <input type="hidden" name="passport_no" value="{{ $examinee->passport_no }}">
                        <input type="hidden" name="phone" value="{{ $examinee->phone }}">
                        <button type="submit" class="btn btn-confirm w-100">
                            <i class="ti ti-check me-2"></i>
                            تأكيد المشاركة
                        </button>
                    </form>
                    @endif

                    @if($examinee->status != 'withdrawn')
                    <form method="POST" action="{{ route('examinee.withdraw', $examinee) }}" class="flex-fill" onsubmit="return confirm('هل أنت متأكد من الانسحاب؟ لن تتمكن من المشاركة في الامتحان.')">
                        @csrf
                        <input type="hidden" name="national_id" value="{{ $examinee->national_id }}">
                        <input type="hidden" name="passport_no" value="{{ $examinee->passport_no }}">
                        <input type="hidden" name="phone" value="{{ $examinee->phone }}">
                        <button type="submit" class="btn btn-withdraw w-100">
                            <i class="ti ti-x me-2"></i>
                            الانسحاب
                        </button>
                    </form>
                    @endif
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('examinee.check.form') }}" class="btn btn-back">
                        <i class="ti ti-arrow-right me-2"></i>
                        رجوع للاستعلام
                    </a>
                </div>

                @if($examinee->notes)
                <div class="alert alert-info mt-3">
                    <strong><i class="ti ti-info-circle me-2"></i>ملاحظات:</strong><br>
                    {{ $examinee->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>