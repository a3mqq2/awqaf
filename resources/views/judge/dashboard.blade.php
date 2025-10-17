@extends('layouts.app')

@section('title', 'لوحة المحكم')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">لوحة المحكم</li>
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
    .waiting-time {
        font-size: 0.85rem;
        color: #6c757d;
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
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="ti ti-users-group display-4 me-3"></i>
                    <div>
                        <h5 class="mb-1 text-white">اللجان المخصصة لك</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($committees as $committee)
                                <span class="badge bg-light text-primary">
                                    {{ $committee->name }} - {{ $committee->cluster->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-12 mb-3">
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>تنبيه:</strong> لم يتم تخصيص أي لجنة لك بعد. الرجاء التواصل مع المسؤول.
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
                        قائمة الانتظار
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                        <i class="ti ti-refresh me-1"></i>
                        تحديث
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($examinees->isEmpty())
                    <div class="text-center py-5">
                        <i class="ti ti-users-off display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">لا يوجد ممتحنين في قائمة الانتظار</h5>
                        <p class="text-muted">سيظهر هنا الممتحنين الذين سجلوا حضورهم</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($examinees as $examinee)
                            @php
                                $myEvaluation = $examinee->evaluationByJudge(Auth::id());
                                $waitingTime = $examinee->attended_at ? $examinee->attended_at->diffForHumans() : '';
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
                                                <br>
                                                <small class="waiting-time">
                                                    <i class="ti ti-clock me-1"></i>
                                                    {{ $waitingTime }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted">التجمع</small>
                                                    <div class="fw-bold">{{ $examinee->cluster->name ?? '-' }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">الرواية</small>
                                                    <div class="fw-bold">{{ $examinee->narration->name ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($myEvaluation)
                                            @if($myEvaluation->status === 'pending')
                                                <a href="{{ route('judge.evaluate', $myEvaluation->id) }}" 
                                                   class="btn btn-info w-100">
                                                    <i class="ti ti-pencil me-1"></i>
                                                    بدء الاختبار
                                                </a>
                                            @elseif($myEvaluation->status === 'in_progress')
                                                <a href="{{ route('judge.evaluate', $myEvaluation->id) }}" 
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
                                                استقبال الممتحن
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
    if (!confirm('هل تريد استقبال هذا الممتحن وبدء التقييم؟')) {
        return;
    }

    $.ajax({
        url: '{{ route("judge.receive") }}',
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
                alert('❌ ' + response.message);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'حدث خطأ أثناء استقبال الممتحن';
            alert('❌ ' + message);
        }
    });
}

// Auto refresh every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endpush