@extends('layouts.app')

@section('title', 'الممتحنين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الممتحنين</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-users me-2"></i>
                    قائمة الممتحنين
                    <span class="badge bg-primary ms-2">{{ $examinees->total() }}</span>
                </h5>
                <div class="d-flex gap-2">
                    <!-- Print Selected Cards Button - Added Here -->
                    <button type="button" class="btn btn-warning" id="printSelectedCards" style="display: none;">
                        <i class="ti ti-id-badge me-1"></i>
                        طباعة البطاقات المحددة (<span id="selectedCount">0</span>)
                    </button>
                    
                    <button type="button" class="btn btn-outline-secondary" id="toggleFilters">
                        <i class="ti ti-adjustments me-1"></i>
                        <span id="filterToggleText">إظهار الفلتر</span>
                    </button>
                    <a href="{{ route('examinees.print') }}?{{ http_build_query(request()->except('page')) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="ti ti-printer me-1"></i>
                        طباعة
                    </a>
                    <a href="{{ route('examinees.import.form') }}" class="btn btn-success">
                        <i class="ti ti-file-import me-1"></i>
                        استيراد من Excel
                    </a>
                    <a href="{{ route('examinees.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        إضافة ممتحن جديد
                    </a>
                </div>
            </div>

            <!-- Advanced Filters Card -->
            <div class="collapse {{ request()->hasAny(['name', 'national_id', 'phone', 'whatsapp', 'gender', 'status', 'nationality', 'office_id', 'cluster_id', 'narration_id', 'drawing_id', 'current_residence', 'birth_date_from', 'birth_date_to']) ? 'show' : '' }}" id="filtersCollapse">
                <div class="card-body bg-light border-bottom">
                    <form method="GET" action="{{ route('examinees.index') }}" id="filterForm">
                        <div class="row g-3">
                            <!-- Name Search -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-search me-1"></i>
                                    الاسم الرباعي
                                </label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="ابحث في جميع الأسماء..." 
                                       value="{{ request('name') }}">
                                <small class="text-muted">الأول، الأب، الجد، أو اللقب</small>
                            </div>

                            <!-- National ID -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-id-badge me-1"></i>
                                    الرقم الوطني
                                </label>
                                <input type="text" name="national_id" class="form-control" 
                                       placeholder="الرقم الوطني" 
                                       value="{{ request('national_id') }}">
                            </div>

                            <!-- Phone -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-phone me-1"></i>
                                    رقم الهاتف
                                </label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="رقم الهاتف" 
                                       value="{{ request('phone') }}">
                            </div>

                            <!-- WhatsApp -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-brand-whatsapp me-1"></i>
                                    رقم الواتساب
                                </label>
                                <input type="text" name="whatsapp" class="form-control" 
                                       placeholder="رقم الواتساب" 
                                       value="{{ request('whatsapp') }}">
                            </div>

                            <!-- Passport Number -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-passport me-1"></i>
                                    رقم الجواز
                                </label>
                                <input type="text" name="passport_no" class="form-control" 
                                       placeholder="رقم الجواز" 
                                       value="{{ request('passport_no') }}">
                            </div>

                            <!-- Gender -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-gender-male me-1"></i>
                                    الجنس
                                </label>
                                <select name="gender" class="form-select">
                                    <option value="">الكل</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-circle-check me-1"></i>
                                    الحالة
                                </label>
                                <select name="status" class="form-select">
                                    <option value="">كل الحالات</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد التأكيد</option>
                                    <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>منسحب</option>
                                </select>
                            </div>

                            <!-- Nationality -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-flag me-1"></i>
                                    الجنسية
                                </label>
                                <input type="text" name="nationality" class="form-control" 
                                       placeholder="الجنسية" 
                                       value="{{ request('nationality') }}">
                            </div>

                            <!-- Office -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-building-store me-1"></i>
                                    المكتب
                                </label>
                                <select name="office_id" class="form-select">
                                    <option value="">كل المكاتب</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                            {{ $office->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cluster -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-users-group me-1"></i>
                                    التجمع
                                </label>
                                <select name="cluster_id" class="form-select">
                                    <option value="">كل التجمعات</option>
                                    @foreach($clusters as $cluster)
                                        <option value="{{ $cluster->id }}" {{ request('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                            {{ $cluster->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Narration -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-book me-1"></i>
                                    الرواية
                                </label>
                                <select name="narration_id" class="form-select">
                                    <option value="">كل الروايات</option>
                                    @foreach($narrations as $narration)
                                        <option value="{{ $narration->id }}" {{ request('narration_id') == $narration->id ? 'selected' : '' }}>
                                            {{ $narration->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Drawing -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-pencil me-1"></i>
                                    الرسم
                                </label>
                                <select name="drawing_id" class="form-select">
                                    <option value="">كل الرسوم</option>
                                    @foreach($drawings as $drawing)
                                        <option value="{{ $drawing->id }}" {{ request('drawing_id') == $drawing->id ? 'selected' : '' }}>
                                            {{ $drawing->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Current Residence -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="ti ti-map-pin me-1"></i>
                                    مكان الإقامة
                                </label>
                                <input type="text" name="current_residence" class="form-control" 
                                       placeholder="أدخل مكان الإقامة" 
                                       value="{{ request('current_residence') }}">
                            </div>

                            <!-- Birth Date From -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-calendar me-1"></i>
                                    تاريخ الميلاد من
                                </label>
                                <input type="date" name="birth_date_from" class="form-control" 
                                       value="{{ request('birth_date_from') }}">
                            </div>

                            <!-- Birth Date To -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="ti ti-calendar me-1"></i>
                                    تاريخ الميلاد إلى
                                </label>
                                <input type="date" name="birth_date_to" class="form-control" 
                                       value="{{ request('birth_date_to') }}">
                            </div>

                            <!-- Sorting Options -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="ti ti-sort-ascending me-1"></i>
                                    ترتيب حسب
                                </label>
                                <select name="sort_by" class="form-select">
                                    <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>تاريخ الإضافة</option>
                                    <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>الاسم الأول</option>
                                    <option value="last_name" {{ request('sort_by') == 'last_name' ? 'selected' : '' }}>اللقب</option>
                                    <option value="national_id" {{ request('sort_by') == 'national_id' ? 'selected' : '' }}>الرقم الوطني</option>
                                    <option value="birth_date" {{ request('sort_by') == 'birth_date' ? 'selected' : '' }}>تاريخ الميلاد</option>
                                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>الحالة</option>
                                </select>
                            </div>

                            <!-- Sort Direction -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="ti ti-arrows-sort me-1"></i>
                                    اتجاه الترتيب
                                </label>
                                <select name="sort_direction" class="form-select">
                                    <option value="desc" {{ request('sort_direction', 'desc') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                                </select>
                            </div>

                            <!-- Per Page -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="ti ti-list-numbers me-1"></i>
                                    عدد النتائج في الصفحة
                                </label>
                                <select name="per_page" class="form-select">
                                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>الكل</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('examinees.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x me-1"></i>
                                        إلغاء الفلتر
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-filter me-1"></i>
                                        تطبيق الفلتر
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['name', 'national_id', 'phone', 'whatsapp', 'passport_no', 'gender', 'status', 'nationality', 'office_id', 'cluster_id', 'narration_id', 'drawing_id', 'current_residence', 'birth_date_from', 'birth_date_to']))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="ti ti-filter me-2"></i>
                                                <strong>الفلاتر المفعلة:</strong>
                                                <div class="d-inline-flex flex-wrap gap-2 mt-2">
                                                    @if(request('name'))
                                                        <span class="badge bg-primary">الاسم: {{ request('name') }}</span>
                                                    @endif
                                                    @if(request('national_id'))
                                                        <span class="badge bg-primary">الرقم الوطني: {{ request('national_id') }}</span>
                                                    @endif
                                                    @if(request('phone'))
                                                        <span class="badge bg-primary">الهاتف: {{ request('phone') }}</span>
                                                    @endif
                                                    @if(request('whatsapp'))
                                                        <span class="badge bg-primary">واتساب: {{ request('whatsapp') }}</span>
                                                    @endif
                                                    @if(request('passport_no'))
                                                        <span class="badge bg-primary">الجواز: {{ request('passport_no') }}</span>
                                                    @endif
                                                    @if(request('gender'))
                                                        <span class="badge bg-primary">الجنس: {{ request('gender') == 'male' ? 'ذكر' : 'أنثى' }}</span>
                                                    @endif
                                                    @if(request('status'))
                                                        <span class="badge bg-primary">الحالة: 
                                                            @if(request('status') == 'confirmed') مؤكد
                                                            @elseif(request('status') == 'pending') قيد التأكيد
                                                            @else منسحب
                                                            @endif
                                                        </span>
                                                    @endif
                                                    @if(request('nationality'))
                                                        <span class="badge bg-primary">الجنسية: {{ request('nationality') }}</span>
                                                    @endif
                                                    @if(request('office_id'))
                                                        <span class="badge bg-primary">المكتب: {{ $offices->find(request('office_id'))->name ?? '' }}</span>
                                                    @endif
                                                    @if(request('cluster_id'))
                                                        <span class="badge bg-primary">التجمع: {{ $clusters->find(request('cluster_id'))->name ?? '' }}</span>
                                                    @endif
                                                    @if(request('narration_id'))
                                                        <span class="badge bg-primary">الرواية: {{ $narrations->find(request('narration_id'))->name ?? '' }}</span>
                                                    @endif
                                                    @if(request('drawing_id'))
                                                        <span class="badge bg-primary">الرسم: {{ $drawings->find(request('drawing_id'))->name ?? '' }}</span>
                                                    @endif
                                                    @if(request('current_residence'))
                                                        <span class="badge bg-primary">الإقامة: {{ request('current_residence') }}</span>
                                                    @endif
                                                    @if(request('birth_date_from') || request('birth_date_to'))
                                                        <span class="badge bg-primary">تاريخ الميلاد: {{ request('birth_date_from') }} - {{ request('birth_date_to') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Examinees Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th width="50">#</th>
                                <th>الاسم الرباعي</th>
                                <th>الرقم الوطني</th>
                                <th>الهاتف / واتساب</th>
                                <th>التجمع</th>
                                <th>المكتب</th>
                                <th>الرواية</th>
                                <th>الرسم</th>
                                <th>الحالة</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examinees as $examinee)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input examinee-checkbox" 
                                                   type="checkbox" 
                                                   value="{{ $examinee->id }}"
                                                   data-name="{{ $examinee->full_name }}"
                                                   data-national-id="{{ $examinee->national_id ?? $examinee->passport_no }}"
                                                   data-cluster="{{ $examinee->cluster->name ?? '-' }}"
                                                   data-narration="{{ $examinee->narration->name ?? '-' }}"
                                                   data-drawing="{{ $examinee->drawing->name ?? '-' }}">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-secondary text-secondary">
                                            {{ $examinee->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-user"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $examinee->full_name }}</h6>
                                                @if($examinee->nationality)
                                                    <small class="text-muted">
                                                        <i class="ti ti-flag"></i>
                                                        {{ $examinee->nationality }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($examinee->national_id)
                                            <i class="ti ti-id-badge me-1 text-muted"></i>
                                            <small>{{ $examinee->national_id }}</small>
                                        @elseif($examinee->passport_no)
                                            <i class="ti ti-passport me-1 text-muted"></i>
                                            <small>{{ $examinee->passport_no }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->phone)
                                            <div class="mb-1">
                                                <i class="ti ti-phone me-1 text-muted"></i>
                                                <small>{{ $examinee->phone }}</small>
                                            </div>
                                        @endif
                                        @if($examinee->whatsapp)
                                            <div>
                                                <i class="ti ti-brand-whatsapp me-1 text-success"></i>
                                                <small>{{ $examinee->whatsapp }}</small>
                                            </div>
                                        @endif
                                        @if(!$examinee->phone && !$examinee->whatsapp)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->cluster)
                                            <span class="badge bg-light-primary text-primary">
                                                <i class="ti ti-users me-1"></i>
                                                {{ $examinee->cluster->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->office)
                                            <span class="badge bg-light-info text-info">
                                                <i class="ti ti-building-store me-1"></i>
                                                {{ $examinee->office->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->narration)
                                            <span class="badge bg-light-secondary text-secondary">
                                                {{ $examinee->narration->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->drawing)
                                            <span class="badge bg-light-secondary text-secondary">
                                                {{ $examinee->drawing->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examinee->status == 'confirmed')
                                            <span class="badge bg-light-success text-success">
                                                <i class="ti ti-circle-check me-1"></i>
                                                مؤكد
                                            </span>
                                        @elseif($examinee->status == 'pending')
                                            <span class="badge bg-light-warning text-warning">
                                                <i class="ti ti-clock me-1"></i>
                                                قيد التأكيد
                                            </span>
                                        @else
                                            <span class="badge bg-light-danger text-danger">
                                                <i class="ti ti-circle-x me-1"></i>
                                                منسحب
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('examinees.show', $examinee) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           data-bs-toggle="tooltip" 
                                           title="عرض التفاصيل">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('examinees.edit', $examinee) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="تعديل">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal"
                                                data-examinee-id="{{ $examinee->id }}"
                                                data-examinee-name="{{ $examinee->full_name }}"
                                                title="حذف">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-users-off display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد بيانات</h5>
                                            <p class="text-muted">لم يتم العثور على ممتحنين</p>
                                            <a href="{{ route('examinees.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i>
                                                إضافة أول ممتحن
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
            @if(request('per_page') != 'all' && $examinees->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $examinees->firstItem() }} إلى {{ $examinees->lastItem() }} من أصل {{ $examinees->total() }} نتيجة
                        </div>
                        <div>
                            {{ $examinees->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @elseif(request('per_page') == 'all')
                <div class="card-footer">
                    <div class="text-muted text-center">
                        عرض جميع النتائج ({{ $examinees->count() }})
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="ti ti-alert-triangle me-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="ti ti-trash display-1 text-danger mb-3"></i>
                    <h6>هل أنت متأكد من حذف الممتحن؟</h6>
                    <p class="text-muted mb-0">
                        سيتم حذف الممتحن <strong id="deleteExamineeName"></strong> نهائياً ولا يمكن التراجع عن هذا الإجراء.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>
                        حذف نهائي
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Toggle Filters
    const toggleFiltersBtn = document.getElementById('toggleFilters');
    const filtersCollapse = document.getElementById('filtersCollapse');
    const filterToggleText = document.getElementById('filterToggleText');
    
    if (toggleFiltersBtn && filtersCollapse) {
        toggleFiltersBtn.addEventListener('click', function() {
            const bsCollapse = new bootstrap.Collapse(filtersCollapse, {
                toggle: true
            });
            
            setTimeout(() => {
                if (filtersCollapse.classList.contains('show')) {
                    filterToggleText.textContent = 'إخفاء الفلتر';
                    toggleFiltersBtn.classList.remove('btn-outline-secondary');
                    toggleFiltersBtn.classList.add('btn-secondary');
                } else {
                    filterToggleText.textContent = 'إظهار الفلتر';
                    toggleFiltersBtn.classList.remove('btn-secondary');
                    toggleFiltersBtn.classList.add('btn-outline-secondary');
                }
            }, 350);
        });
        
        if (filtersCollapse.classList.contains('show')) {
            filterToggleText.textContent = 'إخفاء الفلتر';
            toggleFiltersBtn.classList.remove('btn-outline-secondary');
            toggleFiltersBtn.classList.add('btn-secondary');
        }
    }

    // Delete modal
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const examineeId = button.getAttribute('data-examinee-id');
            const examineeName = button.getAttribute('data-examinee-name');
            
            document.getElementById('deleteExamineeName').textContent = examineeName;
            document.getElementById('deleteForm').action = '/examinees/' + examineeId;
        });
    }

    // ============ Checkbox Selection System ============
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.examinee-checkbox');
    const printSelectedBtn = document.getElementById('printSelectedCards');
    const selectedCountSpan = document.getElementById('selectedCount');

    // Select/Deselect All
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    // Individual checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateSelectedCount();
        });
    });

    function updateSelectAllState() {
        const totalCheckboxes = checkboxes.length;
        const checkedCheckboxes = document.querySelectorAll('.examinee-checkbox:checked').length;
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0;
            selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
        }
    }

    function updateSelectedCount() {
        const checkedCheckboxes = document.querySelectorAll('.examinee-checkbox:checked');
        const count = checkedCheckboxes.length;
        
        if (selectedCountSpan) {
            selectedCountSpan.textContent = count;
        }
        
        if (printSelectedBtn) {
            printSelectedBtn.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    // Print Selected Cards
    if (printSelectedBtn) {
        printSelectedBtn.addEventListener('click', function() {
            const checkedCheckboxes = document.querySelectorAll('.examinee-checkbox:checked');
            
            if (checkedCheckboxes.length === 0) {
                alert('الرجاء تحديد ممتحن واحد على الأقل');
                return;
            }

            // Collect data
            const examineesData = [];
            checkedCheckboxes.forEach(checkbox => {
                examineesData.push({
                    id: checkbox.value,
                    name: checkbox.dataset.name,
                    nationalId: checkbox.dataset.nationalId,
                    cluster: checkbox.dataset.cluster,
                    narration: checkbox.dataset.narration,
                    drawing: checkbox.dataset.drawing
                });
            });

            // Create print window
            printCards(examineesData);
        });
    }

    function printCards(examineesData) {
    // Collect IDs
    const ids = examineesData.map(e => e.id).join(',');
    
    // Open print page
    window.open('/examinees/print-cards?ids=' + ids, '_blank');
}

    // Initialize
    updateSelectedCount();
});
</script>
@endpush