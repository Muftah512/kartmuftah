<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>تقرير نقاط البيع</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: left; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير نقاط البيع</h1>
        <p>تاريخ التقرير: {{ $date }}</p>
        <p>عدد نقاط البيع: {{ count($pointsOfSale) }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الموقع</th>
                <th>الرصيد</th>
                <th>الحالة</th>
                <th>أنشئت بواسطة</th>
                <th>تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pointsOfSale as $index => $pos)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pos->name }}</td>
                <td>{{ $pos->location ?? '--' }}</td>
                <td>{{ number_format($pos->balance, 2) }} ر.س</td>
                <td>{{ $pos->is_active ? 'نشط' : 'غير نشط' }}</td>
                <td>{{ $pos->creator->name }}</td>
                <td>{{ $pos->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>تم إنشاء التقرير بواسطة نظام كرت المفتاح</p>
        <p>http://cardmuftah.com</p>
    </div>
</body>
</html>

