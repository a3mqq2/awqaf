@extends('layouts.app')

@section('title', 'تفاصيل المحكم')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judges.index') }}">المحكمين</a></li>
    <li class="breadcrumb-item active">{{ $judge->name }}</li>
@endsection

@section('content')
<div class="row mt-3">
    <!-- معلومات المحكم الأساسية -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="ti ti-user-circle me-2"></i>
                    معلومات المحكم
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($judge->name) }}&size=120&background=28a745&color=fff" 
                         alt="{{ $judge->name }}" 
                         class="rounded-circle"
                         style="width: 120px; height: 120px;">
                </div>
                <h5 class="mb-1">{{ $judge->name }}</h5>
                <p class="text-muted mb-2">{{ $judge->email }}</p>
                <span class="badge {{ $judge->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $judge->is_active ? 'نشط' : 'غير نشط' }}
                </span>
                
                <hr>
                
                <div class="mb-3 text-start">
                    <label class="text-muted small">تاريخ الإنشاء</label>
                    <div>
                        <i class="ti ti-calendar me-1"></i>
                        {{ $judge->created_at->format('Y-m-d') }}
                        <br>
                        <small class="text-muted">{{ $judge->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <div class="mb-3 text-start">
                    <label class="text-muted small">آخر تحديث</label>
                    <div>
                        <i class="ti ti-clock me-1"></i>
                        {{ $judge->updated_at->format('Y-m-d') }}
                        <br>
                        <small class="text-muted">{{ $judge->updated_at->diffForHumans() }}</small>
                    </div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <a href="{{ route('judges.edit', $judge) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>تعديل المحكم
                    </a>
                    <a href="{{ route('judges.index') }}" class="btn btn-secondary">
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
                        <i class="ti ti-map-pin text-primary me-2"></i>
                        <span>التجمعات</span>
                    </div>
                    <span class="badge bg-primary">{{ $judge->clusters->count() }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ti ti-users-group text-info me-2"></i>
                        <span>اللجان</span>
                    </div>
                    <span class="badge bg-info">{{ $judge->committees->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- التجمعات -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="ti ti-map-pin me-2"></i>
                    التجمعات المخصصة ({{ $judge->clusters->count() }})
                </h6>
                <a href="{{ route('judges.edit', $judge) }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-edit me-1"></i>تعديل
                </a>
            </div>
            <div class="card-body">
                @if($judge->clusters->count() > 0)
                    <div class="row">
                        @foreach($judge->clusters as $cluster)
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                    <i class="ti ti-map-pin text-primary me-2 fs-4"></i>
                                    <span>{{ $cluster->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ti ti-map-off display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">لا توجد تجمعات مخصصة لهذا المحكم</p>
                        <a href="{{ route('judges.edit', $judge) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="ti ti-plus me-1"></i>إضافة تجمعات
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- اللجان -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="ti ti-users-group me-2"></i>
                    اللجان المخصصة ({{ $judge->committees->count() }})
                </h6>
            </div>
            <div class="card-body">
                @if($judge->committees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم اللجنة</th>
                                    <th>التجمع</th>
                                    <th>الروايات</th>
                                    <th>الممتحنين</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($judge->committees as $committee)
                                    <tr>
                                        <td>
                                            <strong>{{ $committee->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary">
                                                <i class="ti ti-map-pin me-1"></i>
                                                {{ $committee->cluster->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info text-info">
                                                {{ $committee->narrations->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-success text-success">
                                                {{ $committee->examinees->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('committees.show', $committee) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ti ti-users-group-off display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">لم يتم تخصيص المحكم لأي لجنة بعد</p>
                        <small class="text-muted">يمكنك إضافة المحكم إلى اللجان من صفحة تعديل اللجنة</small>
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
                        <a href="{{ route('judges.edit', $judge) }}" class="btn btn-primary me-2">
                            <i class="ti ti-edit me-1"></i>تعديل المحكم
                        </a>
                        <form action="{{ route('judges.toggle-status', $judge) }}" method="POST" class="d-inline me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $judge->is_active ? 'warning' : 'success' }}">
                                <i class="ti ti-{{ $judge->is_active ? 'user-x' : 'user-check' }} me-1"></i>
                                {{ $judge->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                            </button>
                        </form>
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="ti ti-trash me-1"></i>حذف المحكم
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
                <h6>هل أنت متأكد من حذف المحكم؟</h6>
                <p class="text-muted mb-2">
                    سيتم حذف المحكم <strong>{{ $judge->name }}</strong> نهائياً
                </p>
                @if($judge->committees->count() > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="ti ti-alert-triangle me-1"></i>
                        <strong>تحذير:</strong> المحكم مخصص لـ {{ $judge->committees->count() }} لجنة
                    </div>
                @endif
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إلغاء
                </button>
                <form action="{{ route('judges.destroy', $judge) }}" method="POST">
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