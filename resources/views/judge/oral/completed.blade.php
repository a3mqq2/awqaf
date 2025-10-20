@extends('layouts.app')

@section('title', 'التقييمات الشفهية المكتملة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judge.dashboard') }}">لوحة المحكم</a></li>
    <li class="breadcrumb-item active">التقييمات الشفهية المكتملة</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success text-white p-3 me-3">
                                <i class="ti ti-circle-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">إجمالي التقييمات</h6>
                                <h3 class="mb-0">{{ $evaluations->total() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white p-3 me-3">
                                <i class="ti ti-star fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">متوسط الدرجات</h6>
                                <h3 class="mb-0">{{ number_format($evaluations->avg('final_score'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning text-white p-3 me-3">
                                <i class="ti ti-trophy fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">أعلى درجة</h6>
                                <h3 class="mb-0">{{ number_format($evaluations->max('final_score'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-info text-white p-3 me-3">
                                <i class="ti ti-clock fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">اليوم</h6>
                                <h3 class="mb-0">{{ $evaluations->where('completed_at', '>=', today())->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluations Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-microphone me-2"></i>
                    التقييمات الشفهية المكتملة
                    <span class="badge bg-success ms-2">{{ $evaluations->total() }}</span>
                </h5>
                <a href="{{ route('judge.dashboard') }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i>العودة للوحة التحكم
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>الممتحن</th>
                                <th>الرواية</th>
                                <th>اللجنة</th>
                                <th>الدرجة النهائية</th>
                                <th>الحالة</th>
                                <th>مدة الاختبار</th>
                                <th>تاريخ الإكمال</th>
                                <th width="100">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $evaluation)
                                <tr>
                                    <td>
                                        <span class="badge bg-light-secondary text-secondary">
                                            {{ $evaluations->firstItem() + $loop->index }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($evaluation->examinee->full_name) }}&background=random&color=fff&size=40" 
                                                 alt="{{ $evaluation->examinee->full_name }}" 
                                                 class="rounded-circle me-2" 
                                                 width="40" 
                                                 height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $evaluation->examinee->full_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="ti ti-id me-1"></i>
                                                    {{ $evaluation->examinee->national_id ?? $evaluation->examinee->passport_no }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        @if($evaluation->examinee->narration)
                                            <span class="badge bg-light-info text-info">
                                                <i class="ti ti-book me-1"></i>
                                                {{ $evaluation->examinee->narration->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-light-primary text-primary">
                                            <i class="ti ti-users-group me-1"></i>
                                            {{ $evaluation->committee->name }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $percentage = ($evaluation->final_score / 100) * 100;
                                                $badgeClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 60 ? 'bg-warning' : 'bg-danger');
                                            @endphp
                                            <span class="badge {{ $badgeClass }} fs-6 me-2">
                                                {{ number_format($evaluation->final_score, 2) }}/100
                                            </span>
                                            <div class="progress flex-fill" style="height: 8px; max-width: 80px;">
                                                <div class="progress-bar {{ $badgeClass }}" 
                                                     style="width: {{ $percentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        @if($evaluation->status === 'completed')
                                            <span class="badge bg-light-success text-success">
                                                <i class="ti ti-circle-check me-1"></i>مكتمل
                                            </span>
                                        @elseif($evaluation->status === 'excluded')
                                            <span class="badge bg-light-danger text-danger">
                                                <i class="ti ti-x me-1"></i>مستبعد
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if($evaluation->started_at && $evaluation->completed_at)
                                            @php
                                                $duration = $evaluation->started_at->diffInMinutes($evaluation->completed_at);
                                                $hours = floor($duration / 60);
                                                $minutes = $duration % 60;
                                            @endphp
                                            <i class="ti ti-clock me-1 text-muted"></i>
                                            @if($hours > 0)
                                                {{ $hours }} ساعة و {{ $minutes }} دقيقة
                                            @else
                                                {{ $minutes }} دقيقة
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div>
                                            <i class="ti ti-calendar me-1 text-muted"></i>
                                            {{ $evaluation->completed_at->format('Y-m-d') }}
                                        </div>
                                        <small class="text-muted">
                                            <i class="ti ti-clock me-1"></i>
                                            {{ $evaluation->completed_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info view-details" 
                                                data-evaluation-id="{{ $evaluation->id }}"
                                                title="عرض التفاصيل">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-microphone-off display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد تقييمات شفهية مكتملة</h5>
                                            <p class="text-muted">لم تقم بإكمال أي تقييمات شفهية بعد</p>
                                            <a href="{{ route('judge.dashboard') }}" class="btn btn-primary mt-2">
                                                <i class="ti ti-arrow-right me-1"></i>ابدأ التقييم
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($evaluations->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $evaluations->firstItem() }} إلى {{ $evaluations->lastItem() }} من أصل {{ $evaluations->total() }} نتيجة
                        </div>
                        <div>
                            {{ $evaluations->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="ti ti-file-info me-2"></i>تفاصيل التقييم الشفهي
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إغلاق
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View details buttons
    const viewButtons = document.querySelectorAll('.view-details');
    const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const evaluationId = this.getAttribute('data-evaluation-id');
            loadEvaluationDetails(evaluationId);
            modal.show();
        });
    });
    
    function loadEvaluationDetails(evaluationId) {
        const modalContent = document.getElementById('modalContent');
        
        // Show loading spinner
        modalContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
            </div>
        `;
        
        // Fetch evaluation details
        fetch(`/judge/oral-evaluations/${evaluationId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderEvaluationDetails(data.evaluation);
                } else {
                    showError('حدث خطأ أثناء تحميل البيانات');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('حدث خطأ أثناء تحميل البيانات');
            });
    }
    
    function renderEvaluationDetails(evaluation) {
        const modalContent = document.getElementById('modalContent');
        
        let questionsHtml = '';
        if (evaluation.questions_data) {
            Object.values(evaluation.questions_data).forEach(question => {
                const percentage = (question.final_score / 8.3333) * 100;
                const badgeClass = percentage >= 80 ? 'success' : (percentage >= 60 ? 'warning' : 'danger');
                
                questionsHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">السؤال ${question.question_number}</h6>
                                    <span class="badge bg-${badgeClass}">${question.final_score.toFixed(2)}/8.33</span>
                                </div>
                                <div class="progress mb-2" style="height: 6px;">
                                    <div class="progress-bar bg-${badgeClass}" style="width: ${percentage}%"></div>
                                </div>
                                <small class="text-muted">
                                    ${question.is_approved ? '<i class="ti ti-circle-check text-success"></i> معتمد' : '<i class="ti ti-clock text-warning"></i> قيد التقييم'}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        const finalPercentage = (evaluation.final_score / 100) * 100;
        const finalBadgeClass = finalPercentage >= 80 ? 'success' : (finalPercentage >= 60 ? 'warning' : 'danger');
        
        modalContent.innerHTML = `
            <div class="row g-4">
                <!-- Examinee Info -->
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="text-muted small">اسم الممتحن</label>
                                    <h6>${evaluation.examinee.full_name}</h6>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small">رقم الهوية</label>
                                    <h6>${evaluation.examinee.national_id || evaluation.examinee.passport_no}</h6>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small">الرواية</label>
                                    <h6>${evaluation.examinee.narration ? evaluation.examinee.narration.name : '-'}</h6>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small">اللجنة</label>
                                    <h6>${evaluation.committee.name}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Scores Summary -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="ti ti-star me-2"></i>ملخص الدرجات
                            </h5>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h2 class="text-primary mb-0">${evaluation.total_score.toFixed(2)}</h2>
                                        <p class="text-muted mb-0">المجموع الكلي</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h2 class="text-${finalBadgeClass} mb-0">${evaluation.final_score.toFixed(2)}</h2>
                                        <p class="text-muted mb-0">الدرجة النهائية</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h2 class="text-info mb-0">${finalPercentage.toFixed(0)}%</h2>
                                    <p class="text-muted mb-0">النسبة المئوية</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Questions Details -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="ti ti-list-numbers me-2"></i>تفاصيل الأسئلة
                            </h5>
                            <div class="row">
                                ${questionsHtml}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                ${evaluation.notes ? `
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="ti ti-note me-2"></i>الملاحظات
                            </h5>
                            <p class="mb-0">${evaluation.notes}</p>
                        </div>
                    </div>
                </div>
                ` : ''}
                
                <!-- Timing Info -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-muted small">تاريخ البدء</label>
                                    <p class="mb-0">${new Date(evaluation.started_at).toLocaleString('ar-EG')}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small">تاريخ الإكمال</label>
                                    <p class="mb-0">${new Date(evaluation.completed_at).toLocaleString('ar-EG')}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small">الحالة</label>
                                    <p class="mb-0">
                                        <span class="badge bg-${evaluation.status === 'completed' ? 'success' : 'danger'}">
                                            ${evaluation.status === 'completed' ? 'مكتمل' : 'مستبعد'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    function showError(message) {
        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="text-center py-5">
                <i class="ti ti-alert-circle display-1 text-danger mb-3"></i>
                <h5 class="text-danger">${message}</h5>
            </div>
        `;
    }
});
</script>
@endpush