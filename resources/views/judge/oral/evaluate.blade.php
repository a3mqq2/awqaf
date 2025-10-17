@extends('layouts.app')

@section('title', 'الاختبار الشفهي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('judge.oral.dashboard') }}">الاختبار الشفهي</a></li>
    <li class="breadcrumb-item active">التقييم</li>
@endsection

@push('styles')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: #003a4c;
        background-image: url('/images/shape.svg');
        background-size: cover;
        background-attachment: fixed;
    }

    .evaluation-container {
        max-width: 100%;
        width: 100vw;
        margin: 0;
        background: #fff;
        border-radius: 0;
        padding: 15px;
        display: grid;
        grid-template-columns: auto 1fr;
        grid-template-rows: auto 1fr auto auto;
        gap: 15px;
        min-height: calc(100vh - 80px);
        grid-template-areas: 
            "header header"
            "pdfs scoring"
            "summary summary"
            "footer footer";
        transition: all 0.3s ease;
    }

    .evaluation-container.pdf-collapsed {
        grid-template-columns: 50px 1fr;
    }

    /* Header Section */
    .header-section {
        grid-area: header;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 15px 20px;
    }

    .header-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .header-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .header-label {
        font-size: 10px;
        font-weight: 500;
        color: #6c757d;
        text-transform: uppercase;
    }

    .header-value {
        font-size: 14px;
        font-weight: 600;
        color: #003a4c;
    }

    /* Question Progress */
    .question-progress {
        background: linear-gradient(135deg, #003a4c 0%, #004a5c 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0,58,76,0.3);
    }

    .question-progress h2 {
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .question-number {
        font-size: 48px;
        font-weight: 700;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .question-score-display {
        background: rgba(255,255,255,0.2);
        padding: 12px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .question-score-display .label {
        font-size: 12px;
        margin-bottom: 5px;
    }

    .question-score-display .value {
        font-size: 32px;
        font-weight: 700;
    }

    /* Scoring Panel */
    .scoring-panel {
        grid-area: scoring;
        display: flex;
        flex-direction: column;
        gap: 12px;
        height: calc(100vh - 280px);
        overflow-y: auto;
        padding: 0 10px;
    }

    .scoring-panel::-webkit-scrollbar {
        width: 6px;
    }

    .scoring-panel::-webkit-scrollbar-thumb {
        background: #003a4c;
        border-radius: 3px;
    }

    /* Scoring Grid Container */
    .scoring-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        transition: all 0.3s ease;
    }

    /* When PDF is collapsed, use more space */
    .pdf-collapsed .scoring-grid {
        grid-template-columns: repeat(4, 1fr);
    }

    /* Memory section spans full width */
    .memory-section {
        grid-column: span 3;
    }

    .pdf-collapsed .memory-section {
        grid-column: span 4;
    }

    /* Rules section spans 2 columns */
    .rules-section {
        grid-column: span 2;
    }

    .pdf-collapsed .rules-section {
        grid-column: span 2;
    }

    /* Individual memory items in a row */
    .memory-items {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .pdf-collapsed .memory-items {
        grid-template-columns: repeat(5, 1fr);
    }

    /* Individual rules items in a column */
    .rules-items {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .pdf-collapsed .rules-items {
        grid-template-columns: repeat(2, 1fr);
    }

    /* PDFs Panel (Left - Collapsible) */
    .pdfs-panel {
        grid-area: pdfs;
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 12px;
        position: relative;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 280px);
        width: 400px;
        transition: width 0.3s ease;
        overflow: hidden;
    }

    .pdfs-panel.collapsed {
        width: 50px;
    }

    .pdfs-panel.collapsed .pdfs-content {
        opacity: 0;
        visibility: hidden;
    }

    .pdfs-panel.collapsed .pdf-toggle-btn {
        right: auto;
        left: 50%;
        transform: translateX(-50%);
    }

    .pdfs-panel.collapsed .pdf-toggle-btn i {
        transform: rotate(180deg);
    }

    .pdf-toggle-btn {
        position: absolute;
        right: 10px;
        top: 10px;
        background: #003a4c;
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,58,76,0.3);
    }

    .pdf-toggle-btn:hover {
        background: #004a5c;
        transform: scale(1.1);
    }

    .pdf-toggle-btn i {
        transition: transform 0.3s ease;
        font-size: 20px;
    }

    .pdfs-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 15px;
        opacity: 1;
        visibility: visible;
        transition: all 0.3s ease;
    }

    .pdfs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #003a4c;
        margin-top: 30px;
    }

    .pdfs-header h3 {
        margin: 0;
        font-size: 18px;
        color: #003a4c;
        font-weight: 700;
    }

    .pdfs-viewer-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
        overflow: hidden;
        border-radius: 8px;
        background: white;
    }

    /* PDF List */
    .pdf-list {
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
    }

    .pdf-list::-webkit-scrollbar {
        width: 6px;
    }

    .pdf-list::-webkit-scrollbar-thumb {
        background: #003a4c;
        border-radius: 3px;
    }

    .pdf-item {
        padding: 10px;
        margin-bottom: 6px;
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-item:hover {
        background: #e9ecef;
        border-color: #003a4c;
    }

    .pdf-item.active {
        background: #003a4c;
        color: white;
        border-color: #003a4c;
    }

    .pdf-item i {
        font-size: 16px;
        color: #dc3545;
    }

    .pdf-item.active i {
        color: white;
    }

    /* PDF Viewer */
    .pdf-viewer-main {
        flex: 1;
        position: relative;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .pdf-viewer {
        width: 100%;
        height: 100%;
        border: none;
    }

    .pdf-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #6c757d;
    }

    .pdf-placeholder i {
        font-size: 64px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Summary Section (Total Score & Notes) */
    .summary-section {
        grid-area: summary;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 20px;
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* Total Score Display - Updated */
    .total-score-container {
        background: linear-gradient(135deg, #003a4c 0%, #004a5c 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,58,76,0.3);
    }

    .total-score-container h3 {
        margin: 0 0 15px 0;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .total-score-value {
        font-size: 3rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        margin: 10px 0;
    }

    .total-score-container small {
        font-size: 14px;
        opacity: 0.9;
    }

    /* Notes Section - Updated */
    .notes-container {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 12px;
        padding: 15px;
    }

    .notes-container h3 {
        margin: 0 0 12px 0;
        font-size: 16px;
        font-weight: 700;
        color: #003a4c;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 2px solid #003a4c;
    }

    .notes-container textarea {
        width: 100%;
        min-height: 80px;
        padding: 12px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        resize: vertical;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .notes-container textarea:focus {
        outline: none;
        border-color: #003a4c;
        box-shadow: 0 0 0 3px rgba(0,58,76,0.1);
    }

    /* Card Styles */
    .eval-card {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .eval-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .eval-card h3 {
        margin-bottom: 12px;
        font-size: 15px;
        font-weight: 700;
        color: #003a4c;
        border-bottom: 2px solid #003a4c;
        padding-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Counter Styles */
    .counter-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 12px 8px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        text-align: center;
    }

    .counter-row:hover {
        border-color: #003a4c;
        box-shadow: 0 2px 8px rgba(0,58,76,0.1);
        transform: translateY(-2px);
    }

    .counter-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .counter-label small {
        display: block;
        font-size: 11px;
        color: #dc3545;
        margin-top: 2px;
        font-weight: 500;
    }

    .counter-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .counter-btn {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 50%;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-plus {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-plus:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(40,167,69,0.4);
    }

    .btn-plus:active {
        transform: scale(0.95);
    }

    .btn-minus {
        background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
        color: white;
    }

    .btn-minus:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(220,53,69,0.4);
    }

    .btn-minus:active {
        transform: scale(0.95);
    }

    .counter-value {
        min-width: 40px;
        text-align: center;
        font-weight: 700;
        font-size: 15px;
        color: #003a4c;
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 5px;
        border: 2px solid #dee2e6;
        transition: all 0.2s ease;
    }



    /* Footer */
    .footer-section {
        grid-area: footer;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 15px;
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        gap: 15px;
        align-items: center;
    }

    .footer-section button {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-previous {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .btn-previous:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108,117,125,0.4);
    }

    .btn-previous:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-approve-question {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-approve-question:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40,167,69,0.4);
    }

    .btn-final-save {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }

    .btn-final-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,123,255,0.4);
    }

    .question-status {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        background: white;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        font-size: 14px;
        font-weight: 600;
    }

    .question-status.completed {
        border-color: #28a745;
        background: #d4edda;
        color: #155724;
    }

    .question-status.in-progress {
        border-color: #ffc107;
        background: #fff3cd;
        color: #856404;
    }



    /* Responsive Design */
    @media (max-width: 1600px) {
        .scoring-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .pdf-collapsed .scoring-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .memory-section {
            grid-column: span 2;
        }

        .pdf-collapsed .memory-section {
            grid-column: span 3;
        }

        .memory-items {
            grid-template-columns: repeat(2, 1fr);
        }

        .pdf-collapsed .memory-items {
            grid-template-columns: repeat(3, 1fr);
        }

        .rules-section {
            grid-column: span 1;
        }

        .pdf-collapsed .rules-section {
            grid-column: span 2;
        }
    }

    @media (max-width: 1400px) {
        .evaluation-container {
            grid-template-columns: auto 1fr;
        }
        
        .evaluation-container.pdf-collapsed {
            grid-template-columns: 50px 1fr;
        }

        .pdfs-panel {
            width: 350px;
        }
    }

    @media (max-width: 1200px) {
        .evaluation-container {
            grid-template-columns: auto 1fr;
        }
        
        .evaluation-container.pdf-collapsed {
            grid-template-columns: 50px 1fr;
        }

        .pdfs-panel {
            width: 300px;
        }

        .header-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .scoring-grid {
            grid-template-columns: 1fr;
        }

        .pdf-collapsed .scoring-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .memory-section,
        .pdf-collapsed .memory-section {
            grid-column: span 1;
        }

        .pdf-collapsed .memory-section {
            grid-column: span 2;
        }

        .memory-items {
            grid-template-columns: repeat(2, 1fr);
        }

        .pdf-collapsed .memory-items {
            grid-template-columns: repeat(3, 1fr);
        }

        .rules-section,
        .pdf-collapsed .rules-section {
            grid-column: span 1;
        }

        .rules-items {
            grid-template-columns: 1fr;
        }

        .summary-section {
            grid-template-columns: 250px 1fr;
        }
    }

    @media (max-width: 1024px) {
        .evaluation-container {
            grid-template-columns: 1fr;
            grid-template-areas: 
                "header"
                "pdfs"
                "scoring"
                "summary"
                "footer";
            gap: 10px;
            padding: 10px;
        }

        .evaluation-container.pdf-collapsed {
            grid-template-columns: 1fr;
        }

        .pdfs-panel {
            width: 100%;
            height: 400px;
        }

        .pdfs-panel.collapsed {
            width: 100%;
            height: 50px;
        }

        .scoring-panel {
            height: auto;
            max-height: none;
            overflow-y: visible;
        }

        .scoring-grid {
            grid-template-columns: 2fr;
        }

        .memory-section,
        .rules-section {
            grid-column: span 1;
        }

        .memory-items {
            grid-template-columns: repeat(2, 1fr);
        }

        .header-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-section {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .footer-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .evaluation-container {
            padding: 8px;
        }

        .header-section {
            padding: 12px 15px;
        }

        .header-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .pdfs-panel {
            height: 300px;
        }

        .memory-items {
            grid-template-columns: 1fr;
        }

        .eval-card h3 {
            font-size: 14px;
        }

        .counter-label {
            font-size: 12px;
        }

        .question-number {
            font-size: 36px;
        }

        .total-score-value {
            font-size: 2rem;
        }

        .summary-section {
            padding: 15px;
        }
    }
</style>
@endpush

@section('content')
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
                <!-- PDF List -->
                <div class="pdf-list">
                    @if($evaluation->examinee->narration && $evaluation->examinee->narration->pdfs->count() > 0)
                        @foreach($evaluation->examinee->narration->pdfs as $index => $pdf)
                            <div class="pdf-item {{ $index == 0 ? 'active' : '' }}" 
                                 onclick="loadPdf('{{ $pdf->file_url }}', this)">
                                <i class="ti ti-file-text"></i>
                                <span>{{ $pdf->title }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="ti ti-file-off mb-2" style="font-size: 32px;"></i>
                            <div>لا توجد ملفات</div>
                        </div>
                    @endif
                </div>

                <!-- Viewer -->
                <div class="pdf-viewer-main">
                    @if($evaluation->examinee->narration && $evaluation->examinee->narration->pdfs->count() > 0)
                        <iframe id="pdfViewer" 
                                class="pdf-viewer" 
                                src="{{ $evaluation->examinee->narration->pdfs->first()->file_url }}#toolbar=1&navpanes=0&scrollbar=1">
                        </iframe>
                    @else
                        <div class="pdf-placeholder">
                            <i class="ti ti-file-off"></i>
                            <div>لا توجد ملفات PDF للرواية</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scoring Panel -->
    <div class="scoring-panel">
        <!-- Question Progress -->
        <div class="question-progress">
            <h2 class="text-white">السؤال الحالي</h2>
            <div class="question-number" id="currentQuestionNumber">{{ $evaluation->current_question }}</div>
            <small>من 12 سؤال</small>
            
            <div class="question-score-display">
                <div class="label">درجة السؤال</div>
                <div class="value" id="questionScore">8.33</div>
                <small>من 8.33</small>
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
                            الأحكام الفرعية
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
                            الأحكام الأصلية
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
                            عدم إتقان أصل
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

                <div class="counter-row">
                    <span class="counter-label">
                        وقف لا يتم به معنى
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
                        وقف قبيح
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
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const evaluationId = {{ $evaluation->id }};
let currentQuestion = {{ $evaluation->current_question }};
let questionsData = @json($evaluation->questions_data ?? []);

// Toggle PDF Panel
function togglePdfPanel() {
    const panel = document.getElementById('pdfsPanel');
    const container = document.getElementById('evaluationContainer');
    
    panel.classList.toggle('collapsed');
    container.classList.toggle('pdf-collapsed');
}

// Load PDF
function loadPdf(url, element) {
    document.querySelectorAll('.pdf-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    
    document.getElementById('pdfViewer').src = url + '#toolbar=1&navpanes=0&scrollbar=1';
}

// Update Deduction
function updateDeduction(type, action) {
    $.ajax({
        url: `/judge/oral/evaluate/${evaluationId}/update-deduction`,
        method: 'POST',
        data: {
            type: type,
            action: action,
            _token: '{{ csrf_token() }}'
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
                    _token: '{{ csrf_token() }}'
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
                    _token: '{{ csrf_token() }}'
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
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ!',
                            text: `${response.message}\nالدرجة النهائية: ${response.final_score}`,
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            window.location.href = '{{ route("judge.oral.dashboard") }}';
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
</script>
@endpush