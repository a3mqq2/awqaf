@extends('layouts.app')

@section('title', 'تفاصيل اللجنة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('committees.index') }}">اللجان</a></li>
    <li class="breadcrumb-item active">{{ $committee->name }}</li>
@endsection

@section('content')
<div class="row mt-3">
    <!-- معلومات اللجنة الأساسية -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    معلومات اللجنة
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">اسم اللجنة</label>
                    <h6 class="mb-0">{{ $committee->name }}</h6>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">التجمع</label>
                    <div>
                        <span class="badge bg-light-primary text-primary">
                            <i class="ti ti-map-pin me-1"></i>
                            {{ $committee->cluster->name }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">تاريخ الإنشاء</label>
                    <div>
                        <i class="ti ti-calendar me-1"></i>
                        {{ $committee->created_at->format('Y-m-d') }}
                        <br>
                        <small class="text-muted">{{ $committee->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">آخر تحديث</label>
                    <div>
                        <i class="ti ti-clock me-1"></i>
                        {{ $committee->updated_at->format('Y-m-d') }}
                        <br>
                        <small class="text-muted">{{ $committee->updated_at->diffForHumans() }}</small>
                    </div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <a href="{{ route('committees.edit', $committee) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>تعديل اللجنة
                    </a>
                    <a href="{{ route('committees.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="ti ti-chart-bar me-2"></i>
                    الإحصائيات
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="ti ti-book text-info me-2"></i>
                        <span>الروايات</span>
                    </div>
                    <span class="badge bg-info">{{ $committee->narrations->count() }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="ti ti-users text-success me-2"></i>
                        <span>المحكمين</span>
                    </div>
                    <span class="badge bg-success">{{ $committee->users->count() }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ti ti-user-check text-primary me-2"></i>
                        <span>الممتحنين</span>
                    </div>
                    <span class="badge bg-primary">{{ $committee->examinees->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- الروايات -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="ti ti-book me-2"></i>
                    الروايات المخصصة ({{ $committee->narrations->count() }})
                </h6>
                <a href="{{ route('committees.edit', $committee) }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-edit me-1"></i>تعديل
                </a>
            </div>
            <div class="card-body">
                @if($committee->narrations->count() > 0)
                    <div class="row">
                        @foreach($committee->narrations as $narration)
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center p-2 border rounded">
                                    <i class="ti ti-book-2 text-info me-2 fs-5"></i>
                                    <span>{{ $narration->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ti ti-book-off display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">لا توجد روايات مخصصة لهذه اللجنة</p>
                        <a href="{{ route('committees.edit', $committee) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="ti ti-plus me-1"></i>إضافة روايات
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- المحكمين -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="ti ti-users me-2"></i>
                    المحكمين ({{ $committee->users->count() }})
                </h6>
                <a href="{{ route('committees.edit', $committee) }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-edit me-1"></i>تعديل
                </a>
            </div>
            <div class="card-body">
                @if($committee->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>المحكم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($committee->users as $judge)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($judge->name) }}&background=3c5e7f&color=fff&size=32" 
                                                     alt="{{ $judge->name }}" 
                                                     class="rounded-circle me-2" 
                                                     width="32" 
                                                     height="32">
                                                <span>{{ $judge->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="ti ti-mail me-1 text-muted"></i>
                                            {{ $judge->email }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $judge->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                                <i class="ti {{ $judge->is_active ? 'ti-circle-check' : 'ti-circle-x' }} me-1"></i>
                                                {{ $judge->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ti ti-users-off display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">لا يوجد محكمين مضافين لهذه اللجنة</p>
                        <a href="{{ route('committees.edit', $committee) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="ti ti-plus me-1"></i>إضافة محكمين
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- الممتحنين -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="ti ti-user-check me-2"></i>
                    الممتحنين المخصصين ({{ $committee->examinees->count() }})
                </h6>
                <a href="{{ route('examinees.index', ['committee_id' => $committee->id]) }}" 
                   class="btn btn-sm btn-primary">
                    <i class="ti ti-eye me-1"></i>عرض الجميع
                </a>
            </div>
            <div class="card-body">
                @if($committee->examinees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الاسم</th>
                                    <th>الرقم الوطني</th>
                                    <th>الحالة</th>
                                    <th>الحضور</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($committee->examinees->take(5) as $examinee)
                                    <tr>
                                        <td>
                                            <a href="{{ route('examinees.show', $examinee) }}" class="text-decoration-none">
                                                {{ $examinee->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <i class="ti ti-id-badge me-1 text-muted"></i>
                                            {{ $examinee->national_id ?? $examinee->passport_no }}
                                        </td>
                                        <td>
                                            @if($examinee->status == 'confirmed')
                                                <span class="badge bg-light-success text-success">
                                                    <i class="ti ti-circle-check me-1"></i>مؤكد
                                                </span>
                                            @elseif($examinee->status == 'pending')
                                                <span class="badge bg-light-warning text-warning">
                                                    <i class="ti ti-clock me-1"></i>قيد التأكيد
                                                </span>
                                            @else
                                                <span class="badge bg-light-danger text-danger">
                                                    <i class="ti ti-circle-x me-1"></i>{{ $examinee->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($examinee->is_attended)
                                                <span class="badge bg-light-success text-success">
                                                    <i class="ti ti-check me-1"></i>حضر
                                                </span>
                                            @else
                                                <span class="badge bg-light-secondary text-secondary">
                                                    <i class="ti ti-minus me-1"></i>لم يحضر
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($committee->examinees->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('examinees.index', ['committee_id' => $committee->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                عرض جميع الممتحنين ({{ $committee->examinees->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="ti ti-user-off display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">لا يوجد ممتحنين مخصصين لهذه اللجنة بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- أزرار العمليات السريعة -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ti ti-bolt me-2"></i>
                        عمليات سريعة
                    </h6>
                    <div>
                        <a href="{{ route('committees.edit', $committee) }}" class="btn btn-primary me-2">
                            <i class="ti ti-edit me-1"></i>تعديل اللجنة
                        </a>
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="ti ti-trash me-1"></i>حذف اللجنة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="ti ti-alert-triangle me-2"></i>تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="ti ti-trash display-1 text-danger mb-3"></i>
                <h6>هل أنت متأكد من حذف اللجنة؟</h6>
                <p class="text-muted mb-2">
                    سيتم حذف اللجنة <strong>{{ $committee->name }}</strong> نهائياً
                </p>
                @if($committee->examinees->count() > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="ti ti-alert-triangle me-1"></i>
                        <strong>تحذير:</strong> هناك {{ $committee->examinees->count() }} ممتحن مخصص لهذه اللجنة
                    </div>
                @endif
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إلغاء
                </button>
                <form action="{{ route('committees.destroy', $committee) }}" method="POST">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>حذف نهائي
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection