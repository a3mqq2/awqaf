<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة تواصل جديدة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #3c5e7f 0%, #2d4960 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .field {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .field:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #3c5e7f;
            margin-bottom: 5px;
            display: block;
        }
        .value {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .message-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-right: 4px solid #3c5e7f;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{asset('logo-white.png')}}" width="200" alt="">
            <h1> رسالة تواصل جديدة</h1>
            <p style="margin: 5px 0 0 0;">نظام امتحان الإجازة</p>
        </div>
        
        <div class="content">
            <div class="field">
                <span class="label"> الاسم الكامل:</span>
                <span class="value">{{ $contactName }}</span>
            </div>
            
            <div class="field">
                <span class="label"> رقم الهاتف:</span>
                <span class="value">{{ $contactPhone }}</span>
            </div>
            
            <div class="field">
                <span class="label"> المدينة:</span>
                <span class="value">{{ $contactCity }}</span>
            </div>
            
            <div class="field">
                <span class="label"> الرسالة:</span>
                <div class="message-box">
                    <div class="value">{{ $contactMessage }}</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>تم الإرسال من نظام امتحان الإجازة</p>
            <p>© {{ date('Y') }} وزارة الأوقاف والشؤون الإسلامية</p>
        </div>
    </div>
</body>
</html>