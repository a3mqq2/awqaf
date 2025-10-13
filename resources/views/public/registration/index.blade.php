<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من تسجيل امتحان الإجازة 2025/2026م</title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Custom Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3c5e7f;
            --secondary-color: #998965;
            --light-bg: #f8f9fa;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .registration-container {
            max-width: 900px;
            width: 100%;
        }
        
        .logo-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(60, 94, 127, 0.1);
            margin-bottom: 30px;
        }
        
        .logo-placeholder {
            width: 120px;
            height: 120px;
            background: white;
            border: 3px solid var(--primary-color);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .main-title {
            color: var(--primary-color);
            font-weight: 800;
            font-size: 32px;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .sub-title {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 0;
        }
        
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(60, 94, 127, 0.15);
            border-color: var(--primary-color);
        }
        
        .action-card-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
        }
        
        .action-card-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .action-card-desc {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 0;
            line-height: 1.8;
        }
        
        /* Disabled Registration Card Styles */
        .registration-disabled-wrapper {
            position: relative;
            pointer-events: none;
        }
        
        .registration-disabled-wrapper .action-card {
            filter: blur(3px);
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .registration-disabled-wrapper:hover .action-card {
            transform: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }
        
        .closed-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 25px 40px;
            border-radius: 16px;
            text-align: center;
            z-index: 10;
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.35);
            pointer-events: auto;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .closed-overlay-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 1.3;
            letter-spacing: 0.5px;
        }
        
        .closed-overlay-text {
            font-size: 17px;
            font-weight: 600;
            margin: 0;
            opacity: 0.95;
        }
        

        
        .footer-text {
            text-align: center;
            color: #6c757d;
            margin-top: 30px;
            font-size: 14px;
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
            .main-title {
                font-size: 24px;
            }
            
            .sub-title {
                font-size: 16px;
            }
            
            .action-card {
                padding: 25px;
                margin-bottom: 20px;
            }
            
            .action-card-title {
                font-size: 20px;
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
            
            .closed-overlay {
                padding: 20px 30px;
            }
            
            .closed-overlay-title {
                font-size: 20px;
            }
            
            .closed-overlay-text {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <!-- Logo & Title Section -->
        <div class="logo-section">
            <div class="logo-placeholder">شعار الوزارة</div>
            <h1 class="main-title">
                التحقق من تسجيل امتحان الإجازة
            </h1>
            <p class="sub-title">
                لسنة
                2025م - 1447هـ 
            </p>
        </div>

        <!-- Action Cards -->
        <div class="row g-4">
            <!-- Check Registration Card -->
            <div class="col-md-6">
                <a href="#" class="text-decoration-none">
                    <div class="action-card">
                        <div class="action-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                        </div>
                        <h3 class="action-card-title">الاستعلام عن تسجيل سابق</h3>
                        <p class="action-card-desc">
                            في حال قمت بالتسجيل مسبقاً، يمكنك التحقق من حالة تسجيلك وتأكيده أو الانسحاب
                        </p>
                    </div>
                </a>
            </div>

            <!-- New Registration Card - DISABLED -->
            <div class="col-md-6">
                <div class="registration-disabled-wrapper">
                    <div class="closed-overlay">
                        <div class="closed-overlay-title">انتهى التسجيل</div>
                        <div class="closed-overlay-text">التسجيل غير متاح حالياً</div>
                    </div>
                    <div class="action-card">
                        <div class="action-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                        <h3 class="action-card-title">تسجيل جديد</h3>
                        <p class="action-card-desc">
                            في حال لم تسجل مسبقاً، قم بإنشاء تسجيل جديد لامتحان الإجازة
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="footer-text">
            © 2025 وزارة الأوقاف والشؤون الإسلامية - جميع الحقوق محفوظة
        </p>
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
                    <form class="contact-form">
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
</body>
</html>