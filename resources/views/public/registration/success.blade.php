<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التسجيل بنجاح</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3c5e7f;
            --secondary-color: #998965;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .success-container {
            max-width: 700px;
            width: 100%;
        }
        
        .success-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 60px;
            color: white;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }
        
        .success-title {
            color: var(--primary-color);
            font-weight: 800;
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        .success-message {
            color: #6c757d;
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: right;
            border: 2px solid #e9ecef;
        }
        
        .info-header {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
            text-align: center;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .info-value {
            color: #495057;
            font-size: 16px;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        
        
        .status-under_review {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-withdrawn {
            background: #f8d7da;
            color: #721c24;
        }
        
        .buttons-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn-home {
            background: var(--primary-color);
            color: white;
            padding: 16px 50px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 12px;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background: #2d4a5f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(60, 94, 127, 0.3);
            color: white;
        }
        
        .btn-print-card {
            background: var(--secondary-color);
            color: white;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 12px;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-print-card:hover {
            background: #7a6d4f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(153, 137, 101, 0.3);
            color: white;
        }
        
        .alert-success {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .important-note {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            text-align: right;
        }
        
        .important-note h4 {
            color: #856404;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .important-note ul {
            margin: 0;
            padding-right: 20px;
            color: #856404;
            line-height: 2;
        }
        
        .footer-note {
            color: #6c757d;
            font-size: 14px;
            margin-top: 30px;
            line-height: 1.8;
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 30px 20px;
            }
            
            .success-title {
                font-size: 24px;
            }
            
            .success-message {
                font-size: 16px;
            }
            
            .info-box {
                padding: 20px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .buttons-container {
                flex-direction: column;
            }
            
            .btn-home,
            .btn-print-card {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">✓</div>
            
            <h1 class="success-title">تم التسجيل بنجاح!</h1>
            
            <p class="success-message">
                تم تسجيل بياناتك بنجاح في نظام امتحان الإجازة للعام 1447 هـ - 2025 م.<br>
                سيتم مراجعة طلبك من قبل الجهات المختصة وإشعارك بالنتيجة.
            </p>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="info-box">
                <h3 class="info-header">بيانات التسجيل</h3>
                
                <div class="info-row">
                    <span class="info-label">رقم التسجيل:</span>
                    <span class="info-value">#{{ str_pad($examinee->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">الاسم الكامل:</span>
                    <span class="info-value">{{ $examinee->full_name }}</span>
                </div>
                
                @if($examinee->national_id)
                <div class="info-row">
                    <span class="info-label">الرقم الوطني:</span>
                    <span class="info-value">{{ $examinee->national_id }}</span>
                </div>
                @endif
                
                @if($examinee->passport_no)
                <div class="info-row">
                    <span class="info-label">رقم الجواز:</span>
                    <span class="info-value">{{ $examinee->passport_no }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">رقم الهاتف:</span>
                    <span class="info-value">{{ $examinee->phone }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">المكتب:</span>
                    <span class="info-value">{{ $examinee->office->name ?? '-' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">التجمع:</span>
                    <span class="info-value">{{ $examinee->cluster->name ?? '-' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">الرواية:</span>
                    <span class="info-value">{{ $examinee->narration->name ?? '-' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">الرسم:</span>
                    <span class="info-value">{{ $examinee->drawing->name ?? '-' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">الحالة:</span>
                    <span>
                        @if($examinee->status == 'confirmed')
                            <span class="status-badge status-confirmed">✓ مؤكد</span>
                            @elseif($examinee->status == 'pending')
                            <span class="status-badge status-pending">⏳ قيد المراجعة</span>

                            @elseif($examinee->status == 'under_review')
                            <span class="status-badge status-review">⏳ قيد المراجعة</span>


                        @else
                            <span class="status-badge status-withdrawn">✗ منسحب</span>
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">تاريخ التسجيل:</span>
                    <span class="info-value">{{ $examinee->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            <div class="important-note">
                <h4>ملاحظات هامة:</h4>
                <ul>
                    <li>تأكد من صحة رقمك الوطني أو الإداري أو الهوية للاستعلام عن القبول في الامتحان</li>
                    @if ($examinee->status == "confirmed")
                    <li>يمكنك طباعة بطاقتك الشخصية من خلال الزر أدناه</li>
                    @endif
                    <li>يمكنك العودة لتأكيد أو تعديل تسجيلك من خلال صفحة الاستعلام</li>
                    <li>تأكد من صحة رقم الهاتف المسجل للتواصل</li>
                    <li>سيتم الإعلان عن موعد ومكان الامتحان قريباً</li>
                </ul>
            </div>

            <div class="buttons-container">
                @if ($examinee->status == "confirmed")
                <a href="{{ route('examinees.print.cards') }}?ids={{ $examinee->id }}" 
                    target="_blank" 
                    class="btn-print-card">
                     <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline-block; vertical-align: middle; margin-left: 8px;">
                         <path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/>
                     </svg>
                     طباعة البطاقة
                 </a>
                @endif
                <a href="{{ route('public.registration.index') }}" class="btn-home">
                    العودة للصفحة الرئيسية
                </a>
            </div>

            <p class="footer-note">
                في حال وجود أي استفسار، يرجى التواصل مع المكتب المسجل لديه<br>
                © {{ date('Y') }} وزارة الأوقاف والشؤون الإسلامية - ليبيا
            </p>
        </div>
    </div>

    <script>
        // Auto-scroll to top on page load
        window.addEventListener('load', function() {
            window.scrollTo(0, 0);
        });
    </script>
</body>
</html>