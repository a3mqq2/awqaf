<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعلام عن بيانات ممتحن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Changa', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .check-card {
            max-width: 520px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-header {
            background-color: #3c5e7f;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .logo-container {
            width: 140px;
            height: 140px;
            margin: 0 auto 1.5rem;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .card-header h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .card-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
            font-weight: 400;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: #3c5e7f;
            box-shadow: 0 0 0 3px rgba(60, 94, 127, 0.1);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-left: none;
            color: #6c757d;
        }
        
        .input-group .form-control {
            border-right: 1px solid #ddd;
        }
        
        .btn-check {
            background-color: #3c5e7f;
            border: none;
            border-radius: 4px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 1rem;
            color: white;
            transition: background-color 0.2s;
        }
        
        .btn-check:hover {
            background-color: #2d4960;
        }
        
        .alert {
            border-radius: 4px;
            border: none;
            font-size: 0.9rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .mb-3 {
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="check-card card">
            <div class="card-header">
               <div class="logo">
                  <img src="{{ asset('logo-white.png') }}" style="width:100%;"  alt="الشعار">
               </div>

                <h3>استعلام عن بيانات ممتحن</h3>
                <p>أدخل بياناتك للاستعلام عن حالة التسجيل</p>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="ti ti-circle-check me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="ti ti-alert-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('examinee.check') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="national_id" class="form-label">
                            <i class="ti ti-id-badge me-1"></i>
                            الرقم الوطني
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti ti-id"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('national_id') is-invalid @enderror" 
                                   id="national_id" 
                                   name="national_id" 
                                   placeholder="أدخل الرقم الوطني"
                                   value="{{ old('national_id') }}"
                                   required>
                        </div>
                        @error('national_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="passport_no" class="form-label">
                            <i class="ti ti-passport me-1"></i>
                            رقم الهوية
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti ti-ticket"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('passport_no') is-invalid @enderror" 
                                   id="passport_no" 
                                   name="passport_no" 
                                   placeholder="أدخل رقم الهوية"
                                   value="{{ old('passport_no') }}"
                                   required>
                        </div>
                        @error('passport_no')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

           

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-check">
                            <i class="ti ti-search me-2"></i>
                            استعلام عن البيانات
                        </button>
                    </div>
                </form>

                <div class="footer-text">
                    <i class="ti ti-info-circle me-1"></i>
                    يرجى إدخال البيانات كما تم تسجيلها للحصول على النتيجة الصحيحة
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>