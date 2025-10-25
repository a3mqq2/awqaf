<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الاختبار الشفهي</title>
    
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/evaluate.css') }}">
        <!-- [Font] Changa from Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Changa:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        
</head>
<body>
    <div class="evaluation-container" id="evaluationContainer">
        <!-- Header Section -->
        <div class="header-section">
            <h1 style="color: #003a4c; font-size: 20px; font-weight: 700; margin-bottom: 0;">
                <i class="ti ti-microphone me-2"></i>
                واجهة الاختبار الشفهي
            </h1>
            <div class="header-grid">
                <div class="header-item">
                    <span class="header-label">اسم الممتحن</span>
                    <span class="header-value">{{ $evaluation->examinee->full_name }}</span>
                </div>
                <div class="header-item">
                    <span class="header-label">اسم المحكم</span>
                    <span class="header-value">{{ Auth::user()->name }}</span>
                </div>
                <div class="header-item">
                    <span class="header-label">الرقم الوطني</span>
                    <span class="header-value">{{ $evaluation->examinee->national_id ?? $evaluation->examinee->passport_no }}</span>
                </div>
                <div class="header-item">
                    <span class="header-label">الرواية</span>
                    <span class="header-value">{{ $evaluation->examinee->narration->name ?? '-' }}</span>
                </div>
                <div class="header-item">
                    <span class="header-label">التجمع</span>
                    <span class="header-value">{{ $evaluation->committee->cluster->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- PDFs Panel (Left - Collapsible) -->
        <div class="pdfs-panel" id="pdfsPanel">
            <button class="pdf-toggle-btn" onclick="togglePdfPanel()">
                <i class="ti ti-chevron-left"></i>
            </button>
            
            <div class="pdfs-content">
                <div class="pdfs-header">
                    <h3>
                        <i class="ti ti-file-text me-2"></i>
                        المصحف الشريف
                    </h3>
                </div>

                <div class="pdfs-viewer-container">
                    @php
                    $pdfs = [
                        ['title' => 'مصحف الجماهيرية قالون أبوعمرو الداني', 'url' => url('/pdf/q_pdf_file')],
                        ['title' => 'مصحف قراءات العشر', 'url' => url('/pdf/msqam_pdf_file')],
                    ];
                    @endphp
                
                    <div class="pdf-list">
                        @foreach($pdfs as $index => $pdf)
                            <div class="pdf-item {{ $index == 0 ? 'active' : '' }}" onclick="loadPdf('{{ $pdf['url'] }}', this)">
                                <i class="ti ti-file-text"></i>
                                <span>{{ $pdf['title'] }}</span>
                            </div>
                        @endforeach
                    </div>
                
                    <div class="pdf-viewer-main">
                        <!-- PDF Controls -->
                        <div class="pdf-controls">
                            <div class="pdf-zoom-controls">
                                <button onclick="zoomOut()">
                                    <i class="ti ti-zoom-out"></i>
                                    تصغير
                                </button>
                                <button onclick="zoomIn()">
                                    <i class="ti ti-zoom-in"></i>
                                    تكبير
                                </button>
                            </div>
                            
                            <div class="pdf-page-info">
                                صفحة <span id="pageNum">1</span> من <span id="pageCount">--</span>
                            </div>
                            
                            <div>
                                <button onclick="prevPage()" id="prevPage">
                                    <i class="ti ti-arrow-up"></i>
                                    السابق
                                </button>
                                <button onclick="nextPage()" id="nextPage">
                                    <i class="ti ti-arrow-down"></i>
                                    التالي
                                </button>
                            </div>
                        </div>
                        
                        <!-- PDF Canvas Container -->
                        <div class="pdf-canvas-container" id="pdfCanvasContainer">
                            <div class="pdf-placeholder">
                                <i class="ti ti-file-text"></i>
                                <p>اختر مصحفاً من القائمة أعلاه</p>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

        <!-- Scoring Panel -->
        <div class="scoring-panel">
            <!-- Question Progress -->
            <div class="question-progress">
                <div class="question-info">
                    <h2 class="text-white">السؤال</h2>
                    <div class="question-number" id="currentQuestionNumber">{{ $evaluation->current_question }}</div>
                    <span class="question-total">/ 12</span>
                </div>
                
                <div class="question-score-display">
                    <div class="label">الدرجة:</div>
                    <div class="value" id="questionScore">8.33</div>
                    <small>/ 8.33</small>
                </div>
            </div>

            <!-- Scoring Grid -->
            <div class="scoring-grid">
                <!-- بنود الحفظ (Memory Section) -->
                <div class="eval-card memory-section">
                    <h3>
                        <i class="ti ti-book"></i>
                        بنود الحفظ
                    </h3>
                    
                    <div class="memory-items">
                        <div class="counter-row">
                            <span class="counter-label">
                                تعلثم
                                <small>-0.125</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('stutter', 'decrement')">-</button>
                                <span class="counter-value" id="stutter">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('stutter', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                إعادة
                                <small>-0.25</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('repeat', 'decrement')">-</button>
                                <span class="counter-value" id="repeat">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('repeat', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                تردد
                                <small>-0.375</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('hesitation', 'decrement')">-</button>
                                <span class="counter-value" id="hesitation">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('hesitation', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                تنبيه
                                <small>-0.5</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('alert', 'decrement')">-</button>
                                <span class="counter-value" id="alert">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('alert', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                فتح
                                <small>-1.0</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('open', 'decrement')">-</button>
                                <span class="counter-value" id="open">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('open', 'increment')">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الأحكام (Rules Section) -->
                <div class="eval-card rules-section">
                    <h3>
                        <i class="ti ti-book-2"></i>
                        الأحكام
                    </h3>

                    <div class="rules-items">
                        <div class="counter-row">
                            <span class="counter-label">
                                فرعية
                                <small>-0.125</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('sub_ruling', 'decrement')">-</button>
                                <span class="counter-value" id="sub_ruling">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('sub_ruling', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                أصلية
                                <small>-0.5</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('main_ruling', 'decrement')">-</button>
                                <span class="counter-value" id="main_ruling">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('main_ruling', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                عدم إتقان
                                <small>-0.5</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('not_mastered', 'decrement')">-</button>
                                <span class="counter-value" id="not_mastered">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('not_mastered', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                ترك أصل
                                <small>-1.0</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('left_origin', 'decrement')">-</button>
                                <span class="counter-value" id="left_origin">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('left_origin', 'increment')">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الوقف (Stop Section) -->
                <div class="eval-card">
                    <h3>
                        <i class="ti ti-player-pause"></i>
                        الوقف
                    </h3>

                    <div class="rules-items">
                        <div class="counter-row">
                            <span class="counter-label">
                                لا يتم معنى
                                <small>-0.25</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('incomplete_stop', 'decrement')">-</button>
                                <span class="counter-value" id="incomplete_stop">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('incomplete_stop', 'increment')">+</button>
                            </div>
                        </div>

                        <div class="counter-row">
                            <span class="counter-label">
                                قبيح
                                <small>-0.25</small>
                            </span>
                            <div class="counter-controls">
                                <button class="counter-btn btn-minus" onclick="updateDeduction('bad_stop', 'decrement')">-</button>
                                <span class="counter-value" id="bad_stop">0</span>
                                <button class="counter-btn btn-plus" onclick="updateDeduction('bad_stop', 'increment')">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section (Total Score & Notes) -->
        <div class="summary-section">
            <!-- Total Score -->
            <div class="total-score-container">
                <h3 class="text-white">المجموع الكلي</h3>
                <div class="total-score-value" id="totalScore">{{ number_format($evaluation->total_score, 2) }}</div>
                <small>من 100 درجة</small>
            </div>

            <!-- Notes -->
            <div class="notes-container">
                <h3>
                    <i class="ti ti-notes"></i>
                    ملاحظات المحكم
                </h3>
                <textarea id="notes" placeholder="اكتب ملاحظاتك حول أداء الممتحن في هذا السؤال...">{{ $evaluation->notes }}</textarea>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="footer-section">
            <button class="btn-previous" id="btnPrevious" onclick="previousQuestion()" {{ $evaluation->current_question == 1 ? 'disabled' : '' }}>
                <i class="ti ti-arrow-right"></i>
                <span>السؤال السابق</span>
            </button>

            <div class="question-status" id="questionStatus">
                <i class="ti ti-clock"></i>
                <span>جاري التقييم</span>
            </div>

            <button class="btn-approve-question" id="btnApprove" onclick="approveQuestion()" {{ $evaluation->current_question > 12 ? 'style=display:none' : '' }}>
                <i class="ti ti-check"></i>
                <span>اعتماد السؤال</span>
            </button>

            <button class="btn-final-save" id="btnFinalSave" onclick="finalSave()" style="display: none;">
                <i class="ti ti-device-floppy"></i>
                <span>حفظ التقييم النهائي</span>
            </button>
        </div>
    </div>

    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Configure PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/evaluate.js') }}"></script>
    
    <script>
        // Initialize evaluation with data from backend
        initEvaluation(
            {{ $evaluation->id }},
            {{ $evaluation->current_question }},
            @json($evaluation->questions_data ?? [])
        );
        
        // Auto-load first PDF on page load
        $(document).ready(function() {
            const firstPdfUrl = '{{ $pdfs[0]['url'] ?? '' }}';
            if (firstPdfUrl && firstPdfUrl !== '') {
                const firstPdfElement = document.querySelector('.pdf-item.active');
                if (firstPdfElement) {
                    loadPdf(firstPdfUrl, firstPdfElement);
                }
            }
        });
    </script>
</body>
</html>