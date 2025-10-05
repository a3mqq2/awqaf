<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من تسجيل امتحان الإجازة 2025/2026م</title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
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
        
        .footer-text {
            text-align: center;
            color: #6c757d;
            margin-top: 30px;
            font-size: 14px;
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
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <!-- Logo & Title Section -->
        <div class="logo-section">
            <img src="{{asset('logo-primary.png')}}" alt="">
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
                <a href="{{ route('public.registration.check.form') }}" class="text-decoration-none">
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

            <!-- New Registration Card -->
            <div class="col-md-6">
                <a href="{{ route('public.registration.register.form') }}" class="text-decoration-none">
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
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="footer-text">
            © {{ date('Y') }} وزارة الأوقاف والشؤون الإسلامية - جميع الحقوق محفوظة
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Show elegant alert on page load
        // window.addEventListener('DOMContentLoaded', function() {
        //     Swal.fire({
        //         title: '<strong style="color: #3c5e7f;">تنبيه هام</strong>',
        //         icon: 'info',
        //         html: `
        //             <div style="text-align: right; direction: rtl; font-family: 'Cairo', sans-serif; line-height: 2;">
        //                 <p style="font-size: 18px; color: #2c3e50; margin-bottom: 15px;">
        //                     <strong>عزيزي الممتحن،</strong>
        //                 </p>
        //                 <p style="font-size: 16px; color: #34495e; margin-bottom: 20px;">
        //                     يرجى مراجعة <span style="color: #3c5e7f; font-weight: bold;">رابط الاستعلام</span> 
        //                     لمعرفة تأكيد قبول تسجيلك في امتحان الإجازة
        //                 </p>
        //                 <div style="background: #f0f4f8; padding: 15px; border-radius: 10px; border-right: 4px solid #3c5e7f;">
        //                     <p style="font-size: 15px; color: #555; margin: 0;">
        //                          يمكنك التحقق من حالة تسجيلك من خلال الضغط على زر 
        //                         <strong>"الاستعلام عن تسجيل سابق"</strong>
        //                     </p>
        //                 </div>
        //             </div>
        //         `,
        //         showCloseButton: true,
        //         confirmButtonText: '<span style="font-family: Cairo;">فهمت، شكراً</span>',
        //         confirmButtonColor: '#3c5e7f',
        //         width: '600px',
        //         padding: '2em',
        //         backdrop: 'rgba(60, 94, 127, 0.4)',
        //         customClass: {
        //             popup: 'custom-alert-popup',
        //             confirmButton: 'custom-confirm-button'
        //         }
        //     });
        // });
    </script>
    
    <style>
        .custom-alert-popup {
            border-radius: 20px !important;
            font-family: 'Cairo', sans-serif !important;
        }
        
        .custom-confirm-button {
            font-size: 16px !important;
            padding: 12px 30px !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }
        
        .swal2-icon.swal2-info {
            border-color: #3c5e7f !important;
            color: #3c5e7f !important;
        }
    </style>
</body>
</html>