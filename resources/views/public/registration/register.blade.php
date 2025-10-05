<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل جديد</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
        
        .register-container {
            max-width: 900px;
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
        
        .steps-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .steps-container::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e9ecef;
            z-index: 0;
        }
        
        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .step-item.active .step-circle {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .step-item.completed .step-circle {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        
        .step-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 600;
        }
        
        .step-item.active .step-label {
            color: var(--primary-color);
        }
        
        .form-step {
            display: none;
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }
        
        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .step-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary-color);
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
            padding: 30px;
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
            margin-bottom: 15px;
        }
        
        .identity-label {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(60, 94, 127, 0.15);
        }
        
        .btn-navigation {
            padding: 14px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: #2d4a5f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(60, 94, 127, 0.3);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background: #7a6d4f;
        }
        
        .btn-outline-secondary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            background: transparent;
        }
        
        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            color: white;
        }
        
        .review-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .review-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .review-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .review-item:last-child {
            border-bottom: none;
        }
        
        .review-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .review-value {
            font-weight: 600;
            color: #212529;
        }
        
        .cairo-font {
            font-family: 'Cairo', sans-serif !important;
        }
        
        .swal2-popup {
            border-radius: 20px !important;
        }
        
        .swal2-title {
            font-size: 24px !important;
            padding: 20px !important;
        }
        
        .swal2-html-container {
            margin: 20px 0 !important;
        }
        
        .swal2-confirm {
            font-size: 16px !important;
            padding: 12px 40px !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }
        
        @media (max-width: 768px) {
            .identity-cards {
                grid-template-columns: 1fr;
            }
            
            .form-step {
                padding: 25px;
            }
            
            .steps-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Header -->
        <div class="header-card">
            <h1 class="page-title">تسجيل جديد</h1>
            <p class="text-muted mb-0">املأ البيانات المطلوبة بدقة</p>
        </div>

        <!-- Steps Progress -->
        <div class="steps-container">
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">نوع الهوية</div>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">البيانات الشخصية</div>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">بيانات الاتصال</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">بيانات الامتحان</div>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">المراجعة</div>
            </div>
        </div>

        <form action="{{ route('public.registration.register') }}" method="POST" id="registrationForm">
            @csrf

            <!-- Step 1: Identity Type -->
            <div class="form-step active" data-step="1">
                <h2 class="step-title">اختر نوع الهوية</h2>
                
                <div class="identity-cards">
                    <label class="identity-card" id="libyanCard">
                        <input type="radio" name="identity_type" value="national_id" required>
                        <div class="identity-icon">🇱🇾</div>
                        <div class="identity-label">ليبي الجنسية</div>
                        <small class="text-muted">الرقم الوطني / او الاداري</small>
                    </label>
                    
                    <label class="identity-card" id="foreignCard">
                        <input type="radio" name="identity_type" value="passport" required>
                        <div class="identity-icon">🌍</div>
                        <div class="identity-label">جنسية أخرى</div>
                        <small class="text-muted">رقم الهوية</small>
                    </label>
                </div>

                <div class="d-flex gap-3">
                    <a href="{{ route('public.registration.index') }}" class="btn btn-outline-secondary btn-navigation">
                        رجوع
                    </a>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(1, 2)" id="step1Next" disabled>
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 2: Personal Information -->
            <div class="form-step" data-step="2">
                <h2 class="step-title">البيانات الشخصية</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            الاسم الأول
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            اسم الأب
                        </label>
                        <input type="text" name="father_name" id="father_name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            اسم الجد
                        </label>
                        <input type="text" name="grandfather_name" id="grandfather_name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            اللقب
                        </label>
                        <input type="text" name="last_name" id="last_name" class="form-control">
                    </div>

                    <div class="col-md-6" id="nationalIdField" style="display: none;">
                        <label class="form-label">
                            الرقم الوطني / او الاداري
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="national_id" id="national_id" class="form-control" placeholder="12 رقم" maxlength="12">
                    </div>

                    <div class="col-md-6" id="passportField" style="display: none;">
                        <label class="form-label">
                            رقم الهوية
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="passport_no" id="passport_no" class="form-control">
                    </div>

                    <input type="hidden" name="nationality" id="nationality" value="ليبية">

                    <div class="col-md-6">
                        <label class="form-label">
                            تاريخ الميلاد
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control" max="2008-12-31" required>
                        <small class="text-muted">الحد الأقصى: 2008</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الجنس
                            <span class="text-danger">*</span>
                        </label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="">اختر...</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(1)">
                        السابق
                    </button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(2, 3)">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 3: Contact Information -->
            <div class="form-step" data-step="3">
                <h2 class="step-title">بيانات الاتصال والإقامة</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            رقم الهاتف (بدون صفر)
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">+218</span>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="912345678" maxlength="9" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            رقم الواتساب (اختياري)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">+218</span>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="912345678" maxlength="9">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">
                            مكان الإقامة الحالي
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="current_residence" id="current_residence" class="form-control" placeholder="المدينة والمنطقة" required>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(2)">
                        السابق
                    </button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(3, 4)">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 4: Exam Information -->
            <div class="form-step" data-step="4">
                <h2 class="step-title">بيانات الامتحان</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            المكتب
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="office_name" id="office_name" class="form-control" placeholder="اسم المكتب" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            التجمع
                            <span class="text-danger">*</span>
                        </label>
                        <select name="cluster_id" id="cluster_id" class="form-select" required>
                            <option value="">اختر التجمع...</option>
                            @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الرواية
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="narration_name" id="narration_name" class="form-control" placeholder="اسم الرواية" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الرسم
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="drawing_name" id="drawing_name" class="form-control" placeholder="اسم الرسم" required>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(3)">
                        السابق
                    </button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(4, 5)">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 5: Review -->
            <div class="form-step" data-step="5">
                <h2 class="step-title">مراجعة البيانات قبل التأكيد</h2>

                <div class="alert alert-warning">
                    <strong>⚠️ تنبيه:</strong> يرجى التأكد من صحة البيانات المدخلة قبل إتمام التسجيل
                </div>

                <div class="review-section">
                    <h3 class="review-title">البيانات الشخصية</h3>
                    <div class="review-item">
                        <span class="review-label">نوع الهوية:</span>
                        <span class="review-value" id="review_identity_type"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">الاسم الكامل:</span>
                        <span class="review-value" id="review_full_name"></span>
                    </div>
                    <div class="review-item" id="review_national_id_row" style="display: none;">
                        <span class="review-label">الرقم الوطني / او الاداري:</span>
                        <span class="review-value" id="review_national_id"></span>
                    </div>
                    <div class="review-item" id="review_passport_row" style="display: none;">
                        <span class="review-label">رقم الهوية:</span>
                        <span class="review-value" id="review_passport"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">تاريخ الميلاد:</span>
                        <span class="review-value" id="review_birth_date"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">الجنس:</span>
                        <span class="review-value" id="review_gender"></span>
                    </div>
                </div>

                <div class="review-section">
                    <h3 class="review-title">بيانات الاتصال</h3>
                    <div class="review-item">
                        <span class="review-label">رقم الهاتف:</span>
                        <span class="review-value" id="review_phone"></span>
                    </div>
                    <div class="review-item" id="review_whatsapp_row">
                        <span class="review-label">رقم الواتساب:</span>
                        <span class="review-value" id="review_whatsapp"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">مكان الإقامة:</span>
                        <span class="review-value" id="review_residence"></span>
                    </div>
                </div>

                <div class="review-section">
                    <h3 class="review-title">بيانات الامتحان</h3>
                    <div class="review-item">
                        <span class="review-label">المكتب:</span>
                        <span class="review-value" id="review_office"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">التجمع:</span>
                        <span class="review-value" id="review_cluster"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">الرواية:</span>
                        <span class="review-value" id="review_narration"></span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">الرسم:</span>
                        <span class="review-value" id="review_drawing"></span>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(4)">
                        تعديل البيانات
                    </button>
                    <button type="submit" class="btn btn-primary btn-navigation flex-grow-1">
                        ✓ تأكيد وإتمام التسجيل
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        let selectedIdentityType = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Handle session errors and success messages
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '<span style="font-family: Cairo; color: #dc3545;">خطأ</span>',
                    html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">{{ session('error') }}</p>',
                    confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                    confirmButtonColor: '#dc3545',
                    backdrop: 'rgba(220, 53, 69, 0.4)',
                    customClass: {
                        popup: 'cairo-font',
                        confirmButton: 'cairo-font'
                    }
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '<span style="font-family: Cairo; color: #28a745;">نجح</span>',
                    html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">{{ session('success') }}</p>',
                    confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                    confirmButtonColor: '#28a745',
                    backdrop: 'rgba(40, 167, 69, 0.4)',
                    customClass: {
                        popup: 'cairo-font',
                        confirmButton: 'cairo-font'
                    }
                });
            @endif

            @if($errors->any())
                let errorsList = '<ul style="text-align: right; padding-right: 20px; margin: 0;">';
                @foreach($errors->all() as $error)
                    errorsList += '<li style="margin-bottom: 8px;">{{ $error }}</li>';
                @endforeach
                errorsList += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: '<span style="font-family: Cairo; color: #dc3545;">يوجد أخطاء في النموذج</span>',
                    html: '<div style="font-family: Cairo; font-size: 16px; direction: rtl;">' + errorsList + '</div>',
                    confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                    confirmButtonColor: '#dc3545',
                    backdrop: 'rgba(220, 53, 69, 0.4)',
                    customClass: {
                        popup: 'cairo-font',
                        confirmButton: 'cairo-font'
                    }
                });
            @endif

            const libyanCard = document.getElementById('libyanCard');
            const foreignCard = document.getElementById('foreignCard');
            const step1Next = document.getElementById('step1Next');
            const nationalIdField = document.getElementById('nationalIdField');
            const passportField = document.getElementById('passportField');
            
            libyanCard.addEventListener('click', function() {
                foreignCard.classList.remove('active');
                libyanCard.classList.add('active');
                selectedIdentityType = 'national_id';
                step1Next.disabled = false;
                
                nationalIdField.style.display = 'block';
                passportField.style.display = 'none';
                document.getElementById('national_id').required = true;
                document.getElementById('passport_no').required = false;
                document.getElementById('nationality').value = 'ليبية';
            });
            
            foreignCard.addEventListener('click', function() {
                libyanCard.classList.remove('active');
                foreignCard.classList.add('active');
                selectedIdentityType = 'passport';
                step1Next.disabled = false;
                
                nationalIdField.style.display = 'none';
                passportField.style.display = 'block';
                document.getElementById('national_id').required = false;
                document.getElementById('passport_no').required = true;
                document.getElementById('nationality').value = '';
            });
        });

        function validateAndNext(currentStep, nextStep) {
            if (validateStep(currentStep)) {
                goToStep(nextStep);
            } else {
                // Error is already shown in validateStep function
            }
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
            const requiredInputs = currentStepElement.querySelectorAll('[required]');
            
            for (let input of requiredInputs) {
                if (!input.value.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="font-family: Cairo; color: #3c5e7f;">حقل مطلوب</span>',
                        html: `<p style="font-family: Cairo; font-size: 16px; direction: rtl;">يرجى تعبئة حقل <strong>${getFieldLabel(input)}</strong></p>`,
                        confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                        confirmButtonColor: '#3c5e7f',
                        backdrop: 'rgba(60, 94, 127, 0.4)',
                        customClass: {
                            popup: 'cairo-font',
                            confirmButton: 'cairo-font'
                        }
                    });
                    input.focus();
                    return false;
                }
                
                // Validate birth date
                if (input.id === 'birth_date') {
                    const birthDate = new Date(input.value);
                    const maxDate = new Date('2008-12-31');
                    if (birthDate > maxDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: '<span style="font-family: Cairo; color: #998965;">تاريخ غير صحيح</span>',
                            html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">تاريخ الميلاد يجب أن يكون <strong>2008 أو أقل</strong></p>',
                            confirmButtonText: '<span style="font-family: Cairo;">تصحيح</span>',
                            confirmButtonColor: '#998965',
                            backdrop: 'rgba(153, 137, 101, 0.4)',
                            customClass: {
                                popup: 'cairo-font',
                                confirmButton: 'cairo-font'
                            }
                        });
                        input.focus();
                        return false;
                    }
                }
                
                // Validate national ID length
                if (input.id === 'national_id' && input.required) {
                    if (input.value.length !== 12) {
                        Swal.fire({
                            icon: 'error',
                            title: '<span style="font-family: Cairo; color: #3c5e7f;">رقم وطني غير صحيح</span>',
                            html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">الرقم الوطني / او الاداري يجب أن يكون <strong>12 رقم</strong><br>الأرقام المدخلة حالياً: <strong>' + input.value.length + '</strong></p>',
                            confirmButtonText: '<span style="font-family: Cairo;">تصحيح</span>',
                            confirmButtonColor: '#3c5e7f',
                            backdrop: 'rgba(60, 94, 127, 0.4)',
                            customClass: {
                                popup: 'cairo-font',
                                confirmButton: 'cairo-font'
                            }
                        });
                        input.focus();
                        return false;
                    }
                }
                
                // Validate phone number
                if (input.id === 'phone') {
                    if (input.value.length !== 9) {
                        Swal.fire({
                            icon: 'error',
                            title: '<span style="font-family: Cairo; color: #3c5e7f;">رقم هاتف غير صحيح</span>',
                            html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">رقم الهاتف يجب أن يكون <strong>9 أرقام</strong> بدون صفر<br>الأرقام المدخلة حالياً: <strong>' + input.value.length + '</strong></p>',
                            confirmButtonText: '<span style="font-family: Cairo;">تصحيح</span>',
                            confirmButtonColor: '#3c5e7f',
                            backdrop: 'rgba(60, 94, 127, 0.4)',
                            customClass: {
                                popup: 'cairo-font',
                                confirmButton: 'cairo-font'
                            }
                        });
                        input.focus();
                        return false;
                    }
                }
            }
            
            return true;
        }

        function getFieldLabel(input) {
            const label = input.closest('.col-md-6, .col-md-12')?.querySelector('.form-label');
            if (label) {
                return label.textContent.replace('*', '').trim();
            }
            return 'هذا الحقل';
        }

        function goToStep(step) {
            if (step === 5) {
                updateReviewSection();
            }
            
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
            
            updateStepIndicators(step);
            currentStep = step;
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function prevStep(step) {
            goToStep(step);
        }

        function updateStepIndicators(activeStep) {
            document.querySelectorAll('.step-item').forEach(item => {
                const stepNum = parseInt(item.getAttribute('data-step'));
                item.classList.remove('active', 'completed');
                
                if (stepNum < activeStep) {
                    item.classList.add('completed');
                } else if (stepNum === activeStep) {
                    item.classList.add('active');
                }
            });
        }

        function updateReviewSection() {
            // Identity type
            const identityType = document.querySelector('input[name="identity_type"]:checked').value;
            document.getElementById('review_identity_type').textContent = 
                identityType === 'national_id' ? 'ليبي الجنسية' : 'جنسية أخرى';
            
            // Full name
            const firstName = document.getElementById('first_name').value;
            const fatherName = document.getElementById('father_name').value;
            const grandfatherName = document.getElementById('grandfather_name').value;
            const lastName = document.getElementById('last_name').value;
            document.getElementById('review_full_name').textContent = 
                `${firstName} ${fatherName} ${grandfatherName} ${lastName}`.trim();
            
            // National ID or Passport
            if (identityType === 'national_id') {
                document.getElementById('review_national_id_row').style.display = 'flex';
                document.getElementById('review_passport_row').style.display = 'none';
                document.getElementById('review_national_id').textContent = 
                    document.getElementById('national_id').value;
            } else {
                document.getElementById('review_national_id_row').style.display = 'none';
                document.getElementById('review_passport_row').style.display = 'flex';
                document.getElementById('review_passport').textContent = 
                    document.getElementById('passport_no').value;
            }
            
            // Birth date
            document.getElementById('review_birth_date').textContent = 
                document.getElementById('birth_date').value;
            
            // Gender
            const gender = document.getElementById('gender').value;
            document.getElementById('review_gender').textContent = 
                gender === 'male' ? 'ذكر' : 'أنثى';
            
            // Phone
            document.getElementById('review_phone').textContent = 
                '+218' + document.getElementById('phone').value;
            
            // WhatsApp
            const whatsapp = document.getElementById('whatsapp').value;
            if (whatsapp) {
                document.getElementById('review_whatsapp_row').style.display = 'flex';
                document.getElementById('review_whatsapp').textContent = '+218' + whatsapp;
            } else {
                document.getElementById('review_whatsapp_row').style.display = 'none';
            }
            
            // Residence
            document.getElementById('review_residence').textContent = 
                document.getElementById('current_residence').value;
            
            // Office
            document.getElementById('review_office').textContent = 
                document.getElementById('office_name').value;
            
            // Cluster
            const clusterId = document.getElementById('cluster_id').value;
            const clusterText = document.querySelector(`#cluster_id option[value="${clusterId}"]`).textContent;
            document.getElementById('review_cluster').textContent = clusterText;
            
            // Narration
            document.getElementById('review_narration').textContent = 
                document.getElementById('narration_name').value;
            
            // Drawing
            document.getElementById('review_drawing').textContent = 
                document.getElementById('drawing_name').value;
        }
    </script>
</body>
</html>