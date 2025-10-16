@extends('layouts.app')

@section('title', 'إدارة ملفات PDF - ' . $narration->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('narrations.index') }}">الروايات</a></li>
    <li class="breadcrumb-item active">ملفات PDF - {{ $narration->name }}</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
    .pdf-card {
        transition: all 0.3s ease;
        cursor: move;
    }
    .pdf-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    .pdf-preview {
        width: 100%;
        height: 300px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
    }
    .sortable-ghost {
        opacity: 0.4;
    }
    .drag-handle {
        cursor: move;
    }
</style>
@endpush

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <!-- Narration Info -->
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-white">
                            <i class="ti ti-book me-2"></i>
                            {{ $narration->name }}
                        </h4>
                        <p class="mb-0">
                            <i class="ti ti-file-text me-2"></i>
                            عدد ملفات PDF: <strong>{{ $pdfs->count() }}</strong>
                        </p>
                    </div>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="ti ti-upload me-1"></i>
                        رفع ملف PDF جديد
                    </button>
                </div>
            </div>
        </div>

        <!-- PDFs List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-files me-2"></i>
                        قائمة ملفات PDF
                    </h5>
                    <div>
                        <span class="text-muted">
                            <i class="ti ti-arrows-move me-1"></i>
                            اسحب لإعادة الترتيب
                        </span>
                        <button class="btn btn-sm btn-success ms-2" id="saveOrderBtn" style="display: none;">
                            <i class="ti ti-device-floppy me-1"></i>
                            حفظ الترتيب
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($pdfs->isEmpty())
                    <div class="text-center py-5">
                        <i class="ti ti-file-off display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد ملفات PDF</h5>
                        <p class="text-muted">قم برفع ملفات PDF للمنهج العلمي</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="ti ti-upload me-1"></i>
                            رفع أول ملف
                        </button>
                    </div>
                @else
                    <div class="row" id="pdfsList">
                        @foreach($pdfs as $pdf)
                            <div class="col-md-4 mb-3" data-id="{{ $pdf->id }}">
                                <div class="card pdf-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="drag-handle">
                                                <i class="ti ti-grip-vertical text-muted fs-4"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-1">{{ $pdf->title }}</h6>
                                                <small class="text-muted">
                                                    <i class="ti ti-hash"></i>
                                                    الترتيب: {{ $pdf->order }}
                                                </small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $pdf->file_url }}" target="_blank">
                                                            <i class="ti ti-eye me-2"></i>معاينة
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('narrations.pdfs.toggle', [$narration, $pdf]) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ti ti-toggle-{{ $pdf->is_active ? 'right' : 'left' }} me-2"></i>
                                                                {{ $pdf->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" 
                                                                class="dropdown-item text-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal"
                                                                data-pdf-id="{{ $pdf->id }}"
                                                                data-pdf-title="{{ $pdf->title }}">
                                                            <i class="ti ti-trash me-2"></i>حذف
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- PDF Preview -->
                                        <iframe src="{{ $pdf->file_url }}#toolbar=0&navpanes=0&scrollbar=0&page=1" 
                                                class="pdf-preview"></iframe>

                                        <!-- Status Badge -->
                                        <div class="mt-2">
                                            <span class="badge {{ $pdf->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                <i class="ti ti-{{ $pdf->is_active ? 'check' : 'x' }} me-1"></i>
                                                {{ $pdf->is_active ? 'مفعل' : 'غير مفعل' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-3">
            <a href="{{ route('narrations.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>
                رجوع للروايات
            </a>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-upload me-2"></i>
                    رفع ملف PDF جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('narrations.pdfs.store', $narration) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ti ti-text me-1"></i>
                            عنوان الملف <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               placeholder="مثال: الجزء الأول"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PDF File -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ti ti-file-text me-1"></i>
                            ملف PDF <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                               name="pdf_file" 
                               class="form-control @error('pdf_file') is-invalid @enderror" 
                               accept=".pdf"
                               required>
                        <small class="text-muted">الحد الأقصى: 50 ميجابايت</small>
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ti ti-hash me-1"></i>
                            الترتيب
                        </label>
                        <input type="number" 
                               name="order" 
                               class="form-control" 
                               value="{{ $pdfs->count() + 1 }}"
                               min="0">
                        <small class="text-muted">يمكنك إعادة الترتيب لاحقاً بالسحب</small>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>ملاحظة:</strong> سيظهر هذا الملف للمحكمين أثناء التقييم
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-upload me-1"></i>رفع الملف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 bg-danger">
                <h5 class="modal-title text-white">
                    <i class="ti ti-alert-triangle me-2"></i>تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-trash display-1 text-danger mb-3"></i>
                <h6>هل أنت متأكد من حذف الملف؟</h6>
                <p class="text-muted mb-0">
                    سيتم حذف <strong id="deletePdfTitle"></strong> نهائياً
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Sortable for drag and drop
    const pdfsList = document.getElementById('pdfsList');
    
    if (pdfsList) {
        const sortable = new Sortable(pdfsList, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                $('#saveOrderBtn').show();
            }
        });
    }

    // Save Order
    $('#saveOrderBtn').on('click', function() {
        const orders = {};
        $('#pdfsList .col-md-4').each(function(index) {
            const pdfId = $(this).data('id');
            orders[pdfId] = index + 1;
        });

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>جاري الحفظ...');

        $.ajax({
            url: '{{ route("narrations.pdfs.update-order", $narration) }}',
            method: 'POST',
            data: {
                orders: orders,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    location.reload();
                }
            },
            error: function() {
                alert(' حدث خطأ أثناء حفظ الترتيب');
                $('#saveOrderBtn').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>حفظ الترتيب');
            }
        });
    });

    // Delete Modal
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const pdfId = button.getAttribute('data-pdf-id');
            const pdfTitle = button.getAttribute('data-pdf-title');
            
            document.getElementById('deletePdfTitle').textContent = pdfTitle;
            document.getElementById('deleteForm').action = '/narrations/{{ $narration->id }}/pdfs/' + pdfId;
        });
    }
});
</script>
@endpush