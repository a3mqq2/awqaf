<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التسجيل بنجاح</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
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
        
        .status-under_review,
        .status-pending {
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
        
        .card-alert {
            background: #e8f5e9;
            border: 3px solid #28a745;
            border-radius: 16px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
            animation: pulseGreen 2s ease-in-out infinite;
        }
        
        .card-alert-title {
            color: #28a745;
            font-weight: 800;
            font-size: 24px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .card-alert-message {
            color: #155724;
            font-size: 18px;
            line-height: 1.8;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .card-alert-submessage {
            color: #2d5016;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        @keyframes pulseGreen {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
            }
            50% {
                box-shadow: 0 4px 25px rgba(40, 167, 69, 0.4);
            }
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
        
        /* Float Button Styles */
        .float-button-container {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
        }
        
        .float-button {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #2d4960 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(60, 94, 127, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            animation: pulse 2s infinite;
        }
        
        .float-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(60, 94, 127, 0.4);
        }
        
        .float-button i {
            animation: wave 1s ease-in-out infinite;
        }
        
        .float-tooltip {
            position: absolute;
            bottom: 50%;
            left: 80px;
            transform: translateY(50%);
            background: white;
            color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            animation: tooltipFadeIn 0.5s ease-in-out 1s forwards, tooltipFloat 2s ease-in-out 1s infinite;
            pointer-events: none;
        }
        
        .float-tooltip::before {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 8px 8px 8px 0;
            border-color: transparent white transparent transparent;
        }
        
        .float-tooltip-arrow {
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 24px;
            animation: arrowBounce 1s ease-in-out 1s infinite;
            opacity: 0;
            animation: arrowFadeIn 0.5s ease-in-out 1s forwards, arrowBounce 1s ease-in-out 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(60, 94, 127, 0.3);
            }
            50% {
                box-shadow: 0 4px 25px rgba(60, 94, 127, 0.5);
            }
        }
        
        @keyframes wave {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-15deg);
            }
            75% {
                transform: rotate(15deg);
            }
        }
        
        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(50%) translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(50%) translateX(0);
            }
        }
        
        @keyframes tooltipFloat {
            0%, 100% {
                transform: translateY(50%) translateX(0);
            }
            50% {
                transform: translateY(50%) translateX(5px);
            }
        }
        
        @keyframes arrowFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes arrowBounce {
            0%, 100% {
                transform: translateY(-50%) translateX(0);
            }
            50% {
                transform: translateY(-50%) translateX(-8px);
            }
        }
        
        .float-button-container:hover .float-tooltip {
            opacity: 1 !important;
            animation: tooltipFloat 2s ease-in-out infinite;
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            border-radius: 0;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 1;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-title {
            font-weight: 700;
        }
        
        .contact-form .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .contact-form .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
        }
        
        .contact-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(60, 94, 127, 0.15);
        }
        
        .btn-submit-contact {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 14px 40px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-submit-contact:hover {
            background-color: #2d4960;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(60, 94, 127, 0.3);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
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
            
            .float-button-container {
                bottom: 20px;
                left: 20px;
            }
            
            .float-button {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .float-tooltip {
                left: 75px;
                font-size: 13px;
                padding: 10px 15px;
            }
            
            .float-tooltip-arrow {
                font-size: 20px;
            }
            
            .card-alert-title {
                font-size: 20px;
            }
            
            .card-alert-message {
                font-size: 16px;
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

            <!-- Card Print Alert for Confirmed Status -->
            @if($examinee->status == 'confirmed')
            <div class="card-alert">
                <div class="card-alert-title">
                    <span>تنبيه هام جداً</span>
                </div>
                <div class="card-alert-message">
                    يجب عليك سحب بطاقة الدخول وطباعتها
                </div>
                <div class="card-alert-submessage">
                    البطاقة إلزامية لحضور الامتحان ولن يسمح بالدخول بدونها
                </div>
                <a href="{{ route('public.registration.print-card', ['ids' => $examinee->id]) }}" 
                   target="_blank" 
                   class="btn-print-card"
                   style="display: inline-block; margin-top: 10px;">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline-block; vertical-align: middle; margin-left: 8px;">
                        <path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/>
                    </svg>
                    طباعة البطاقة الآن
                </a>
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
                            <span class="status-badge status-under_review">⏳ قيد المراجعة</span>
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
                    <li><strong>يجب طباعة بطاقة الدخول - البطاقة إلزامية لحضور الامتحان</strong></li>
                    @endif
                    <li>يمكنك العودة لتأكيد أو تعديل تسجيلك من خلال صفحة الاستعلام</li>
                    <li>تأكد من صحة رقم الهاتف المسجل للتواصل</li>
                    <li>سيتم الإعلان عن موعد ومكان الامتحان قريباً</li>
                </ul>
            </div>

            <div class="buttons-container">
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

    <!-- Float Button -->
    <div class="float-button-container">
        <div class="float-tooltip">
            <span class="float-tooltip-arrow">👈</span>
            اضغط هنا للتواصل مع الإدارة
        </div>
        <button class="float-button" data-bs-toggle="modal" data-bs-target="#contactModal" title="اضغط هنا للتواصل مع الإدارة">
            <i class="ti ti-message-circle"></i>
        </button>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">
                        <i class="ti ti-message-dots me-2"></i>
                        تواصل مع الإدارة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="contact_name" class="form-label">
                                <i class="ti ti-user me-1"></i>
                                الاسم الكامل
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="contact_name" 
                                   name="name" 
                                   placeholder="أدخل اسمك الكامل"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">
                                <i class="ti ti-phone me-1"></i>
                                رقم الهاتف
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="contact_phone" 
                                   name="phone" 
                                   placeholder="أدخل رقم الهاتف"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_city" class="form-label">
                                <i class="ti ti-map-pin me-1"></i>
                                المدينة
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="contact_city" 
                                   name="city" 
                                   placeholder="أدخل اسم المدينة"
                                   required>
                        </div>


                        <div class="mb-3">
                            <label for="national_id" class="form-label">
                             الرقم الوطني او جواز سفر
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="national_id" 
                                   name="national_id" 
                                   placeholder="أدخل الرقم الوطني"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_message" class="form-label">
                                <i class="ti ti-message me-1"></i>
                                الرسالة
                            </label>
                            <textarea class="form-control" 
                                      id="contact_message" 
                                      name="message" 
                                      rows="4" 
                                      placeholder="اكتب رسالتك هنا..."
                                      required></textarea>
                        </div>

                        <input type="hidden" name="email_to" value="support@waqsa.ly">

                        <div class="d-grid">
                            <button type="submit" class="btn btn-submit-contact">
                                <i class="ti ti-send me-2"></i>
                                إرسال الرسالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Auto-scroll to top on page load
        window.addEventListener('load', function() {
            window.scrollTo(0, 0);
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '<span style="font-family: Cairo; color: #dc3545;">تنبيه</span>',
                    html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">{{ session('error') }}</p>',
                    confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                    confirmButtonColor: '#dc3545',
                    customClass: {
                        popup: 'cairo-font',
                        confirmButton: 'cairo-font'
                    }
                });
            @endif
            
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '<span style="font-family: Cairo; color: #28a745;">تمت بنجاح</span>',
                    html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">{{ session('success') }}</p>',
                    confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                    confirmButtonColor: '#28a745',
                    customClass: {
                        popup: 'cairo-font',
                        confirmButton: 'cairo-font'
                    }
                });
            @endif
        });
    </script>

</body>
</html>