<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $barang->kode_barang }}</title>
    <style>
        /* Reset semua margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            /* Hide everything during print */
            @page {
                margin: 0.5cm !important;
                padding: 0 !important;
                size: A4 portrait;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: white !important;
                font-family: 'Arial', sans-serif;
                width: 100%;
                height: 100%;
            }

            /* Hide header, footer, and other browser elements */
            @page :header { display: none !important; }
            @page :footer { display: none !important; }

            .barcode-container {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .barcode-section {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 20px;
                border-bottom: 2px dashed #e2e8f0;
            }

            .barcode-section:last-child {
                border-bottom: none;
            }

            .company-info {
                margin-bottom: 15px;
                text-align: center;
            }

            .barcode-image {
                image-rendering: crisp-edges;
                image-rendering: pixelated;
                margin: 10px 0;
            }

            .product-info {
                margin-top: 15px;
                padding: 12px;
                background: #f8fafc;
                border-radius: 6px;
                border: 1px solid #e2e8f0;
                text-align: center;
                width: 100%;
                max-width: 300px;
            }
        }

        /* Styles untuk preview di browser */
        @media screen {
            body {
                background: #f1f5f9;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            .barcode-container {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 0;
                width: 21cm;
                min-height: 29.7cm;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
        }

        /* Common styles */
        .company-info h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }

        .company-info p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #64748b;
        }

        .product-info p {
            margin: 5px 0;
        }

        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1e293b;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #e2e8f0;
        }

        .product-name {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin: 6px 0;
        }

        .scan-info {
            font-size: 9px;
            color: #6b7280;
            line-height: 1.3;
        }

        /* QR Code specific */
        .qr-section .barcode-image {
            width: 200px;
            height: 200px;
        }

        /* Barcode 1D specific */
        .barcode-section .barcode-image {
            height: 70px;
            width: auto;
            max-width: 280px;
        }
    </style>
</head>
<body>
    <div class="barcode-container">
        @if($jenis === 'qr' || $jenis === 'keduanya')
        <!-- QR Code Section -->
        <div class="barcode-section qr-section">
            <div class="company-info">
                <h3>PT. PENGELOLA KERTAS</h3>
                <p>Storage Management System</p>
            </div>

            <div class="barcode-display">
                <img src="{{ $barcodeQR }}" alt="QR Code {{ $barang->kode_barang }}"
                     class="barcode-image">
            </div>

            <div class="product-info">
                <p class="barcode-number">{{ $barang->kode_barang }}</p>
                <p class="product-name">{{ $barang->nama_barang }}</p>
                <p class="scan-info">
                    🔍 Scan untuk informasi lengkap<br>
                    {{ url('/scan/' . $barang->kode_barang) }}
                </p>
            </div>
        </div>
        @endif

        @if($jenis === 'barcode' || $jenis === 'keduanya')
        <!-- Barcode 1D Section -->
        <div class="barcode-section">
            <div class="company-info">
                <h3>PT. PENGELOLA KERTAS</h3>
                <p>Inventory Management System</p>
            </div>

            <div class="barcode-display">
                <img src="{{ $barcode1D }}" alt="Barcode {{ $barang->kode_barang }}"
                     class="barcode-image">
            </div>

            <div class="product-info">
                <p class="barcode-number">{{ $barang->kode_barang }}</p>
                <p class="product-name">{{ $barang->nama_barang }}</p>
                <p class="scan-info">
                    📠 Scan barcode untuk informasi stok<br>
                    Gunakan barcode scanner
                </p>
            </div>
        </div>
        @endif
    </div>

    <script>
        // Auto print saat halaman load
        window.onload = function() {
            setTimeout(() => {
                // Print tanpa dialog (jika diizinkan)
                window.print();

                // Close setelah print
                window.onafterprint = function() {
                    setTimeout(() => {
                        window.close();
                    }, 100);
                };

                // Fallback close
                setTimeout(() => {
                    if (!document.hidden) {
                        window.close();
                    }
                }, 2000);
            }, 500);
        };

        // Prevent any browser UI interference
        document.addEventListener('DOMContentLoaded', function() {
            // Force remove any browser headers/footers
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    @page { margin: 0.5cm; }
                    body { margin: 0; }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>
