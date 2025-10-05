<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .print-card {
            width: 8.5cm;
            height: 5.37cm;
            margin: 0 auto 20px;
            padding: 0;
            overflow: hidden;
            background: #fff;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid #3c5e7f;
        }

        /* الشعار والعنوان */
        .header-section {
            background: linear-gradient(135deg, #3c5e7f 0%, #2e4a67 100%);
            padding: 6px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-right img {
            width: 32px;
            height: auto;
        }

        .header-text {
            color: white;
        }

        .card-title {
            font-size: 11px;
            font-weight: 800;
            margin: 0;
            line-height: 1.1;
        }

        .card-subtitle {
            font-size: 7px;
            font-weight: 600;
            margin: 0;
            opacity: 0.9;
            line-height: 1.1;
        }

        .card-number {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* قسم المعلومات */
        .info-container {
            padding: 8px 10px 20px;
            background: linear-gradient(to bottom, #fefefe 0%, #f8f9fa 100%);
        }

        /* الاسم */
        .name-section {
            background: linear-gradient(135deg, #3c5e7f 0%, #2e4a67 100%);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 6px;
            box-shadow: 0 2px 4px rgba(60, 94, 127, 0.2);
        }

        .name-label {
            font-size: 7px;
            font-weight: 700;
            margin-bottom: 1px;
            opacity: 0.9;
            line-height: 1;
        }

        .name-value {
            font-size: 11px;
            font-weight: 800;
            line-height: 1.1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Grid المعلومات */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .field-section {
            background: white;
            padding: 5px 8px;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }

        .field-label {
            color: #3c5e7f;
            font-size: 7px;
            font-weight: 700;
            margin-bottom: 1px;
            line-height: 1;
        }

        .field-value {
            font-size: 9px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Footer */
        .footer-section {
            background: linear-gradient(90deg, #998965 0%, #7a6d4f 100%);
            color: #fff;
            text-align: center;
            padding: 3px;
            font-size: 7px;
            font-weight: 600;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            line-height: 1.2;
        }

        /* طباعة */
        @media print {
            @page {
                margin: 0;
                size: 8.5cm 5.37cm;
            }
            
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            
            .print-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                box-shadow: none;
                border-radius: 0;
                page-break-after: always;
                margin: 0;
            }
            
            .header-section,
            .name-section,
            .footer-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
        }

        /* للعرض على الشاشة */
        @media screen {
            body {
                padding: 20px;
            }
            
            .print-button {
                margin: 20px auto;
                background: #3c5e7f;
                color: #fff;
                padding: 12px 30px;
                border: none;
                border-radius: 8px;
                font-weight: 700;
                font-size: 16px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                transition: all 0.3s;
                font-family: 'Cairo', sans-serif;
            }
            
            .print-button:hover {
                background: #2e4a67;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(60, 94, 127, 0.3);
            }

            .cards-container {
                max-width: 900px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="cards-container">
        @foreach($examinees as $examinee)
            <div class="print-card">
                <!-- Header -->
                <div class="header-section">
                    <div class="header-right">
                        <img src="{{asset('logo-primary.png')}}" alt="شعار">
                        <div class="header-text">
                            <div class="card-title">بطاقة ممتحن</div>
                            <div class="card-subtitle">امتحان الإجازة 1446/1447 هـ</div>
                        </div>
                    </div>
                    <div class="card-number">
                        #{{ str_pad($examinee->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <!-- المعلومات -->
                <div class="info-container">
                    <!-- الاسم -->
                    <div class="name-section">
                        <div class="name-label">الاسم الكامل</div>
                        <div class="name-value">{{ $examinee->full_name }}</div>
                    </div>

                    <!-- Grid المعلومات - 2 أعمدة -->
                    <div class="info-grid">
                        <!-- الرقم الوطني / او الاداري/الجواز -->
                        <div class="field-section">
                            <div class="field-label">
                                {{ $examinee->national_id ? 'الرقم الوطني / او الاداري' : 'رقم الجواز' }}
                            </div>
                            <div class="field-value">
                                {{ $examinee->national_id ?? $examinee->passport_no ?? '-' }}
                            </div>
                        </div>

                        <!-- التجمع -->
                        <div class="field-section">
                            <div class="field-label">التجمع</div>
                            <div class="field-value">{{ $examinee->cluster->name ?? '-' }}</div>
                        </div>

                        <!-- الرواية -->
                        <div class="field-section">
                            <div class="field-label">الرواية</div>
                            <div class="field-value">{{ $examinee->narration->name ?? '-' }}</div>
                        </div>

                        <!-- الرسم -->
                        <div class="field-section">
                            <div class="field-label">الرسم</div>
                            <div class="field-value">{{ $examinee->drawing->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="footer-section">
                    وزارة الأوقاف والشؤون الإسلامية - دولة ليبيا
                </div>
            </div>
        @endforeach

        <!-- زر الطباعة -->
        <button class="print-button no-print" onclick="window.print()">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/>
            </svg>
            طباعة البطاقات ({{ count($examinees) }})
        </button>
    </div>
</body>
</html>