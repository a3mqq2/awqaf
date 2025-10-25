// Global variables - these will be set from the blade file
let evaluationId;
let currentQuestion;
let questionsData = [];

// PDF.js variables
let pdfDoc = null;
let currentPdfUrl = null;
let currentScale = 1.5;
let currentPage = 1;
let pageRendering = false;
let pageNumPending = null;

// Initialize the evaluation system
function initEvaluation(evalId, currentQ, questData) {
    evaluationId = evalId;
    currentQuestion = currentQ;
    questionsData = questData || [];
}

// Toggle PDF Panel
function togglePdfPanel() {
    const panel = document.getElementById('pdfsPanel');
    const container = document.getElementById('evaluationContainer');
    
    panel.classList.toggle('collapsed');
    container.classList.toggle('pdf-collapsed');
}

// Load PDF using PDF.js
function loadPdf(url, element) {
    // Update active state
    document.querySelectorAll('.pdf-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    
    // Check if URL is valid
    if (!url || url === 'null' || url === 'undefined') {
        showPdfError('رابط الملف غير متاح');
        return;
    }
    
    currentPdfUrl = url;
    currentPage = 1;
    
    // Show loading
    const container = document.getElementById('pdfCanvasContainer');
    container.innerHTML = '<div class="pdf-loading"><i class="ti ti-loader"></i><p>جاري تحميل المصحف...</p></div>';
    
    // Load PDF using PDF.js
    const loadingTask = pdfjsLib.getDocument(url);
    
    loadingTask.promise.then(function(pdf) {
        pdfDoc = pdf;
        document.getElementById('pageCount').textContent = pdf.numPages;
        
        // Enable/disable navigation buttons
        updatePdfNavButtons();
        
        // Render all pages
        renderAllPages();
    }).catch(function(error) {
        console.error('Error loading PDF:', error);
        showPdfError('حدث خطأ في تحميل المصحف');
    });
}

// Render all PDF pages
function renderAllPages() {
    const container = document.getElementById('pdfCanvasContainer');
    container.innerHTML = '';
    
    for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
        const canvas = document.createElement('canvas');
        canvas.id = 'pdf-canvas-' + pageNum;
        canvas.dataset.pageNum = pageNum;
        container.appendChild(canvas);
        
        renderPage(pageNum, canvas);
    }
}

// Render a specific page
function renderPage(pageNum, canvas) {
    pdfDoc.getPage(pageNum).then(function(page) {
        const viewport = page.getViewport({ scale: currentScale });
        const context = canvas.getContext('2d');
        
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        
        const renderContext = {
            canvasContext: context,
            viewport: viewport
        };
        
        page.render(renderContext);
    });
}

// Update PDF navigation buttons
function updatePdfNavButtons() {
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const pageNum = document.getElementById('pageNum');
    
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= pdfDoc.numPages;
    if (pageNum) pageNum.textContent = currentPage;
}

// Show PDF error
function showPdfError(message) {
    const container = document.getElementById('pdfCanvasContainer');
    container.innerHTML = `<div class="pdf-error"><i class="ti ti-alert-circle"></i><p>${message}</p></div>`;
}

// Zoom In
function zoomIn() {
    currentScale += 0.25;
    if (currentScale > 3) currentScale = 3;
    if (pdfDoc) renderAllPages();
}

// Zoom Out
function zoomOut() {
    currentScale -= 0.25;
    if (currentScale < 0.5) currentScale = 0.5;
    if (pdfDoc) renderAllPages();
}

// Go to specific page (scroll to it)
function goToPage(pageNum) {
    if (pageNum < 1 || pageNum > pdfDoc.numPages) return;
    
    currentPage = pageNum;
    updatePdfNavButtons();
    
    const canvas = document.getElementById('pdf-canvas-' + pageNum);
    if (canvas) {
        canvas.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Previous Page
function prevPage() {
    if (currentPage <= 1) return;
    goToPage(currentPage - 1);
}

// Next Page
function nextPage() {
    if (currentPage >= pdfDoc.numPages) return;
    goToPage(currentPage + 1);
}

// Update Deduction
function updateDeduction(type, action) {
    $.ajax({
        url: `/judge/oral/evaluate/${evaluationId}/update-deduction`,
        method: 'POST',
        data: {
            type: type,
            action: action,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // تحديث العدادات
                $('#' + type).text(response.deductions[type]);
                
                // تحديث درجة السؤال
                $('#questionScore').text(response.question_score.toFixed(2));
                
                // Visual feedback
                const element = document.getElementById(type);
                element.style.transform = 'scale(1.2)';
                element.style.background = action == 'increment' ? '#f8d7da' : '#d4edda';
                element.style.borderColor = action == 'increment' ? '#dc3545' : '#28a745';
                
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                    element.style.background = '#f8f9fa';
                    element.style.borderColor = '#dee2e6';
                }, 200);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'حدث خطأ';
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: message,
                confirmButtonText: 'حسناً'
            });
        }
    });
}

// Approve Question
function approveQuestion() {
    Swal.fire({
        title: 'اعتماد السؤال',
        text: `هل أنت متأكد من اعتماد السؤال رقم ${currentQuestion}؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، اعتمد',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/judge/oral/evaluate/${evaluationId}/approve-question`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Check if evaluation was auto-completed after question 12
                        if (response.is_completed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم إكمال التقييم!',
                                html: `${response.message}<br><strong>الدرجة النهائية: ${response.final_score}</strong>`,
                                confirmButtonText: 'حسناً'
                            }).then(() => {
                                // Redirect to oral dashboard
                                window.location.href = '/judge/oral';
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الاعتماد!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // تحديث رقم السؤال
                            currentQuestion = response.current_question;
                            $('#currentQuestionNumber').text(currentQuestion);
                            $('#totalScore').text(response.total_score.toFixed(2));

                            // إعادة تصفير العدادات
                            resetCounters();

                            // تحديث الأزرار
                            if (response.is_last_question) {
                                $('#btnApprove').hide();
                                $('#btnFinalSave').show();
                                $('#questionStatus').html('<i class="ti ti-check"></i><span>جميع الأسئلة مكتملة</span>').addClass('completed');
                            }

                            $('#btnPrevious').prop('disabled', false);
                        }
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'حدث خطأ';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: message,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

// Previous Question
function previousQuestion() {
    Swal.fire({
        title: 'الرجوع للسؤال السابق',
        text: 'سيتم إلغاء اعتماد السؤال السابق. هل أنت متأكد؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'نعم، ارجع',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/judge/oral/evaluate/${evaluationId}/previous-question`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الرجوع',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // تحديث رقم السؤال
                        currentQuestion = response.current_question;
                        $('#currentQuestionNumber').text(currentQuestion);
                        $('#totalScore').text(response.total_score.toFixed(2));

                        // تحميل بيانات السؤال السابق
                        loadQuestionData(response.question_data);

                        // تحديث الأزرار
                        if (currentQuestion == 1) {
                            $('#btnPrevious').prop('disabled', true);
                        }

                        $('#btnApprove').show();
                        $('#btnFinalSave').hide();
                        $('#questionStatus').html('<i class="ti ti-clock"></i><span>جاري التقييم</span>').removeClass('completed');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'حدث خطأ';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: message,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

// Load Question Data
function loadQuestionData(questionData) {
    // تحميل قيم العدادات
    Object.keys(questionData.deductions).forEach(key => {
        $('#' + key).text(questionData.deductions[key]);
    });

    // تحديث درجة السؤال
    $('#questionScore').text(questionData.final_score.toFixed(2));
}

// Reset Counters
function resetCounters() {
    const counters = ['stutter', 'repeat', 'hesitation', 'alert', 'open', 'sub_ruling', 'main_ruling', 'not_mastered', 'left_origin', 'incomplete_stop', 'bad_stop'];
    
    counters.forEach(counter => {
        $('#' + counter).text('0');
    });

    $('#questionScore').text('8.33');
}

// Final Save
function finalSave() {
    Swal.fire({
        title: 'حفظ التقييم النهائي',
        text: 'هل أنت متأكد من حفظ التقييم النهائي؟ لن تتمكن من التعديل بعد ذلك.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، احفظ',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const notes = $('#notes').val();

            $.ajax({
                url: `/judge/oral/evaluate/${evaluationId}/save`,
                method: 'POST',
                data: {
                    notes: notes,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ!',
                            text: `${response.message}\nالدرجة النهائية: ${response.final_score}`,
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            // Redirect back to judge oral dashboard
                            window.location.href = '/judge/oral';
                        });
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'حدث خطأ';
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: message,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}