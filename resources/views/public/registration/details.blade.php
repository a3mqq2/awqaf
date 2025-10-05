<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل التسجيل</title>
    
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