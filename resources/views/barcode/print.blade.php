<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $barang->kode_barang }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 kolom */
            gap: 15px;
            width: 100%;
        }

        .barcode-card {
            border: 2px solid #000;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-inside: avoid;
            background: #fff;
            height: 280px; /* Fixed height for consistency */
        }

        .company-header {
            text-align: center;
            border-bottom: 2px solid #000;
            width: 100%;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }

        .company-sub {
            font-size: 10px;
            margin: 2px 0 0;
        }

        .barcode-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 5px 0;
        }

        .barcode-img-qr {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        .barcode-img-1d {
            height: 60px;
            width: 100%;
            object-fit: contain;
        }

        .product-details {
            text-align: center;
            width: 100%;
            border-top: 1px dotted #000;
            padding-top: 8px;
            margin-top: 5px;
        }

        .product-code {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 16px;
            margin: 0 0 4px;
            background: #000;
            color: #fff;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .product-name {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
            height: 32px; /* Limit height for 2 lines */
        }

        .scan-hint {
            font-size: 9px;
            margin-top: 4px;
            font-style: italic;
        }

        /* Screen preview adjustments */
        @media screen {
            body {
                background: #f0f0f0;
                padding: 20px;
            }
            .page-container {
                background: white;
                width: 21cm;
                min-height: 29.7cm;
                margin: 0 auto;
                padding: 1cm;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        }

        @media print {
            body {
                background: white;
            }
            .page-container {
                width: 100%;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="barcode-grid">
            @foreach($barcodes as $barcode)
                @if($jenis === 'qr' || $jenis === 'keduanya')
                <div class="barcode-card">
                    <div class="company-header">
                        <h1 class="company-name">PT. JOKOWIDODO 3PERIODE</h1>
                        <p class="company-sub">Storage Management System</p>
                    </div>
                    
                    <div class="barcode-area">
                        <img src="{{ $barcode['barcodeQR'] }}" class="barcode-img-qr" alt="QR">
                    </div>

                    <div class="product-details">
                        <div class="product-code">{{ $barcode['code'] }}</div>
                        <div class="product-name">{{ $barang->nama_barang }}</div>
                        <div class="scan-hint">Scan untuk detail</div>
                    </div>
                </div>
                @endif

                @if($jenis === 'barcode' || $jenis === 'keduanya')
                <div class="barcode-card">
                    <div class="company-header">
                        <h1 class="company-name">PT. JOKOWIDODO 3PERIODE</h1>
                        <p class="company-sub">Inventory Management</p>
                    </div>
                    
                    <div class="barcode-area">
                        <img src="{{ $barcode['barcode1D'] }}" class="barcode-img-1d" alt="Barcode">
                    </div>

                    <div class="product-details">
                        <div class="product-code">{{ $barcode['code'] }}</div>
                        <div class="product-name">{{ $barang->nama_barang }}</div>
                        <div class="scan-hint">Scan untuk stok</div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
