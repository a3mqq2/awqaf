@extends('layouts.app')

@section('title', 'اللجان')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">اللجان</li>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-users-group me-2"></i>
                    قائمة اللجان
                </h5>
                <a href="{{ route('committees.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة لجنة جديدة
                </a>
            </div>

            <!-- Filter Bar -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('committees.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">البحث</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="ابحث باسم اللجنة..." 
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
                        <label class="form-label">الرواية</label>
                        <select name="narration_id" class="form-select">
                            <option value="">جميع الروايات</option>
                            @foreach($narrations as $narration)
                                <option value="{{ $narration->id }}" {{ request('narration_id') == $narration->id ? 'selected' : '' }}>
                                    {{ $narration->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">ترتيب حسب</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم أ-ي</option>
                            <option value="examinees_desc" {{ request('sort') == 'examinees_desc' ? 'selected' : '' }}>الأكثر ممتحنين</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">عدد الصفوف</label>
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="ti ti-filter"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Committees Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم اللجنة</th>
                                <th>التجمع</th>
                                <th>الروايات</th>
                                <th>المحكمين</th>
                                <th>الممتحنين</th>
                                <th>تاريخ الإنشاء</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($committees as $committee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    
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
                                        @if($committee->narrations->count() > 0)
                                            @foreach($committee->narrations->take(2) as $narration)
                                                <span class="badge bg-light-info text-info me-1 mb-1">
                                                    {{ $narration->name }}
                                                </span>
                                            @endforeach
                                            @if($committee->narrations->count() > 2)
                                                <span class="badge bg-light-secondary text-secondary">
                                                    +{{ $committee->narrations->count() - 2 }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-light-success text-success">
                                            <i class="ti ti-users me-1"></i>
                                            {{ $committee->users->count() }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <a href="{{ route('examinees.index', ['committee_id' => $committee->id]) }}" 
                                           class="badge bg-primary text-white">
                                            <i class="ti ti-user-check me-1"></i>
                                            {{ $committee->examinees->count() }}
                                        </a>
                                    </td>
                                    
                                    <td>
                                        <div>{{ $committee->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $committee->created_at->diffForHumans() }}</small>
                                    </td>
                                    
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('committees.show', $committee) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               title="عرض">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('committees.edit', $committee) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="تعديل">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal"
                                                    data-committee-id="{{ $committee->id }}"
                                                    data-committee-name="{{ $committee->name }}"
                                                    title="حذف">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ti ti-users-group-off display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد لجان</h5>
                                            <p class="text-muted">لم يتم العثور على لجان</p>
                                            <a href="{{ route('committees.create') }}" class="btn btn-primary mt-2">
                                                <i class="ti ti-plus me-1"></i>إضافة أول لجنة
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
            @if($committees->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            عرض {{ $committees->firstItem() }} إلى {{ $committees->lastItem() }} من أصل {{ $committees->total() }} نتيجة
                        </div>
                        <div>
                            {{ $committees->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
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
                <p class="text-muted mb-0">
                    سيتم حذف اللجنة <strong id="deleteCommitteeName"></strong> نهائياً
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
            const committeeId = button.getAttribute('data-committee-id');
            const committeeName = button.getAttribute('data-committee-name');
            
            document.getElementById('deleteCommitteeName').textContent = committeeName;
            document.getElementById('deleteForm').action = `/committees/${committeeId}`;
        });
    }
});
</script>
@endpush