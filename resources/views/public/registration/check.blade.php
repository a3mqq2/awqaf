<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الاستعلام عن التسجيل</title>
    
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
        
        @media (max-width: 768px) {
            .identity-cards {
                grid-template-columns: 1fr;
            }
            
            .step-container {
                padding: 25px;
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
</body>
</html>