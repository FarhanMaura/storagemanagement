<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $barang->kode_barang }}</title>
    <style>
        @media print {
            @page {
                margin: 0.5cm;
                size: A4;
            }
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: white !important;
            }
            .barcode-page {
                page-break-after: always;
                border: 2px solid #e5e7eb;
                padding: 30px;
                margin: 15px 0;
                height: calc(100vh - 60px);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-radius: 20px;
            }
            .barcode-container {
                text-align: center;
                width: 100%;
                max-width: 500px;
            }
            .company-info {
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 2px solid #e2e8f0;
            }
            .barcode-image {
                max-width: 100%;
                height: auto;
                image-rendering: crisp-edges;
                image-rendering: pixelated;
                border: 1px solid #cbd5e0;
                border-radius: 10px;
                background: white;
                padding: 10px;
            }
            .product-info {
                margin-top: 20px;
                padding: 15px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        }

        .barcode-page {
            border: 2px solid #e5e7eb;
            padding: 30px;
            margin: 15px 0;
            height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
        }
        .barcode-container {
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .company-info h3 {
            margin: 0;
            font-size: 20pt;
            font-weight: bold;
            color: #1e293b;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .company-info p {
            margin: 8px 0 0 0;
            font-size: 11pt;
            color: #64748b;
            font-weight: 500;
        }
        .product-info p {
            margin: 8px 0;
        }
        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 3px;
            color: #1e293b;
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
        }
        .product-name {
            font-size: 13pt;
            font-weight: 600;
            color: #374151;
            margin: 10px 0;
        }
        .scan-info {
            font-size: 9pt;
            color: #6b7280;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    @if($jenis === 'qr' || $jenis === 'keduanya')
    <!-- QR Code Page -->
    <div class="barcode-page">
        <div class="barcode-container">
            <div class="company-info">
                <h3>PT. PENGELOLA KERTAS</h3>
                <p>Storage Management System</p>
            </div>

            <div class="barcode-display mb-6">
                <img src="{{ $barcodeQR }}" alt="QR Code {{ $barang->kode_barang }}"
                     class="barcode-image" style="width: 280px; height: 280px;">
            </div>

            <div class="product-info">
                <p class="barcode-number">{{ $barang->kode_barang }}</p>
                <p class="product-name">{{ $barang->nama_barang }}</p>
                <p class="scan-info">
                    🔍 Scan QR code untuk informasi lengkap<br>
                    <strong>{{ url('/scan/' . $barang->kode_barang) }}</strong>
                </p>
            </div>
        </div>
    </div>
    @endif

    @if($jenis === 'barcode' || $jenis === 'keduanya')
    <!-- Barcode 1D Page -->
    <div class="barcode-page">
        <div class="barcode-container">
            <div class="company-info">
                <h3>PT. PENGELOLA KERTAS</h3>
                <p>Inventory Management System</p>
            </div>

            <div class="barcode-display mb-6">
                <img src="{{ $barcode1D }}" alt="Barcode {{ $barang->kode_barang }}"
                     class="barcode-image" style="height: 120px; width: auto; padding: 15px;">
            </div>

            <div class="product-info">
                <p class="barcode-number">{{ $barang->kode_barang }}</p>
                <p class="product-name">{{ Str::limit($barang->nama_barang, 30) }}</p>
                <p class="scan-info">
                    📠 Scan barcode untuk informasi stok<br>
                    Gunakan barcode scanner
                </p>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Auto print saat halaman load
        window.onload = function() {
            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    window.close();
                }, 500);
            }, 1000);
        };
    </script>
</body>
</html>
