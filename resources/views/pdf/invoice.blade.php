<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÝÇÊæÑÉ ÔÍä ÑÕíÏ - {{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
        }
        .invoice-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: #2d3748;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #cbd5e0;
            padding: 8px 12px;
            text-align: right;
        }
        th {
            background-color: #edf2f7;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #e2e8f0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #718096;
        }
        .signature {
            margin-top: 60px;
            border-top: 1px solid #cbd5e0;
            padding-top: 10px;
            width: 50%;
            float: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="invoice-title">ÝÇÊæÑÉ ÔÍä ÑÕíÏ</h1>
        <p>äÙÇã ßÑÊ ÇáãÝÊÇÍ áÅÏÇÑÉ ßÑæÊ ÇáÅäÊÑäÊ</p>
    </div>
    
    <div class="invoice-info">
        <table>
            <tr>
                <td style="width: 30%">ÑÞã ÇáÝÇÊæÑÉ:</td>
                <td>INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td style="width: 30%">ÊÇÑíÎ ÇáÅÕÏÇÑ:</td>
                <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>äÞØÉ ÇáÈíÚ:</td>
                <td>{{ $invoice->pointOfSale->name }}</td>
                <td>ÇáãæÞÚ:</td>
                <td>{{ $invoice->pointOfSale->location }}</td>
            </tr>
            <tr>
                <td>ÇáãÔÑÝ ÇáãÓÄæá:</td>
                <td>{{ $invoice->pointOfSale->supervisor->name }}</td>
                <td>ÍÇáÉ ÇáÝÇÊæÑÉ:</td>
                <td>{{ $invoice->status == 'paid' ? 'ãÏÝæÚÉ' : 'ÛíÑ ãÏÝæÚÉ' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <h2 class="section-title">ÊÝÇÕíá ÇáÝÇÊæÑÉ</h2>
        <table>
            <thead>
                <tr>
                    <th>ÇáæÕÝ</th>
                    <th>ÇáãÈáÛ (ÑíÇá íãäí)</th>
                    <th>ÇáÊÇÑíÎ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->transactions as $transaction)
                <tr>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ number_format($transaction->amount) }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" style="text-align: left;">ÇáÅÌãÇáí</td>
                    <td>{{ number_format($invoice->amount) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="signature">
        <p>ÊæÞíÚ ÇáãÍÇÓÈ: ___________________</p>
    </div>
    
    <div class="footer">
        <p>ÔßÑÇð áÊÚÇãáßã ãÚ äÙÇã ßÑÊ ÇáãÝÊÇÍ</p>
        <p>ÌãíÚ ÇáÍÞæÞ ãÍÝæÙÉ &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>