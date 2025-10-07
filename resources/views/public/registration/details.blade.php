<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل التسجيل</title>
    
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
        
        .details-container {
            max-width: 900px;
            margin: 40px auto;
        }
        
        .status-badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-withdrawn {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
        
        .detail-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--primary-color);
            min-width: 150px;
        }
        
        .detail-value {
            color: #495057;
            flex: 1;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-secondary {
            background: var(--secondary-color);
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .rejection-alert {
            background: #fff5f5;
            border: 2px solid #dc3545;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .rejection-title {
            color: #dc3545;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .rejection-reason {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-right: 4px solid #dc3545;
            font-size: 16px;
            line-height: 1.8;
            color: #495057;
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
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                margin-bottom: 5px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
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
    <div class="details-container">
        <div class="detail-card text-center">
            <h1 class="section-title">بيانات التسجيل</h1>
            
            @if($examinee->status == 'confirmed')
                <div class="status-badge status-confirmed">✓ التسجيل مؤكد</div>
            @elseif($examinee->status == 'rejected')
                <div class="status-badge status-rejected">✗ مرفوض</div>
            @elseif($examinee->status == 'pending' || $examinee->status == 'under_review')
                <div class="status-badge status-pending">⏳ بانتظار التأكيد</div>
            @else
                <div class="status-badge status-withdrawn">✗ منسحب</div>
            @endif
        </div>

        <!-- Rejection Alert -->
        @if($examinee->status == 'rejected' && $examinee->rejection_reason)
        <div class="rejection-alert">
            <div class="rejection-title">
                <span>⚠️</span>
                <span>سبب رفض التسجيل</span>
            </div>
            <div class="rejection-reason">
                {{ $examinee->rejection_reason }}
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    للاستفسار أو التصحيح، يرجى التواصل مع الجهة المختصة
                </small>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="detail-card">
            <div class="action-buttons">
                @if($examinee->status == 'pending')
                    <form action="{{ route('public.registration.confirm', $examinee) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            ✓ تأكيد التسجيل
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger flex-grow-1" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        ✗ الانسحاب من التسجيل
                    </button>
                @endif
                

                @if ($examinee->status == 'confirmed')
                    <a href="{{ route('public.registration.print-card', ['ids' => $examinee->id]) }}" class="btn btn-success flex-grow-1" target="_blank">
                        🖨️ طباعة بطاقة الدخول للامتحان
                    </a>
                @endif

                <a href="{{ route('public.registration.index') }}" class="btn btn-secondary">
                    العودة للرئيسية
                </a>
            </div>
        </div>
              
        <!-- Personal Information -->
        <div class="detail-card">
            <h3 class="section-title">البيانات الشخصية</h3>
            
            <div class="detail-row">
                <div class="detail-label">الاسم الكامل:</div>
                <div class="detail-value">{{ $examinee->full_name }}</div>
            </div>
            
            @if($examinee->national_id)
            <div class="detail-row">
                <div class="detail-label">الرقم الوطني:</div>
                <div class="detail-value">{{ $examinee->national_id }}</div>
            </div>
            @endif
            
            @if($examinee->passport_no)
            <div class="detail-row">
                <div class="detail-label">رقم الجواز:</div>
                <div class="detail-value">{{ $examinee->passport_no }}</div>
            </div>
            @endif
            
            <div class="detail-row">
                <div class="detail-label">الجنسية:</div>
                <div class="detail-value">{{ $examinee->nationality ?? '-' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">تاريخ الميلاد:</div>
                <div class="detail-value">{{ $examinee->birth_date?->format('Y-m-d') ?? '-' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">الجنس:</div>
                <div class="detail-value">{{ $examinee->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">رقم الهاتف:</div>
                <div class="detail-value">{{ $examinee->phone ?? '-' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">مكان الإقامة:</div>
                <div class="detail-value">{{ $examinee->current_residence ?? '-' }}</div>
            </div>
        </div>

        <!-- Exam Information -->
        <div class="detail-card">
            <h3 class="section-title">بيانات الامتحان</h3>
            
            <div class="detail-row">
                <div class="detail-label">المكتب:</div>
                <div class="detail-value">{{ $examinee->office->name ?? '-' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">التجمع:</div>
                <div class="detail-value">{{ $examinee->cluster->name ?? '-' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">الرواية:</div>
                <div class="detail-value">{{ $examinee->narration->name ?? '-' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">الرسم:</div>
                <div class="detail-value">{{ $examinee->drawing->name ?? '-' }}</div>
            </div>
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

                        <input type="hidden" name="email_to" value="info@waqsa.ly">

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

    <!-- Withdraw Confirmation Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="border-bottom: 2px solid var(--primary-color);">
                    <h5 class="modal-title" style="color: var(--primary-color); font-weight: 700;">
                        تأكيد الانسحاب
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('public.registration.withdraw', $examinee) }}" method="POST" id="withdrawForm">
                    @csrf
                    <div class="modal-body" style="padding: 30px;">
                        <div class="alert alert-danger">
                            <strong>⚠️ تحذير:</strong> الانسحاب من التسجيل يعني إلغاء مشاركتك في امتحان الإجازة
                        </div>
                        
                        <p class="text-center mb-0" style="font-size: 18px; line-height: 1.8;">
                            هل أنت متأكد من رغبتك في الانسحاب من التسجيل؟
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            إلغاء
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmWithdrawBtn">
                            نعم، أريد الانسحاب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Handle withdraw confirmation with second modal
        document.getElementById('confirmWithdrawBtn')?.addEventListener('click', function() {
            // Close the first modal
            const withdrawModal = bootstrap.Modal.getInstance(document.getElementById('withdrawModal'));
            withdrawModal.hide();
            
            // Show confirmation modal
            Swal.fire({
                icon: 'info',
                title: '<span style="font-family: Cairo; color: #3c5e7f;">تم الانسحاب</span>',
                html: '<p style="font-family: Cairo; font-size: 18px; direction: rtl; line-height: 2;">تم إلغاء مشاركتك في امتحان إجازة حفظ القرآن الكريم كاملاً لهذا العام</p>',
                confirmButtonText: '<span style="font-family: Cairo;">حسناً</span>',
                confirmButtonColor: '#3c5e7f',
                backdrop: 'rgba(60, 94, 127, 0.4)',
                allowOutsideClick: false,
                customClass: {
                    popup: 'cairo-font',
                    confirmButton: 'cairo-font'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form after user confirms
                    document.getElementById('withdrawForm').submit();
                }
            });
        });

        // Handle other session messages
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
    </script>
</body>
</html>