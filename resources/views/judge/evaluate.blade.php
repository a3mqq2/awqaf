@extends('layouts.app')

@section('title', 'تقييم الممتحن')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judge.dashboard') }}">لوحة المحكم</a></li>
    <li class="breadcrumb-item active">تقييم الممتحن</li>
@endsection

@push('styles')
<style>
    .score-input {
        font-size: 3rem;
        text-align: center;
        font-weight: bold;
        color: #0d6efd;
        border: 3px solid #0d6efd;
        border-radius: 15px;
        padding: 30px;
    }
    .score-input:focus {
        box-shadow: 0 0 20px rgba(13, 110, 253, 0.3);
        border-color: #0d6efd;
    }
    .rating-stars {
        font-size: 3rem;
        transition: all 0.3s ease;
    }
    .rating-stars i {
        margin: 0 8px;
        transition: all 0.3s ease;
    }
    .rating-stars i.ti-star-filled {
        color: #ffc107;
        transform: scale(1.1);
    }
    .rating-stars i.ti-star {
        color: #dee2e6;
    }
    .examinee-card {
        background: #3c5e7f;
        color: white;
        border-radius: 15px;
    }
    .pdf-viewer-container {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        background: #f8f9fa;
    }
    .pdf-tabs {
        background: #fff;
        border-bottom: 2px solid #dee2e6;
    }
    .pdf-tab {
        padding: 12px 20px;
        cursor: pointer;
        border: none;
        background: transparent;
        transition: all 0.3s ease;
    }
    .pdf-tab:hover {
        background: #f8f9fa;
    }
    .pdf-tab.active {
        background: #0d6efd;
        color: white;
        border-bottom: 3px solid #0a58ca;
    }
    .pdf-viewer {
        width: 100%;
        height: 600px;
        border: none;
    }
    .collapse-btn {
        transition: all 0.3s ease;
    }
    .collapse-btn .ti-chevron-down {
        transition: transform 0.3s ease;
    }
    .collapse-btn.collapsed .ti-chevron-down {
        transform: rotate(-180deg);
    }
</style>
@endpush

@section('content')
<div class="row mt-3">
    <div class="col-lg-12">
        <!-- بيانات الممتحن -->
        <div class="card examinee-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div style="width: 80px; height: 80px; margin: 0 auto; border: 3px solid white;" 
                             class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center">
                            <i class="ti ti-user display-4"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h3 class="mb-3 text-white">{{ $evaluation->examinee->full_name }}</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <i class="ti ti-id-badge me-2"></i>
                                <strong>الرقم الوطني:</strong>
                                <div>{{ $evaluation->examinee->national_id ?? $evaluation->examinee->passport_no }}</div>
                            </div>
                            <div class="col-md-3">
                                <i class="ti ti-book me-2"></i>
                                <strong>الرواية:</strong>
                                <div>{{ $evaluation->examinee->narration->name ?? 'غير محدد' }}</div>
                            </div>
                            <div class="col-md-3">
                                <i class="ti ti-users-group me-2"></i>
                                <strong>اللجنة:</strong>
                                <div>{{ $evaluation->committee->name }}</div>
                            </div>
                            <div class="col-md-3">
                                <i class="ti ti-map-pin me-2"></i>
                                <strong>التجمع:</strong>
                                <div>{{ $evaluation->committee->cluster->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- عارض PDFs -->
        @if($evaluation->examinee->narration && $evaluation->examinee->narration->pdfs->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <button class="btn btn-link text-decoration-none w-100 text-start collapse-btn" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#pdfCollapse">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text me-2 text-danger"></i>
                            المنهج العلمي ({{ $evaluation->examinee->narration->pdfs->count() }} ملف)
                        </h5>
                        <i class="ti ti-chevron-down"></i>
                    </div>
                </button>
            </div>
            <div class="collapse" id="pdfCollapse">
                <div class="card-body p-0">
                    <div class="pdf-viewer-container">
                        <!-- PDF Tabs -->
                        @if($evaluation->examinee->narration->pdfs->count() > 1)
                        <div class="pdf-tabs d-flex">
                            @foreach($evaluation->examinee->narration->pdfs as $index => $pdf)
                                <button class="pdf-tab {{ $index == 0 ? 'active' : '' }}" 
                                        onclick="loadPdf('{{ $pdf->file_url }}', this)">
                                    <i class="ti ti-file-text me-2"></i>
                                    {{ $pdf->title }}
                                </button>
                            @endforeach
                        </div>
                        @endif

                        <!-- PDF Viewer -->
                        <div class="position-relative">
                            <iframe id="pdfViewer" 
                                    class="pdf-viewer" 
                                    src="{{ $evaluation->examinee->narration->pdfs->first()->file_url }}#toolbar=1&navpanes=1&scrollbar=1"
                                    type="application/pdf">
                            </iframe>
                            <div id="pdfLoader" class="position-absolute top-50 start-50 translate-middle d-none">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">جاري التحميل...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- نموذج التقييم -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="ti ti-clipboard-check me-2"></i>
                    تقييم المنهج العلمي
                </h5>
            </div>
            <div class="card-body p-5">
                <form id="evaluationForm">
                    @csrf
                    
                    <!-- حقل الدرجة -->
                    <div class="text-center mb-4">
                        <label class="form-label mb-3">
                            <h4>
                                <i class="ti ti-star text-warning me-2"></i>
                                أدخل الدرجة
                            </h4>
                        </label>
                        <input type="number" 
                               name="score" 
                               id="scoreInput" 
                               class="form-control score-input" 
                               min="0" 
                               max="40" 
                               step="0.5"
                               value="{{ $evaluation->score }}"
                               placeholder="0"
                               required
                               autofocus>
                        <small class="text-muted mt-2 d-block">الدرجة القصوى: <strong>40</strong></small>
                    </div>

                    <!-- التقييم البصري -->
                    <div class="text-center mb-4">
                        <div class="rating-stars" id="ratingStars">
                            <i class="ti ti-star"></i>
                            <i class="ti ti-star"></i>
                            <i class="ti ti-star"></i>
                            <i class="ti ti-star"></i>
                            <i class="ti ti-star"></i>
                        </div>
                        <h5 class="mt-3" id="scoreText">
                            <span class="badge bg-secondary" id="scoreBadge">0 / 40</span>
                        </h5>
                    </div>

                    <hr class="my-4">

                    <!-- ملاحظات -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="ti ti-notes me-1"></i>
                            ملاحظات (اختياري)
                        </label>
                        <textarea name="notes" 
                                  id="notesInput" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="اكتب ملاحظاتك حول أداء الممتحن...">{{ $evaluation->notes }}</textarea>
                    </div>

                    <!-- أزرار الحفظ -->
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('judge.dashboard') }}" class="btn btn-secondary btn-lg">
                            <i class="ti ti-arrow-left me-1"></i>
                            رجوع
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="saveBtn">
                            <i class="ti ti-device-floppy me-1"></i>
                            حفظ التقييم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // تحديث التقييم البصري
    $('#scoreInput').on('input', function() {
        let score = parseFloat($(this).val()) || 0;
        score = Math.min(Math.max(score, 0), 40); // بين 0-40
        
        // حساب النسبة المئوية
        const percentage = (score / 40) * 100;
        
        // تحديث النجوم (5 نجوم)
        const stars = Math.ceil(percentage / 20);
        $('#ratingStars i').each(function(index) {
            if (index < stars) {
                $(this).removeClass('ti-star').addClass('ti-star-filled');
            } else {
                $(this).removeClass('ti-star-filled').addClass('ti-star');
            }
        });
        
        // تحديث Badge
        let badgeClass = 'bg-secondary';
        let badgeText = score.toFixed(1) + ' / 40';
        
        if (percentage >= 90) {
            badgeClass = 'bg-success';
            badgeText += ' - ممتاز';
        } else if (percentage >= 80) {
            badgeClass = 'bg-info';
            badgeText += ' - جيد جداً';
        } else if (percentage >= 70) {
            badgeClass = 'bg-primary';
            badgeText += ' - جيد';
        } else if (percentage >= 60) {
            badgeClass = 'bg-warning';
            badgeText += ' - مقبول';
        } else if (score > 0) {
            badgeClass = 'bg-danger';
            badgeText += ' - ضعيف';
        }
        
        $('#scoreBadge')
            .attr('class', 'badge ' + badgeClass)
            .text(badgeText);
    });

    // تشغيل التحديث عند التحميل
    $('#scoreInput').trigger('input');

    // حفظ التقييم
    $('#evaluationForm').on('submit', function(e) {
        e.preventDefault();
        
        const score = parseFloat($('#scoreInput').val());
        
        if (isNaN(score) || score < 0 || score > 40) {
            alert('⚠️ الدرجة يجب أن تكون بين 0 و 40');
            return;
        }
        
        if (!confirm('هل أنت متأكد من حفظ التقييم؟\nالدرجة: ' + score + ' / 40\n\n⚠️ لن تتمكن من التعديل بعد الحفظ')) {
            return;
        }

        $('#saveBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>جاري الحفظ...');

        $.ajax({
            url: '{{ route("judge.evaluate.save", $evaluation->id) }}',
            method: 'POST',
            data: {
                score: score,
                notes: $('#notesInput').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = '{{ route("judge.dashboard") }}';
                } else {
                    alert(' ' + response.message);
                    $('#saveBtn').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>حفظ التقييم');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'حدث خطأ أثناء حفظ التقييم';
                alert(' ' + message);
                $('#saveBtn').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>حفظ التقييم');
            }
        });
    });
});

// ======== PDF Functions ========
function loadPdf(url, button) {
    // Update active tab
    $('.pdf-tab').removeClass('active');
    $(button).addClass('active');
    
    // Show loader
    $('#pdfLoader').removeClass('d-none');
    
    // Load PDF
    $('#pdfViewer').attr('src', url + '#toolbar=1&navpanes=1&scrollbar=1');
    
    // Hide loader after load
    $('#pdfViewer').on('load', function() {
        $('#pdfLoader').addClass('d-none');
    });
}

// Handle collapse button rotation
$('[data-bs-toggle="collapse"]').on('click', function() {
    $(this).toggleClass('collapsed');
});
</script>
@endpush