@extends('layouts.app')

@section('title', 'التقييمات المكتملة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judge.dashboard') }}">لوحة المحكم</a></li>
    <li class="breadcrumb-item active">التقييمات المكتملة</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-circle-check me-2"></i>
                    التقييمات المكتملة
                    <span class="badge bg-success ms-2">{{ $evaluations->total() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>الممتحن</th>
                                <th>الرقم الوطني</th>
                                <th>اللجنة</th>
                                <th>الرواية</th>
                                <th>الدرجة</th>
                                <th>وقت الاختبار</th>
                                <th>تاريخ التقييم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $evaluation)
                                <tr>
                                    <td>
                                        <strong>{{ $evaluation->examinee->full_name }}</strong>
                                    </td>
                                    <td>
                                        <i class="ti ti-id-badge me-1 text-muted"></i>
                                        {{ $evaluation->examinee->national_id ?? $evaluation->examinee->passport_no }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light-primary text-primary">
                                            {{ $evaluation->committee->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-info text-info">
                                            {{ $evaluation->examinee->narration->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <h5 class="mb-0">
                                            <span class="badge bg-success">{{ $evaluation->score }}</span>
                                        </h5>
                                    </td>
                                    <td>
                                        @if($evaluation->started_at && $evaluation->completed_at)
                                            @php
                                                $duration = $evaluation->started_at->diffInMinutes($evaluation->completed_at);
                                            @endphp
                                            <i class="ti ti-clock me-1"></i>
                                            {{ $duration }} دقيقة
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $evaluation->completed_at->format('Y-m-d H:i') }}
                                        <br>
                                        <small class="text-muted">{{ $evaluation->completed_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="ti ti-clipboard-off display-4 text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد تقييمات مكتملة</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($evaluations->hasPages())
                <div class="card-footer">
                    {{ $evaluations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection