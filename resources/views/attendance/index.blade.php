@extends('layouts.app')

@section('title', 'تسجيل الحضور')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">تسجيل الحضور</li>
@endsection

@push('styles')
<style>
    .search-card {
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .search-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .examinee-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }
    .attendance-badge {
        font-size: 1.1rem;
        padding: 10px 20px;
    }
    #searchInput {
        font-size: 2rem;
        padding: 30px;
        text-align: center;
        letter-spacing: 3px;
        font-weight: bold;
    }
    
    /* QR Scanner Styles */
    #qr-reader {
        border: 3px solid #0d6efd;
        border-radius: 10px;
        overflow: hidden;
    }
    
    #qr-reader video {
        width: 100% !important;
        border-radius: 8px;
    }
    
    .qr-scanner-container {
        position: relative;
    }
    
    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 250px;
        height: 250px;
        border: 3px solid #28a745;
        border-radius: 10px;
        pointer-events: none;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    }
    
    .scanner-corner {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 4px solid #28a745;
    }
    
    .scanner-corner.top-left {
        top: -3px;
        left: -3px;
        border-right: none;
        border-bottom: none;
    }
    
    .scanner-corner.top-right {
        top: -3px;
        right: -3px;
        border-left: none;
        border-bottom: none;
    }
    
    .scanner-corner.bottom-left {
        bottom: -3px;
        left: -3px;
        border-right: none;
        border-top: none;
    }
    
    .scanner-corner.bottom-right {
        bottom: -3px;
        right: -3px;
        border-left: none;
        border-top: none;
    }
</style>
@endpush

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <!-- بطاقة البحث -->
        <div class="card search-card mb-4">
            <div class="card-body">
                <!-- QR Code Scanner -->
                <div id="qrScannerSection" class="mb-4 d-none">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>وجّه الكاميرا نحو الـ QR Code الموجود على بطاقة الممتحن</strong>
                    </div>
                    <div class="qr-scanner-container">
                        <div id="qr-reader" style="width: 100%;"></div>
                        <div class="scanner-overlay">
                            <div class="scanner-corner top-left"></div>
                            <div class="scanner-corner top-right"></div>
                            <div class="scanner-corner bottom-left"></div>
                            <div class="scanner-corner bottom-right"></div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-danger" id="stopQrBtn">
                            <i class="ti ti-x me-1"></i>إيقاف الكاميرا
                        </button>
                    </div>
                </div>

                <!-- Manual Input Form -->
                <form id="searchForm">
                    <div class="row">
                        <!-- الرقم الوطني -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-center w-100">
                                <i class="ti ti-id-badge me-1"></i>
                                <strong>أدخل الرقم الوطني أو رقم الجواز</strong>
                            </label>
                            <input type="text" 
                                   name="national_id" 
                                   id="searchInput" 
                                   class="form-control form-control-lg" 
                                   placeholder="_ _ _ _ _ _ _ _ _ _ _ _"
                                   autofocus
                                   required>
                            <small class="text-muted d-block text-center mt-2">
                                <i class="ti ti-info-circle me-1"></i>
                                اضغط Enter أو زر البحث، أو استخدم الكاميرا لمسح الـ QR Code
                            </small>
                        </div>

                        <!-- أزرار -->
                        <div class="col-md-12 mb-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="searchBtn">
                                <i class="ti ti-search me-2"></i>
                                بحث وتسجيل الحضور
                            </button>
                        </div>
                     
                    </div>
                </form>
            </div>
        </div>

        <!-- بطاقة النتيجة -->
        <div class="card d-none" id="resultCard">
            <div class="card-body">
                <div id="resultContent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- HTML5 QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let html5QrCode = null;
let isScanning = false;

$(document).ready(function() {
    // Toggle QR Scanner
    $('#toggleQrBtn').on('click', function() {
        if (!isScanning) {
            startQrScanner();
        } else {
            stopQrScanner();
        }
    });

    // Stop QR Scanner Button
    $('#stopQrBtn').on('click', function() {
        stopQrScanner();
    });

    // البحث وتسجيل الحضور
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        
        const nationalId = $('#searchInput').val().trim();
        
        if (!nationalId) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى إدخال الرقم الوطني أو رقم الجواز',
                confirmButtonText: 'حسناً'
            });
            return;
        }
        
        searchExaminee(nationalId);
    });

    // عرض معلومات الممتحن
    window.showExamineeInfo = function(examinee) {
        const isAttended = examinee.is_attended;
        
        let attendanceStatus = '';
        let actionButton = '';
        
        if (isAttended) {
            attendanceStatus = `
                <div class="alert alert-success text-center">
                    <i class="ti ti-circle-check display-4 mb-3"></i>
                    <h5>✅ الممتحن قد حضر بالفعل</h5>
                    <p class="mb-0">وقت الحضور: ${examinee.attended_at}</p>
                </div>
            `;
            actionButton = `
                <button type="button" class="btn btn-warning btn-lg w-100" onclick="cancelAttendance(${examinee.id})">
                    <i class="ti ti-x me-2"></i>إلغاء الحضور
                </button>
            `;
        } else {
            attendanceStatus = `
                <div class="alert alert-warning text-center">
                    <i class="ti ti-clock display-4 mb-3"></i>
                    <h5>⏳ الممتحن لم يحضر بعد</h5>
                </div>
            `;
            actionButton = `
                <button type="button" class="btn btn-success btn-lg w-100" onclick="markAttendance(${examinee.id})">
                    <i class="ti ti-check me-2"></i>تسجيل الحضور الآن
                </button>
            `;
        }

        const content = `
            ${attendanceStatus}
            <div class="examinee-info mb-4">
                <div class="row">
                    <div class="col-md-12 mb-3 text-center">
                        <label class="text-muted small">الاسم الكامل</label>
                        <h4 class="text-primary">${examinee.full_name}</h4>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">الرقم الوطني</label>
                        <h6>${examinee.national_id}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">رقم الهاتف</label>
                        <h6>${examinee.phone || 'غير متوفر'}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">التجمع</label>
                        <h6><span class="badge bg-primary">${examinee.cluster}</span></h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">الرواية</label>
                        <h6><span class="badge bg-success">${examinee.narration}</span></h6>
                    </div>
                </div>
            </div>
            ${actionButton}
        `;

        $('#resultContent').html(content);
        $('#resultCard').removeClass('d-none');
        
        // تنظيف حقل البحث
        $('#searchInput').val('').focus();
    };

    // عرض رسالة خطأ
    window.showError = function(message) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: message,
            confirmButtonText: 'حسناً'
        });
        
        // تنظيف حقل البحث
        $('#searchInput').val('').focus();
    };

    // السماح بالبحث بالضغط على Enter
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#searchForm').submit();
        }
    });

    // تنظيف الإدخال (أرقام فقط)
    $('#searchInput').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

// Start QR Code Scanner
function startQrScanner() {
    $('#qrScannerSection').removeClass('d-none');
    $('#qrBtnText').text('إيقاف الكاميرا');
    isScanning = true;
    
    html5QrCode = new Html5Qrcode("qr-reader");
    
    const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };
    
    html5QrCode.start(
        { facingMode: "environment" }, // استخدام الكاميرا الخلفية
        config,
        (decodedText, decodedResult) => {
            // عند قراءة QR Code بنجاح
            console.log(`QR Code detected: ${decodedText}`);
            
            // إيقاف الماسح
            stopQrScanner();
            
            // وضع الرقم في حقل الإدخال
            $('#searchInput').val(decodedText);
            
            // البحث تلقائياً
            searchExaminee(decodedText);
        },
        (errorMessage) => {
            // يمكن تجاهل الأخطاء العادية أثناء المسح
        }
    ).catch((err) => {
        console.error(`Unable to start scanning: ${err}`);
        Swal.fire({
            icon: 'error',
            title: 'خطأ في الكاميرا',
            text: 'لا يمكن الوصول إلى الكاميرا. تأكد من منح الصلاحية للموقع.',
            confirmButtonText: 'حسناً'
        });
        stopQrScanner();
    });
}

// Stop QR Code Scanner
function stopQrScanner() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            $('#qrScannerSection').addClass('d-none');
            $('#qrBtnText').text('فتح الكاميرا');
            isScanning = false;
            html5QrCode = null;
        }).catch((err) => {
            console.error(`Error stopping scanner: ${err}`);
        });
    }
}

// Search Examinee Function
function searchExaminee(nationalId) {
    if (!nationalId) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'يرجى إدخال الرقم الوطني أو رقم الجواز',
            confirmButtonText: 'حسناً'
        });
        return;
    }
    
    const formData = {
        national_id: nationalId,
        _token: '{{ csrf_token() }}'
    };

    $('#searchBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>جاري البحث...');
    $('#resultCard').addClass('d-none');

    $.ajax({
        url: '{{ route("attendance.search") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showExamineeInfo(response.examinee);
            } else {
                showError(response.message);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'حدث خطأ أثناء البحث';
            showError(message);
        },
        complete: function() {
            $('#searchBtn').prop('disabled', false).html('<i class="ti ti-search me-2"></i>بحث وتسجيل الحضور');
        }
    });
}

// تسجيل الحضور
function markAttendance(examineeId) {
    Swal.fire({
        title: 'تأكيد الحضور',
        text: 'هل أنت متأكد من تسجيل حضور هذا الممتحن؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، سجّل الحضور',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("attendance.mark") }}',
                method: 'POST',
                data: {
                    examinee_id: examineeId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            timer: 2000
                        });
                        $('#searchInput').val('').focus();
                        $('#resultCard').addClass('d-none');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: response.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'حدث خطأ أثناء تسجيل الحضور';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: message,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

// إلغاء الحضور
function cancelAttendance(examineeId) {
    Swal.fire({
        title: 'تأكيد الإلغاء',
        text: 'هل أنت متأكد من إلغاء حضور هذا الممتحن؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، ألغِ الحضور',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("attendance.cancel") }}',
                method: 'POST',
                data: {
                    examinee_id: examineeId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الإلغاء',
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            timer: 2000
                        });
                        $('#searchInput').val('').focus();
                        $('#resultCard').addClass('d-none');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: response.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'حدث خطأ أثناء إلغاء الحضور';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: message,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}
</script>
@endpush