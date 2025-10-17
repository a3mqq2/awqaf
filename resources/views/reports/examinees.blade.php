@extends('layouts.app')

@section('title', 'تقرير الممتحنين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">تقرير الممتحنين</li>
@endsection

@push('styles')
<style>
    .pass-badge {
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 6px;
    }
    .score-cell {
        font-weight: 600;
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.examinees') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="">الكل</option>
                                <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>حضر</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">النتيجة</label>
                            <select name="result" class="form-select">
                                <option value="">الكل</option>
                                <option value="passed" {{ request('result') == 'passed' ? 'selected' : '' }}>ناجح</option>
                                <option value="failed" {{ request('result') == 'failed' ? 'selected' : '' }}>راسب</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter me-1"></i>
                                تصفية
                            </button>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('reports.examinees') }}" class="btn btn-secondary w-100">
                                <i class="ti ti-refresh me-1"></i>
                                إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="ti ti-report me-2"></i>
                    تقرير درجات الممتحنين
                    <span class="badge bg-light text-primary ms-2">{{ $examinees->total() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($examinees->isEmpty())
                    <div class="text-center py-5">
                        <i class="ti ti-file-off display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد بيانات</h5>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>الممتحن</th>
                                    <th>التجمع</th>
                                    <th>الرواية</th>
                                    <th class="text-center">المنهج العلمي<br><small class="text-muted">(من 40)</small></th>
                                    <th class="text-center">الشفهي<br><small class="text-muted">(من 100)</small></th>
                                    <th class="text-center">المجموع<br><small class="text-muted">(من 140)</small></th>
                                    <th class="text-center">النسبة %</th>
                                    <th class="text-center">النتيجة</th>
                                    <th class="text-center">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examinees as $index => $examinee)
                                    <tr>
                                        <td class="text-center">{{ $examinees->firstItem() + $index }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $examinee->full_name }}</strong>
                                            </div>
                                            <small class="text-muted">
                                                <i class="ti ti-id-badge me-1"></i>
                                                {{ $examinee->national_id ?? $examinee->passport_no }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $examinee->cluster->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $examinee->narration->name ?? '-' }}</td>
                                        <td class="text-center score-cell">
                                            @if($examinee->avg_written > 0)
                                                <span class="text-primary">{{ number_format($examinee->avg_written, 1) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center score-cell">
                                            @if($examinee->avg_oral > 0)
                                                <span class="text-success">{{ number_format($examinee->avg_oral, 1) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center score-cell">
                                            @if($examinee->total_score > 0)
                                                <strong class="text-dark">{{ number_format($examinee->total_score, 1) }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($examinee->total_score > 0)
                                                <span class="badge {{ $examinee->percentage >= 50 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ number_format($examinee->percentage, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($examinee->total_score > 0)
                                                @if($examinee->is_passed)
                                                    <span class="badge bg-success pass-badge">
                                                        <i class="ti ti-circle-check me-1"></i>
                                                        ناجح
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger pass-badge">
                                                        <i class="ti ti-circle-x me-1"></i>
                                                        راسب
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary pass-badge">
                                                    <i class="ti ti-clock me-1"></i>
                                                    قيد التقييم
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('reports.receipt', $examinee) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               target="_blank"
                                               data-bs-toggle="tooltip"
                                               title="طباعة إيصال">
                                                <i class="ti ti-printer"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {{ $examinees->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        @if($examinees->isNotEmpty())
        <div class="row g-3 mt-3">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $examinees->where('is_passed', true)->count() }}</h3>
                        <small>ناجح</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $examinees->where('is_passed', false)->where('total_score', '>', 0)->count() }}</h3>
                        <small>راسب</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $examinees->where('total_score', 0)->count() }}</h3>
                        <small>قيد التقييم</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ number_format($examinees->where('total_score', '>', 0)->avg('percentage'), 1) }}%</h3>
                        <small>متوسط النسبة</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection