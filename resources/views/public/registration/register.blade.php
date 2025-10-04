<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل جديد</title>
    
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
                        <small class="text-muted">الرقم الوطني</small>
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
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="nextStep(2)" id="step1Next" disabled>
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
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            اسم الأب
                        </label>
                        <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ old('father_name') }}">
                        @error('father_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            اسم الجد
                        </label>
                        <input type="text" name="grandfather_name" class="form-control @error('grandfather_name') is-invalid @enderror" value="{{ old('grandfather_name') }}">
                        @error('grandfather_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            اللقب
                        </label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="nationalIdField" style="display: none;">
                        <label class="form-label">
                            الرقم الوطني
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}" placeholder="12 رقم">
                        @error('national_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="passportField" style="display: none;">
                        <label class="form-label">
                            رقم الهوية
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="passport_no" class="form-control @error('passport_no') is-invalid @enderror" value="{{ old('passport_no') }}">
                        @error('passport_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الجنسية
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality') }}" required>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            تاريخ الميلاد
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الجنس
                            <span class="text-danger">*</span>
                        </label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">اختر...</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(1)">
                        السابق
                    </button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="nextStep(3)">
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
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="912345678" value="{{ old('phone') }}" required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            رقم الواتساب (اختياري)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">+218</span>
                            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" placeholder="912345678" value="{{ old('whatsapp') }}">
                        </div>
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">
                            مكان الإقامة الحالي
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="current_residence" class="form-control @error('current_residence') is-invalid @enderror" placeholder="المدينة والمنطقة" value="{{ old('current_residence') }}" required>
                        @error('current_residence')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(2)">
                        السابق
                    </button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="nextStep(4)">
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
                        <select name="office_id" class="form-select @error('office_id') is-invalid @enderror" required>
                            <option value="">اختر المكتب...</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('office_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            التجمع
                            <span class="text-danger">*</span>
                        </label>
                        <select name="cluster_id" class="form-select @error('cluster_id') is-invalid @enderror" required>
                            <option value="">اختر التجمع...</option>
                            @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                    {{ $cluster->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cluster_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الرواية
                            <span class="text-danger">*</span>
                        </label>
                        <select name="narration_id" class="form-select @error('narration_id') is-invalid @enderror" required>
                            <option value="">اختر الرواية...</option>
                            @foreach($narrations as $narration)
                                <option value="{{ $narration->id }}" {{ old('narration_id') == $narration->id ? 'selected' : '' }}>
                                    {{ $narration->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('narration_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            الرسم
                            <span class="text-danger">*</span>
                        </label>
                        <select name="drawing_id" class="form-select @error('drawing_id') is-invalid @enderror" required>
                            <option value="">اختر الرسم...</option>
                            @foreach($drawings as $drawing)
                                <option value="{{ $drawing->id }}" {{ old('drawing_id') == $drawing->id ? 'selected' : '' }}>
                                    {{ $drawing->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('drawing_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <strong>ملاحظة:</strong> تأكد من صحة جميع البيانات قبل التسجيل
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(3)">
                        السابق
                    </button>
                    <button type="submit" class="btn btn-primary btn-navigation flex-grow-1">
                        إتمام التسجيل
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        let selectedIdentityType = null;

        document.addEventListener('DOMContentLoaded', function() {
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
                document.querySelector('input[name="national_id"]').required = true;
                document.querySelector('input[name="passport_no"]').required = false;
            });
            
            foreignCard.addEventListener('click', function() {
                libyanCard.classList.remove('active');
                foreignCard.classList.add('active');
                selectedIdentityType = 'passport';
                step1Next.disabled = false;
                
                nationalIdField.style.display = 'none';
                passportField.style.display = 'block';
                document.querySelector('input[name="national_id"]').required = false;
                document.querySelector('input[name="passport_no"]').required = true;
            });
        });

        function nextStep(step) {
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
            
            updateStepIndicators(step);
            currentStep = step;
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function prevStep(step) {
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
            
            updateStepIndicators(step);
            currentStep = step;
            window.scrollTo({top: 0, behavior: 'smooth'});
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
    </script>
</body>
</html>