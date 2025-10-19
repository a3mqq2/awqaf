@extends('layouts.app')

@section('title', 'المحكمين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المحكمين</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-gavel me-2"></i>
                    قائمة المحكمين
                </h5>
                @can('judges.create')
                <a href="{{ route('judges.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة محكم جديد
                </a>
                @endcan
            </div>

            <!-- Filter Bar -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('judges.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">البحث</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="البحث بالاسم أو البريد الإلكتروني..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">التجمع</label>
                        <select name="cluster_id" class="form-select">
                            <option value="">جميع التجمعات</option>
                            @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id }}" {{ request('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                    {{ $cluster->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">ترتيب حسب</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم أ-ي</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="ti ti-filter"></i>
                        </button>
                        <a href="{{ route('judges.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Judges Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="250">المحكم</th>
                                <th>البريد الإلكتروني</th>
                                <th>التجمعات</th>
                                <th>اللجان</th>
                                <th>الحالة</th>
                                <th>تاريخ الإضافة</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($judges as $judge)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($judge->name) }}&background=28a745&color=fff&size=40" 
                                                 alt="{{ $judge->name }}" 
                                                 class="rounded-circle me-2" 
                                                 width="40" 
                                                 height="40">
                                            <div>
                                                <h6 class="mb-0">{{ $judge->name }}</h6>
                                                <small class="text-muted">ID: {{ $judge->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <i class="ti ti-mail me-1 text-muted"></i>
                                        {{ $judge->email }}
                                    </td>
                                    
                                    <td>
                                        @if($judge->clusters->count() > 0)
                                            @foreach($judge->clusters->take(2) as $cluster)
                                                <span class="badge bg-light-primary text-primary me-1 mb-1">
                                                    <i class="ti ti-map-pin me-1"></i>{{ $cluster->name }}
                                                </span>
                                            @endforeach
                                            @if($judge->clusters->count() > 2)
                                                <span class="badge bg-light-secondary text-secondary">
                                                    +{{ $judge->clusters->count() - 2 }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-light-info text-info">
                                            <i class="ti ti-users-group me-1"></i>
                                            {{ $judge->committees->count() }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <span class="badge {{ $judge->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                            <i class="ti {{ $judge->is_active ? 'ti-circle-check' : 'ti-circle-x' }} me-1"></i>
                                            {{ $judge->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div>{{ $judge->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $judge->created_at->diffForHumans() }}</small>
                                    </td>
                                    
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('judges.show', $judge) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               title="عرض">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            
                                            @can('judges.edit')
                                            <a href="{{ route('judges.edit', $judge) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="تعديل">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('judges.edit')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-{{ $judge->is_active ? 'warning' : 'success' }}" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#toggleStatusModal"
                                                    data-judge-id="{{ $judge->id }}"
                                                    data-judge-name="{{ $judge->name }}"
                                                    data-current-status="{{ $judge->is_active ? 'active' : 'inactive' }}"
                                                    title="{{ $judge->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="ti {{ $judge->is_active ? 'ti-user-x' : 'ti-user-check' }}"></i>
                                            </button>
                                            @endcan
                                            
                                            @can('judges.delete')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal"
                                                    data-judge-id="{{ $judge->id }}"
                                                    data-judge-name="{{ $judge->name }}"
                                                    title="حذف">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-gavel-off display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">لا يوجد محكمين</h5>
                                            <p class="text-muted">لم يتم العثور على محكمين</p>
                                            @can('judges.create')
                                            <a href="{{ route('judges.create') }}" class="btn btn-primary mt-2">
                                                <i class="ti ti-plus me-1"></i>إضافة أول محكم
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($judges->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $judges->firstItem() }} إلى {{ $judges->lastItem() }} من أصل {{ $judges->total() }} نتيجة
                        </div>
                        <div>
                            {{ $judges->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
@can('judges.delete')
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
                <p class="text-muted mb-0">
                    سيتم حذف المحكم <strong id="deleteJudgeName"></strong> نهائياً
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إلغاء
                </button>
                <form id="deleteForm" method="POST">
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
@endcan

<!-- Toggle Status Modal -->
@can('judges.edit')
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="ti ti-user-check me-2"></i>تغيير حالة المحكم
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="ti ti-user-question display-1 text-warning mb-3"></i>
                <h6 id="toggleStatusTitle"></h6>
                <p class="text-muted mb-0" id="toggleStatusMessage"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إلغاء
                </button>
                <form id="toggleStatusForm" method="POST">
                    @csrf 
                    @method('PATCH')
                    <button type="submit" class="btn" id="toggleStatusBtn">
                        <i class="ti ti-check me-1"></i>تأكيد
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    // Delete modal
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const judgeId = button.getAttribute('data-judge-id');
            const judgeName = button.getAttribute('data-judge-name');
            
            document.getElementById('deleteJudgeName').textContent = judgeName;
            document.getElementById('deleteForm').action = `/judges/${judgeId}`;
        });
    }

    // Toggle status modal
    const toggleStatusModal = document.getElementById('toggleStatusModal');
    if (toggleStatusModal) {
        toggleStatusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const judgeId = button.getAttribute('data-judge-id');
            const judgeName = button.getAttribute('data-judge-name');
            const currentStatus = button.getAttribute('data-current-status');
            
            const isActive = currentStatus == 'active';
            const actionText = isActive ? 'إلغاء تفعيل' : 'تفعيل';
            const btnClass = isActive ? 'btn-warning' : 'btn-success';
            const icon = isActive ? 'ti-user-x' : 'ti-user-check';
            
            document.getElementById('toggleStatusTitle').textContent = `${actionText} المحكم ${judgeName}`;
            document.getElementById('toggleStatusMessage').textContent = `هل أنت متأكد من ${actionText} المحكم ${judgeName}؟`;

            const toggleBtn = document.getElementById('toggleStatusBtn');
            toggleBtn.className = `btn ${btnClass}`;
            toggleBtn.innerHTML = `<i class="ti ${icon} me-1"></i>${actionText}`;
            
            document.getElementById('toggleStatusForm').action = `/judges/${judgeId}/toggle-status`;
        });
    }
});
</script>
@endpush