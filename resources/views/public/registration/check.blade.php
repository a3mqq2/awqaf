<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الاستعلام عن التسجيل</title>
    
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
            padding: 20px;
        }
        
        .check-container {
            max-width: 600px;
            margin: 40px auto;
        }
        
        .header-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .step-container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }
        
        .identity-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .identity-card {
            border: 3px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .identity-card:hover {
            border-color: var(--primary-color);
            background: #f8f9fa;
        }
        
        .identity-card.active {
            border-color: var(--primary-color);
            background: rgba(60, 94, 127, 0.05);
        }
        
        .identity-card input[type="radio"] {
            display: none;
        }
        
        .identity-icon {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .identity-label {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(60, 94, 127, 0.15);
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 14px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            background: #2d4a5f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(60, 94, 127, 0.3);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            padding: 14px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 10px;
        }
        
        .btn-secondary:hover {
            background: #7a6d4f;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 20px;
            font-size: 16px;
        }
        
        #identityNumberField {
            display: none;
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
            .identity-cards {
                grid-template-columns: 1fr;
            }
            
            .step-container {
                padding: 25px;
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
        }
    </style>
</head>
<body>
    <div class="check-container">
        <!-- Header -->
        <div class="header-card">
            <h1 class="page-title">الاستعلام عن التسجيل</h1>
            <p class="text-muted mb-0">قم بإدخال بياناتك للتحقق من حالة تسجيلك</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                @if(session('show_register'))
                    <a href="{{ route('public.registration.register.form') }}" class="alert-link d-block mt-2">
                        انتقل إلى صفحة التسجيل الجديد
                    </a>
                @endif
            </div>
        @endif

        <div class="step-container">
            <form action="{{ route('public.registration.check') }}" method="POST" id="checkForm">
                @csrf

                <!-- Identity Type Selection -->
                <div class="mb-4">
                    <div class="identity-cards">
                        <label class="identity-card" id="libyanCard">
                            <input type="radio" name="identity_type" value="national_id" required>
                            <div class="identity-icon">🇱🇾</div>
                            <div class="identity-label">ليبي الجنسية</div>
                        </label>
                        
                        <label class="identity-card" id="foreignCard">
                            <input type="radio" name="identity_type" value="passport" required>
                            <div class="identity-icon">🌍</div>
                            <div class="identity-label">جنسية أخرى</div>
                        </label>
                    </div>
                </div>

                <!-- Identity Number Field -->
                <div id="identityNumberField">
                    <div class="mb-4">
                        <label class="form-label" id="identityLabel">
                            <span id="identityLabelText"></span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="identity_number" 
                               class="form-control @error('identity_number') is-invalid @enderror" 
                               id="identityInput"
                               placeholder="">
                        @error('identity_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('public.registration.index') }}" class="btn btn-secondary">
                            رجوع
                        </a>
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            التحقق من التسجيل
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Float Button -->
    <div class="float-button-container">
        <div class="float-tooltip">
            <span class="float-tooltip-arrow">👈</span>
            هل تواجه مشكلة ؟ اضغط هنا للتواصل مع الادارة
        </div>
        <button class="float-button" data-bs-toggle="modal" data-bs-target="#contactModal" title="هل تواجه مشكلة ؟ اضغط هنا للتواصل مع الادارة">
            <i class="ti ti-message-circle"></i>
        </button>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
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

                        <input type="hidden" name="email_to" value="aishaaltery89@gmail.com">

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const libyanCard = document.getElementById('libyanCard');
            const foreignCard = document.getElementById('foreignCard');
            const identityNumberField = document.getElementById('identityNumberField');
            const identityLabelText = document.getElementById('identityLabelText');
            const identityInput = document.getElementById('identityInput');
            
            libyanCard.addEventListener('click', function() {
                foreignCard.classList.remove('active');
                libyanCard.classList.add('active');
                identityLabelText.textContent = 'الرقم الوطني / او الاداري';
                identityInput.placeholder = ' أدخل الرقم الوطني / او الاداري';
                identityNumberField.style.display = 'block';
            });
            
            foreignCard.addEventListener('click', function() {
                libyanCard.classList.remove('active');
                foreignCard.classList.add('active');
                identityLabelText.textContent = 'رقم تحقق الهوية';
                identityInput.placeholder = 'أدخل رقم تحقق الهوية';
                identityNumberField.style.display = 'block';
            });
        });
    </script>

<script>
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
                title: '<span style="font-family: Cairo; color: #28a745;">نجح التسجيل</span>',
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