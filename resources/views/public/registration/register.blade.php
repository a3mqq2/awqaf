<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل جديد</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
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

        /* Autocomplete Styles */
        .autocomplete-wrapper {
            position: relative;
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--primary-color);
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none;
        }

        .autocomplete-results.show {
            display: block;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background: rgba(60, 94, 127, 0.1);
        }

        .autocomplete-item.active {
            background: rgba(60, 94, 127, 0.15);
        }

        .autocomplete-loading {
            padding: 12px 16px;
            text-align: center;
            color: var(--primary-color);
            font-weight: 600;
        }

        .autocomplete-empty {
            padding: 12px 16px;
            text-align: center;
            color: #6c757d;
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
    <div class="register-container">
        <div class="header-card">
            <h1 class="page-title">تسجيل جديد</h1>
            <p class="text-muted mb-0">املأ البيانات المطلوبة بدقة</p>
        </div>

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
                        <small class="text-muted">الرقم الوطني / او الإداري</small>
                    </label>
                    
                    <label class="identity-card" id="foreignCard">
                        <input type="radio" name="identity_type" value="passport" required>
                        <div class="identity-icon">🌍</div>
                        <div class="identity-label">جنسية أخرى</div>
                        <small class="text-muted">رقم الهوية</small>
                    </label>
                </div>

                <div class="d-flex gap-3">
                    <a href="/registration" class="btn btn-outline-secondary btn-navigation">رجوع</a>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(1, 2)" id="step1Next" disabled>التالي</button>
                </div>
            </div>

            <!-- Step 2: Personal Information -->
            <div class="form-step" data-step="2">
                <h2 class="step-title">البيانات الشخصية</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required value="{{ old('first_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اسم الأب</label> <span class="text-danger">*</span></label>
                        <input type="text" name="father_name" id="father_name" class="form-control" required value="{{ old('father_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اسم الجد</label> <span class="text-danger">*</span></label>
                        <input type="text" name="grandfather_name" id="grandfather_name" required class="form-control" value="{{ old('grandfather_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اللقب</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required value="{{ old('last_name') }}">
                    </div>

                    <div class="col-md-6" id="nationalIdField" style="display: none;">
                        <label class="form-label">
                            الرقم الوطني / او الإداري
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="national_id" id="national_id" class="form-control" placeholder="12 رقم" maxlength="12">
                    </div>

                    <div class="col-md-6" id="passportField" style="display: none;">
                        <label class="form-label">رقم الهوية <span class="text-danger">*</span></label>
                        <input type="text" name="passport_no" id="passport_no" class="form-control" value="{{ old('passport_no') }}">
                    </div>

                    <input type="hidden" name="nationality" id="nationality" value="">

                    <input type="hidden" name="current_residence" id="current_residence_hidden">

                    <div class="col-md-6">
                        <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control" max="2009-12-31" required value="{{ old('birth_date') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الجنس <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="">اختر...</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(1)">السابق</button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(2, 3)">التالي</button>
                </div>
            </div>

            <!-- Step 3: Contact Information -->
            <div class="form-step" data-step="3">
                <h2 class="step-title">بيانات الاتصال والإقامة</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">رقم الهاتف (بدون صفر) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+218</span>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="912345678" maxlength="9" required value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رقم الواتساب (اختياري)</label>
                        <div class="input-group">
                            <span class="input-group-text">+218</span>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="912345678" maxlength="9" value="{{ old('whatsapp') }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">مكان الإقامة الحالي <span class="text-danger">*</span></label>
                        <input type="text" name="current_residence" id="current_residence" class="form-control" placeholder="المدينة والمنطقة" required value="{{ old('current_residence') }}">
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(2)">السابق</button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(3, 4)">التالي</button>
                </div>
            </div>

            <!-- Step 4: Exam Information with Autocomplete -->
            <div class="form-step" data-step="4">
                <h2 class="step-title">بيانات الامتحان</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">المكتب <span class="text-danger">*</span></label>
                        <div class="autocomplete-wrapper">
                            <input type="text" 
                                   name="office_name" 
                                   id="office_name" 
                                   class="form-control autocomplete-input" 
                                   placeholder="ابدأ بالكتابة للبحث أو أدخل اسم المكتب..." 
                                   autocomplete="off"
                                   required>
                            <input type="hidden" name="office_id" id="office_id">
                            <div class="autocomplete-results" id="office_results"></div>
                        </div>
                        <small class="text-muted">يمكنك الاختيار من القائمة أو كتابة اسم جديد</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">التجمع <span class="text-danger">*</span></label>
                        <select name="cluster_id" id="cluster_id" class="form-select" required>
                            <option value="">اختر التجمع...</option>
                            @foreach ($clusters as $cluster)
                                <option value="{{$cluster->id}}">{{$cluster->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الرواية <span class="text-danger">*</span></label>
                        <div class="autocomplete-wrapper">
                            <input type="text" 
                                   name="narration_name" 
                                   id="narration_name" 
                                   class="form-control autocomplete-input" 
                                   placeholder="ابدأ بالكتابة للبحث أو أدخل اسم الرواية..." 
                                   autocomplete="off"
                                   required>
                            <input type="hidden" name="narration_id" id="narration_id">
                            <div class="autocomplete-results" id="narration_results"></div>
                        </div>
                        <small class="text-muted">يمكنك الاختيار من القائمة أو كتابة اسم جديد</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الرسم <span class="text-danger">*</span></label>
                        <div class="autocomplete-wrapper">
                            <input type="text" 
                                   name="drawing_name" 
                                   id="drawing_name" 
                                   class="form-control autocomplete-input" 
                                   placeholder="ابدأ بالكتابة للبحث أو أدخل اسم الرسم..." 
                                   autocomplete="off"
                                   required>
                            <input type="hidden" name="drawing_id" id="drawing_id">
                            <div class="autocomplete-results" id="drawing_results"></div>
                        </div>
                        <small class="text-muted">يمكنك الاختيار من القائمة أو كتابة اسم جديد</small>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(3)">السابق</button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="validateAndNext(4, 5)">التالي</button>
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
                    <button type="button" class="btn btn-outline-secondary btn-navigation" onclick="prevStep(4)">تعديل البيانات</button>
                    <button type="button" class="btn btn-primary btn-navigation flex-grow-1" onclick="showFinalConfirmation()">✓ تأكيد وإتمام التسجيل</button>
                </div>
            </div>





        </form>

        
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
    
    <script>
        let currentStep = 1;
        let selectedIdentityType = null;

        // Autocomplete System
        class Autocomplete {
            constructor(inputId, resultsId, hiddenId, searchUrl) {
                this.input = document.getElementById(inputId);
                this.results = document.getElementById(resultsId);
                this.hidden = document.getElementById(hiddenId);
                this.searchUrl = searchUrl;
                this.timeout = null;
                this.selectedIndex = -1;
                this.items = [];
                
                this.init();
            }
            
            init() {
                this.input.addEventListener('input', (e) => this.handleInput(e));
                this.input.addEventListener('focus', (e) => this.handleFocus(e));
                this.input.addEventListener('keydown', (e) => this.handleKeydown(e));
                
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.autocomplete-wrapper')) {
                        this.hideResults();
                    }
                });
            }
            
            handleInput(e) {
                clearTimeout(this.timeout);
                const query = e.target.value.trim();
                
                if (query.length < 2) {
                    this.hideResults();
                    // Don't clear hidden value - allow manual text entry
                    return;
                }
                
                this.timeout = setTimeout(() => this.search(query), 300);
            }
            
            handleFocus(e) {
                if (this.input.value.trim().length >= 2) {
                    this.search(this.input.value.trim());
                }
            }
            
            handleKeydown(e) {
                if (!this.results.classList.contains('show')) return;
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        this.selectNext();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        this.selectPrev();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (this.selectedIndex >= 0) {
                            this.selectItem(this.items[this.selectedIndex]);
                        }
                        break;
                    case 'Escape':
                        this.hideResults();
                        break;
                }
            }
            
            async search(query) {
                this.showLoading();
                
                try {
                    const response = await fetch(`${this.searchUrl}?query=${encodeURIComponent(query)}`);
                    const data = await response.json();
                    this.items = data;
                    this.displayResults(data);
                } catch (error) {
                    console.error('Search error:', error);
                    this.showError();
                }
            }
            
            showLoading() {
                this.results.innerHTML = '<div class="autocomplete-loading">جاري البحث...</div>';
                this.results.classList.add('show');
            }
            
            showError() {
                this.results.innerHTML = '<div class="autocomplete-empty">حدث خطأ في البحث</div>';
            }
            
            displayResults(data) {
                if (data.length === 0) {
                    this.results.innerHTML = '<div class="autocomplete-empty">لا توجد نتائج</div>';
                    return;
                }
                
                this.results.innerHTML = data.map((item, index) => 
                    `<div class="autocomplete-item" data-id="${item.id}" data-name="${item.name}" data-index="${index}">
                        ${item.name}
                    </div>`
                ).join('');
                
                this.results.querySelectorAll('.autocomplete-item').forEach(item => {
                    item.addEventListener('click', () => {
                        this.selectItem({
                            id: item.dataset.id,
                            name: item.dataset.name
                        });
                    });
                });
                
                this.selectedIndex = -1;
                this.results.classList.add('show');
            }
            
            selectNext() {
                const items = this.results.querySelectorAll('.autocomplete-item');
                if (items.length === 0) return;
                
                if (this.selectedIndex < items.length - 1) {
                    this.selectedIndex++;
                    this.updateSelection(items);
                }
            }
            
            selectPrev() {
                const items = this.results.querySelectorAll('.autocomplete-item');
                if (items.length === 0) return;
                
                if (this.selectedIndex > 0) {
                    this.selectedIndex--;
                    this.updateSelection(items);
                }
            }
            
            updateSelection(items) {
                items.forEach(item => item.classList.remove('active'));
                if (this.selectedIndex >= 0) {
                    items[this.selectedIndex].classList.add('active');
                    items[this.selectedIndex].scrollIntoView({ block: 'nearest' });
                }
            }
            
            selectItem(item) {
                this.input.value = item.name;
                this.hidden.value = item.id;
                this.hideResults();
                this.input.dispatchEvent(new Event('change'));
            }
            
            hideResults() {
                this.results.classList.remove('show');
                this.selectedIndex = -1;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Autocomplete for all three fields
            const officeAutocomplete = new Autocomplete(
                'office_name',
                'office_results',
                'office_id',
                '/registration/search/offices'
            );
            
            const narrationAutocomplete = new Autocomplete(
                'narration_name',
                'narration_results',
                'narration_id',
                '/registration/search/narrations'
            );
            
            const drawingAutocomplete = new Autocomplete(
                'drawing_name',
                'drawing_results',
                'drawing_id',
                '/registration/search/drawings'
            );

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
                document.getElementById('nationality').value = 'ليبي';
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
                document.getElementById('nationality').value = 'غير ليبي';
            });
        });

        function validateAndNext(currentStep, nextStep) {
            if (validateStep(currentStep)) {
                goToStep(nextStep);
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
                        customClass: {
                            popup: 'cairo-font',
                            confirmButton: 'cairo-font'
                        }
                    });
                    input.focus();
                    return false;
                }
                
                if (input.id === 'birth_date') {
                    const birthDate = new Date(input.value);
                    const maxDate = new Date('2009-12-31');
                    if (birthDate > maxDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: '<span style="font-family: Cairo; color: #998965;">تاريخ غير صحيح</span>',
                            html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">تاريخ الميلاد يجب أن يكون <strong>2009 أو أقل</strong></p>',
                            confirmButtonText: '<span style="font-family: Cairo;">تصحيح</span>',
                            confirmButtonColor: '#998965',
                            customClass: {
                                popup: 'cairo-font',
                                confirmButton: 'cairo-font'
                            }
                        });
                        input.focus();
                        return false;
                    }
                }
                
                if (input.id === 'national_id' && input.required) {
                    // No validation - field is open
                }
                
                if (input.id === 'phone') {
                    if (input.value.length !== 9) {
                        Swal.fire({
                            icon: 'error',
                            title: '<span style="font-family: Cairo; color: #3c5e7f;">رقم هاتف غير صحيح</span>',
                            html: '<p style="font-family: Cairo; font-size: 16px; direction: rtl;">رقم الهاتف يجب أن يكون <strong>9 أرقام</strong> بدون صفر<br>الأرقام المدخلة حالياً: <strong>' + input.value.length + '</strong></p>',
                            confirmButtonText: '<span style="font-family: Cairo;">تصحيح</span>',
                            confirmButtonColor: '#3c5e7f',
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
            
            // No validation needed for office, narration, and drawing
            // They can be entered as text if not found in autocomplete
            
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
            const identityType = document.querySelector('input[name="identity_type"]:checked').value;
            document.getElementById('review_identity_type').textContent = 
                identityType === 'national_id' ? 'ليبي الجنسية' : 'جنسية أخرى';
            
            const firstName = document.getElementById('first_name').value;
            const fatherName = document.getElementById('father_name').value;
            const grandfatherName = document.getElementById('grandfather_name').value;
            const lastName = document.getElementById('last_name').value;
            document.getElementById('review_full_name').textContent = 
                `${firstName} ${fatherName} ${grandfatherName} ${lastName}`.trim();
            
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
            
            document.getElementById('review_birth_date').textContent = 
                document.getElementById('birth_date').value;
            
            const gender = document.getElementById('gender').value;
            document.getElementById('review_gender').textContent = 
                gender === 'male' ? 'ذكر' : 'أنثى';
            
            document.getElementById('review_phone').textContent = 
                '+218' + document.getElementById('phone').value;
            
            const whatsapp = document.getElementById('whatsapp').value;
            if (whatsapp) {
                document.getElementById('review_whatsapp_row').style.display = 'flex';
                document.getElementById('review_whatsapp').textContent = '+218' + whatsapp;
            } else {
                document.getElementById('review_whatsapp_row').style.display = 'none';
            }
            
            document.getElementById('review_residence').textContent = 
                document.getElementById('current_residence').value;
            
            document.getElementById('review_office').textContent = 
                document.getElementById('office_name').value;
            
            const clusterId = document.getElementById('cluster_id').value;
            const clusterText = document.querySelector(`#cluster_id option[value="${clusterId}"]`).textContent;
            document.getElementById('review_cluster').textContent = clusterText;
            
            document.getElementById('review_narration').textContent = 
                document.getElementById('narration_name').value;
            
            document.getElementById('review_drawing').textContent = 
                document.getElementById('drawing_name').value;
        }

        function showFinalConfirmation() {
            Swal.fire({
                title: '<div style="font-family: Cairo; color: #3c5e7f; font-size: 26px; font-weight: 700; line-height: 1.6;">تأكيد التسجيل النهائي</div>',
                html: `
                    <div style="font-family: Cairo; text-align: center; padding: 20px; direction: rtl;">
                        <div style="font-size: 20px; font-weight: 600; color: #212529; margin-bottom: 20px;">
                            هل أنت متأكد من التسجيل في
                        </div>
                        <div style="background: linear-gradient(135deg, #3c5e7f 0%, #2d4a5f 100%); 
                                    color: white; 
                                    padding: 25px; 
                                    border-radius: 15px; 
                                    margin: 20px 0;
                                    box-shadow: 0 5px 20px rgba(60, 94, 127, 0.3);">
                            <div style="font-size: 24px; font-weight: 800; margin-bottom: 10px;">
                                امتحان الإجازة
                            </div>
                            <div style="font-size: 32px; font-weight: 900; letter-spacing: 2px;">
                                2025 - 1447م
                            </div>
                        </div>
                        <div style="background: #fff3cd; 
                                    border: 2px solid #ffc107; 
                                    border-radius: 10px; 
                                    padding: 15px; 
                                    margin-top: 20px;
                                    color: #856404;">
                            <strong>⚠️ تنبيه مهم:</strong>
                            <div style="margin-top: 10px;">
                                بعد التأكيد لن تتمكن من تعديل البيانات
                            </div>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3c5e7f',
                cancelButtonColor: '#998965',
                confirmButtonText: '<span style="font-family: Cairo; font-size: 18px; font-weight: 700;">نعم، أنا متأكد</span>',
                cancelButtonText: '<span style="font-family: Cairo; font-size: 18px; font-weight: 600;">مراجعة البيانات</span>',
                reverseButtons: true,
                width: '600px',
                padding: '2em',
                backdrop: 'rgba(60, 94, 127, 0.4)',
                customClass: {
                    popup: 'cairo-font',
                    confirmButton: 'cairo-font',
                    cancelButton: 'cairo-font'
                },
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: '<span style="font-family: Cairo;">جاري إتمام التسجيل...</span>',
                        html: '<p style="font-family: Cairo;">يرجى الانتظار</p>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    document.getElementById('registrationForm').submit();
                }
            });
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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