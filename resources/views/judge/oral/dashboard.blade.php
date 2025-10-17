@extends('layouts.app')

@section('title', 'لوحة الاختبار الشفهي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الاختبار الشفهي</li>
@endsection

@push('styles')
<style>
    .waiting-card {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    .waiting-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    .examinee-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #3c5e7f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: white;
    }
    .stat-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateX(-5px);
    }
</style>
@endpush

@section('content')
<div class="row mt-3">
    <!-- Statistics Cards -->
    <div class="col-md-4 mb-3">
        <div class="card stat-card" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">في قائمة الانتظار</h6>
                        <h2 class="mb-0">{{ $examinees->count() }}</h2>
                    </div>
                    <div class="bg-light-warning rounded-circle p-3">
                        <i class="ti ti-clock display-6 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card stat-card" style="border-left-color: #0dcaf0;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">جاري التقييم</h6>
                        <h2 class="mb-0">{{ $statistics['in_progress'] }}</h2>
                    </div>
                    <div class="bg-light-info rounded-circle p-3">
                        <i class="ti ti-hourglass display-6 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card stat-card" style="border-left-color: #198754;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">مكتمل</h6>
                        <h2 class="mb-0">{{ $statistics['completed'] }}</h2>
                    </div>
                    <div class="bg-light-success rounded-circle p-3">
                        <i class="ti ti-circle-check display-6 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Committee Info -->
    @if($committees->isNotEmpty())
    <div class="col-md-12 mb-3">
        <div class="card" style="background: #3c5e7f; color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="ti ti-microphone display-4 me-3"></i>
                    <div>
                        <h5 class="mb-1">الاختبار الشفهي</h5>
                        <p class="mb-0">الممتحنون الذين اجتازوا المنهج العلمي وجاهزون للاختبار الشفهي</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Waiting Queue -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-users me-2"></i>
                        قائمة الانتظار للاختبار الشفهي
                        <span class="badge bg-warning ms-2">{{ $examinees->count() }}</span>
                    </h5>
                    <div>
                        <a href="{{ route('judge.dashboard') }}" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="ti ti-arrow-left me-1"></i>
                            المنهج العلمي
                        </a>
                        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                            <i class="ti ti-refresh me-1"></i>
                            تحديث
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($examinees->isEmpty())
                    <div class="text-center py-5">
                        <i class="ti ti-users-off display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">لا يوجد ممتحنين في قائمة الانتظار</h5>
                        <p class="text-muted">سيظهر هنا الممتحنين الذين اجتازوا المنهج العلمي ولم تقيمهم شفهياً بعد</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($examinees as $examinee)
                            @php
                                $myEvaluation = $myEvaluations->get($examinee->id);
                                $writtenScore = $examinee->average_written_score;
                            @endphp
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card waiting-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="examinee-avatar">
                                                {{ mb_substr($examinee->full_name, 0, 1) }}
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">{{ $examinee->full_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="ti ti-id-badge me-1"></i>
                                                    {{ $examinee->national_id ?? $examinee->passport_no }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="alert alert-success py-2">
                                                <i class="ti ti-circle-check me-1"></i>
                                                <strong>اجتاز المنهج العلمي:</strong> {{ number_format($writtenScore, 1) }} / 40
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted">التجمع</small>
                                                    <div class="fw-bold small">{{ $examinee->cluster->name ?? '-' }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">الرواية</small>
                                                    <div class="fw-bold small">{{ $examinee->narration->name ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($myEvaluation)
                                            @if($myEvaluation->status == 'pending')
                                                <a href="{{ route('judge.oral.evaluate', $myEvaluation->id) }}" 
                                                   class="btn btn-info w-100">
                                                    <i class="ti ti-microphone me-1"></i>
                                                    بدء الاختبار الشفهي
                                                </a>
                                            @elseif($myEvaluation->status == 'in_progress')
                                                <a href="{{ route('judge.oral.evaluate', $myEvaluation->id) }}" 
                                                   class="btn btn-warning w-100">
                                                    <i class="ti ti-reload me-1"></i>
                                                    متابعة الاختبار
                                                </a>
                                            @else
                                                <button class="btn btn-success w-100" disabled>
                                                    <i class="ti ti-circle-check me-1"></i>
                                                    تم التقييم
                                                </button>
                                            @endif
                                        @else
                                            <button class="btn btn-primary w-100" 
                                                    onclick="receiveExaminee({{ $examinee->id }}, {{ $committees->first()->id }})">
                                                <i class="ti ti-user-check me-1"></i>
                                                استقبال للاختبار الشفهي
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function receiveExaminee(examineeId, committeeId) {
    if (!confirm('هل تريد استقبال هذا الممتحن للاختبار الشفهي؟')) {
        return;
    }

    $.ajax({
        url: '{{ route("judge.oral.receive") }}',
        method: 'POST',
        data: {
            examinee_id: examineeId,
            committee_id: committeeId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('✅ ' + response.message);
                location.reload();
            } else {
                alert(' ' + response.message);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'حدث خطأ أثناء استقبال الممتحن';
            alert(' ' + message);
        }
    });
}
</script>
@endpush