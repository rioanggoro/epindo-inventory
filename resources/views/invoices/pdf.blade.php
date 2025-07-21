<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20mm;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 0;
            font-size: 10px;
            color: #666;
        }

        .invoice-details {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .invoice-details td {
            padding: 5px 0;
            vertical-align: top;
        }

        .invoice-details td.label {
            font-weight: bold;
            width: 30%;
        }

        .line-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .line-items th,
        .line-items td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .line-items th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            margin-top: 20px;
        }

        .total table {
            width: 40%;
            float: right;
            border-collapse: collapse;
        }

        .total table td {
            padding: 5px;
            border: 1px solid #ccc;
        }

        .total table td.label {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>PT. ELEKTROPLATING SUPERINDO [cite: 4]</p>
        <p>Jl. Karet III Blok H No.24, Delta Silicon II Lippo Cikarang [cite: 5]</p>
        <p>Telp. 021 8990 0915, 8990 0916, Fax. 021 897 2040 </p>
        <p>e-mail: hrd@epindo.co.id </p>
    </div>

    <table class="invoice-details">
        <tr>
            <td class="label">Nomor Invoice:</td>
            <td>{{ $invoice->invoice_number }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Invoice:</td>
            <td>{{ $invoice->invoice_date }}</td>
        </tr>
        <tr>
            <td class="label">Customer:</td>
            <td>{{ $invoice->customer_name }}</td>
        </tr>
    </table>

    <h2>Detail Produk:</h2>
    <table class="line-items">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->product_sold_name }}</td>
                <td>{{ $invoice->quantity_sold }}</td>
                <td>Rp {{ number_format($invoice->total_amount / $invoice->quantity_sold, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <table>
            <tr>
                <td class="label">GRAND TOTAL:</td>
                <td>Rp {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="clear:both; margin-top: 50px; text-align: right;">
        <p>Cikarang, {{ date('d F Y') }}</p>
        <p style="margin-top: 60px;">(_______________________)</p>
        <p>Hormat kami,</p>
    </div>
</body>

</html>
