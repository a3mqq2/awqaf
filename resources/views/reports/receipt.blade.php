<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال دخول الامتحان - {{ $examinee->full_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f8f9fa;
            padding: 0;
            margin: 0;
        }

        .receipt-container {
            width: 210mm;
            height: 200mm;
            margin: 0 auto;
            background: white;
            position: relative;
            padding: 10mm 12mm;
        }

        /* Header */
        .header {
            text-align: center;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-bottom: 2px solid #3c5e7f;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 6px;
        }

        .header h1 {
            font-size: 15px;
            font-weight: 700;
            color: #3c5e7f;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }

        .receipt-number {
            display: inline-block;
            background: #f8f9fa;
            padding: 3px 12px;
            border-radius: 15px;
            border: 1.5px solid #3c5e7f;
            font-size: 10px;
            font-weight: 700;
            color: #3c5e7f;
            margin-top: 5px;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        /* Info Section */
        .info-section {
            display: grid;
            gap: 5px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 70px 1fr;
            background: #f8f9fa;
            padding: 5px 8px;
            border-right: 2px solid #3c5e7f;
            border-radius: 3px;
        }

        .info-label {
            font-size: 10px;
            font-weight: 600;
            color: #666;
        }

        .info-value {
            font-size: 10px;
            font-weight: 700;
            color: #212529;
        }

        /* Scores Section */
        .scores-section {
           background: #fdfdfd;
           border: 2px #2e4a67 dashed;
            color: rgb(0, 0, 0);
            padding: 10px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .scores-title {
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            border-bottom: 1.5px solid rgba(255,255,255,0.3);
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .scores-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin-bottom: 8px;
        }

        .score-box {
            background: rgba(255,255,255,0.15);
            padding: 6px 4px;
            border-radius: 4px;
            text-align: center;
        }

        .score-value {
            font-size: 16px;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }

        .score-label {
            font-size: 7px;
            opacity: 0.9;
            line-height: 1.2;
        }

        .percentage-display {
            background: rgba(255,255,255,0.2);
            padding: 6px;
            border-radius: 4px;
            text-align: center;
            border: 1.5px solid rgba(255,255,255,0.3);
        }

        .percentage-display .label {
            font-size: 9px;
            margin-bottom: 3px;
        }

        .percentage-display .value {
            font-size: 20px;
            font-weight: 700;
        }

        /* Result Section */
        .result-section {
            text-align: center;
            padding: 10px;
            border-radius: 6px;
            border: 2px solid;
        }

        .result-section.passed {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .result-section.failed {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .result-icon {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .result-status {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .result-message {
            font-size: 9px;
            font-weight: 600;
            line-height: 1.3;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding-top: 10px;
            border-top: 1.5px dashed #dee2e6;
            font-size: 9px;
            color: #666;
        }

        .footer-text {
            margin-bottom: 6px;
            font-weight: 600;
            line-height: 1.4;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 5fr auto 1fr;
            gap: 15px;
            align-items: center;
        }

        .footer-item {
            font-size: 9px;
        }

        .footer-label {
            color: #999;
            margin-bottom: 2px;
        }

        .footer-value {
            font-weight: 700;
            color: #3c5e7f;
            font-size: 10px;
        }

        .qr-box {
            width: 45px;
            height: 45px;
            background: #f8f9fa;
            border: 1.5px solid #dee2e6;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            color: #999;
            margin: 0 auto;
        }

        /* Print Button */
        .print-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #3c5e7f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            font-family: 'Cairo', sans-serif;
        }

        .print-btn:hover {
            background: #2e4a67;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .receipt-container {
                width: 210mm;
                height: 148mm;
                margin: 0;
                padding: 10mm 12mm;
                page-break-after: always;
            }

            .print-btn {
                display: none;
            }
        }

        @media screen {
            .receipt-container {
                margin: 20px auto;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">
        🖨️ طباعة
    </button>

    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('logo-primary.png') }}" alt="شعار الأوقاف" class="logo">
            <h1>إيصال دخول الامتحان التحريري</h1>
            <p>اختبار اجازة القران الكريم</p>
            <div class="receipt-number">
                رقم الإيصال: #{{ str_pad($examinee->id, 6, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Info Section -->
            <div class="info-section">
                <div class="info-row">
                    <div class="info-label">الاسم:</div>
                    <div class="info-value">{{ $examinee->full_name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">رقم الهوية:</div>
                    <div class="info-value">{{ $examinee->national_id ?? $examinee->passport_no }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">التجمع:</div>
                    <div class="info-value">{{ $examinee->cluster->name ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">الرواية:</div>
                    <div class="info-value">{{ $examinee->narration->name ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">المكتب:</div>
                    <div class="info-value">{{ $examinee->office->name ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">الموبايل:</div>
                    <div class="info-value">{{ $examinee->phone ?? '-' }}</div>
                </div>
            </div>

            <!-- Scores & Result -->
            <div>
                <div class="scores-section">
                    <div>
                        <div class="scores-title">الدرجات</div>
                        
                        <div class="scores-grid">
                            <div class="score-box">
                                <span class="score-value">{{ number_format($examinee->avg_written, 1) }}</span>
                                <span class="score-label">المنهج<br>العلمي<br>(من 40)</span>
                            </div>

                            <div class="score-box">
                                <span class="score-value">{{ number_format($examinee->avg_oral, 1) }}</span>
                                <span class="score-label">الشفهي<br><br>(من 100)</span>
                            </div>

                            <div class="score-box">
                                <span class="score-value">{{ number_format($examinee->total_score, 1) }}</span>
                                <span class="score-label">المجموع<br><br>(من 140)</span>
                            </div>
                        </div>

                        <div class="percentage-display">
                            <div class="label">النسبة المئوية</div>
                            <div class="value">{{ number_format($examinee->percentage, 1) }}%</div>
                        </div>
                    </div>
                </div>

                <div class="result-section {{ $examinee->is_passed ? 'passed' : 'failed' }}" style="margin-top: 10px;">
                    <div class="result-status">{{ $examinee->is_passed ? 'ناجح' : 'راسب' }}</div>
                    <div class="result-message">
                        @if($examinee->is_passed)
                           لقد اجتاز الاختبار بنجاح
                        @else
                            لم تحقق نسبة النجاح المطلوبة (50%)
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                هذا الإيصال صالح لدخول الامتحان - يرجى إحضار بطاقة الهوية يوم الامتحان
            </p>
            
            <div class="footer-grid">
                <div class="footer-item" style="text-align: right;">
                    <div class="footer-label">تاريخ الإصدار</div>
                    <div class="footer-value">{{ now()->format('Y/m/d') }}</div>
                </div>

                {{-- <div class="qr-box">
                    QR
                </div> --}}

                <div class="footer-item" style="text-align: left;">
                    <div class="footer-label">التوقيت</div>
                    <div class="footer-value">{{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>