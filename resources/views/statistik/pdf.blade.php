<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik Barang - PT. Pengelola Kertas</title>
    <style>
        /* Reset dan base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1f2937;
            padding: 20px;
            background: #ffffff;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .header .subtitle {
            font-size: 16px;
            color: #4b5563;
            font-weight: 500;
        }

        /* Statistik Grid */
        .stats-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 15px;
        }

        .stat-card {
            border: 2px solid #e5e7eb;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            flex: 1;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 20px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Sections */
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1e40af;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .section-title::before {
            content: "";
            width: 4px;
            height: 20px;
            background: #3b82f6;
            margin-right: 10px;
            border-radius: 2px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        /* Top items */
        .top-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .top-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .top-item-number {
            background: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            margin-right: 12px;
        }

        .top-item-content {
            flex: 1;
        }

        .top-item-name {
            font-weight: 600;
            font-size: 12px;
            color: #1f2937;
        }

        .top-item-detail {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .top-item-count {
            background: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            min-width: 60px;
            text-align: center;
        }

        /* Highlight boxes */
        .highlight-box {
            background: linear-gradient(135deg, #fff9db, #ffec99);
            border: 2px solid #ffd43b;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .highlight-title {
            font-size: 14px;
            font-weight: bold;
            color: #e67700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .highlight-title::before {
            content: "💡";
            margin-right: 8px;
            font-size: 16px;
        }

        /* Performance cards */
        .performance-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .performance-card {
            flex: 1;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: #ffffff;
        }

        .performance-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 18px;
        }

        .performance-value {
            font-size: 20px;
            font-weight: bold;
            margin: 8px 0;
            color: #1e40af;
        }

        .performance-label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #9ca3af;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
            padding-top: 30px;
        }

        /* Colors for different types */
        .bg-green {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0) !important;
            border-color: #10b981 !important;
        }
        .bg-red {
            background: linear-gradient(135deg, #fee2e2, #fecaca) !important;
            border-color: #ef4444 !important;
        }
        .bg-blue {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe) !important;
            border-color: #3b82f6 !important;
        }
        .bg-purple {
            background: linear-gradient(135deg, #e9d5ff, #d8b4fe) !important;
            border-color: #8b5cf6 !important;
        }
        .bg-yellow {
            background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
            border-color: #f59e0b !important;
        }

        .text-green { color: #059669 !important; }
        .text-red { color: #dc2626 !important; }
        .text-blue { color: #2563eb !important; }
        .text-purple { color: #7c3aed !important; }
        .text-yellow { color: #d97706 !important; }

        /* Chart table */
        .chart-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .chart-table th {
            background: linear-gradient(135deg, #1e40af, #3730a3);
        }

        /* Two column layout */
        .two-column {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .column {
            flex: 1;
        }

        /* Signature section */
        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature-line {
            border-top: 1px solid #374151;
            width: 200px;
            margin: 40px 0 8px auto;
            padding-top: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN STATISTIK BARANG</h1>
        <p class="subtitle">PT. PENGELOLA KERTAS - Storage Management System</p>
        <p>Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Semua Waktu' }}
           - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}</p>
        <p>Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <!-- Statistik Utama -->
    <div class="section">
        <div class="section-title">📊 STATISTIK UTAMA</div>
        <div class="stats-grid">
            <div class="stat-card bg-green">
                <div class="stat-icon bg-green">📥</div>
                <div class="stat-number text-green">{{ number_format($statistik['total_masuk']) }}</div>
                <div class="stat-label">Total Barang Masuk</div>
            </div>
            <div class="stat-card bg-red">
                <div class="stat-icon bg-red">📤</div>
                <div class="stat-number text-red">{{ number_format($statistik['total_keluar']) }}</div>
                <div class="stat-label">Total Barang Keluar</div>
            </div>
            <div class="stat-card bg-blue">
                <div class="stat-icon bg-blue">📋</div>
                <div class="stat-number text-blue">{{ number_format($statistik['total_laporan']) }}</div>
                <div class="stat-label">Total Laporan</div>
            </div>
            <div class="stat-card bg-purple">
                <div class="stat-icon bg-purple">👥</div>
                <div class="stat-number text-purple">{{ number_format($statistik['total_users']) }}</div>
                <div class="stat-label">Total User</div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Bulan Ini -->
    <div class="section">
        <div class="section-title">📈 AKTIVITAS BULAN INI</div>
        <div class="performance-grid">
            <div class="performance-card bg-green">
                <div class="performance-icon bg-green">📥</div>
                <div class="performance-value text-green">{{ number_format($statistik['bulan_ini_masuk']) }}</div>
                <div class="performance-label">Barang Masuk</div>
            </div>
            <div class="performance-card bg-red">
                <div class="performance-icon bg-red">📤</div>
                <div class="performance-value text-red">{{ number_format($statistik['bulan_ini_keluar']) }}</div>
                <div class="performance-label">Barang Keluar</div>
            </div>
            <div class="performance-card bg-blue">
                <div class="performance-icon bg-blue">📊</div>
                <div class="performance-value text-blue">{{ number_format($statistik['bulan_ini_laporan']) }}</div>
                <div class="performance-label">Laporan Dibuat</div>
            </div>
        </div>
    </div>

    <!-- Top Barang -->
    <div class="section page-break">
        <div class="section-title">🏆 TOP 5 BARANG TERBANYAK</div>
        <div class="two-column">
            <!-- Barang Masuk -->
            <div class="column">
                <div style="font-size: 13px; font-weight: bold; color: #059669; margin-bottom: 12px; text-align: center;">
                    BARANG MASUK
                </div>
                @forelse($statistik['top_barang_masuk'] as $index => $barang)
                <div class="top-item bg-green">
                    <div style="display: flex; align-items: center; flex: 1;">
                        <div class="top-item-number">{{ $index + 1 }}</div>
                        <div class="top-item-content">
                            <div class="top-item-name">{{ $barang->nama_barang }}</div>
                            <div class="top-item-detail">Kode: {{ $barang->kode_barang }}</div>
                        </div>
                    </div>
                    <div class="top-item-count">{{ number_format($barang->total) }}</div>
                </div>
                @empty
                <div style="text-align: center; color: #6b7280; font-size: 11px; padding: 20px; background: #f8fafc; border-radius: 8px;">
                    📊 Belum ada data barang masuk
                </div>
                @endforelse
            </div>

            <!-- Barang Keluar -->
            <div class="column">
                <div style="font-size: 13px; font-weight: bold; color: #dc2626; margin-bottom: 12px; text-align: center;">
                    BARANG KELUAR
                </div>
                @forelse($statistik['top_barang_keluar'] as $index => $barang)
                <div class="top-item bg-red">
                    <div style="display: flex; align-items: center; flex: 1;">
                        <div class="top-item-number">{{ $index + 1 }}</div>
                        <div class="top-item-content">
                            <div class="top-item-name">{{ $barang->nama_barang }}</div>
                            <div class="top-item-detail">Kode: {{ $barang->kode_barang }}</div>
                        </div>
                    </div>
                    <div class="top-item-count">{{ number_format($barang->total) }}</div>
                </div>
                @empty
                <div style="text-align: center; color: #6b7280; font-size: 11px; padding: 20px; background: #f8fafc; border-radius: 8px;">
                    📊 Belum ada data barang keluar
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Users -->
    <div class="section">
        <div class="section-title">👥 TOP 5 USER AKTIF</div>
        @forelse($statistik['top_users'] as $index => $user)
        <div class="top-item bg-blue">
            <div style="display: flex; align-items: center; flex: 1;">
                <div class="top-item-number">{{ $index + 1 }}</div>
                <div style="display: flex; align-items: center; flex: 1;">
                    <div style="width: 32px; height: 32px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; margin-right: 12px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="top-item-content">
                        <div class="top-item-name">{{ $user->name }}</div>
                        <div class="top-item-detail">{{ $user->email }}</div>
                    </div>
                </div>
            </div>
            <div class="top-item-count">{{ $user->laporan_count }} laporan</div>
        </div>
        @empty
        <div style="text-align: center; color: #6b7280; font-size: 11px; padding: 20px; background: #f8fafc; border-radius: 8px;">
            👥 Belum ada data user
        </div>
        @endforelse
    </div>

    <!-- Trend Data -->
    <div class="section page-break">
        <div class="section-title">📅 TREND 6 BULAN TERAKHIR</div>
        <div class="chart-table">
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        @foreach($chartData['months'] as $month)
                        <th style="text-align: center;">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: bold; color: #059669; background: #f0fdf4;">Barang Masuk</td>
                        @foreach($chartData['data_masuk'] as $data)
                        <td style="text-align: center; color: #059669; font-weight: 600; background: #f0fdf4;">{{ number_format($data) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #dc2626; background: #fef2f2;">Barang Keluar</td>
                        @foreach($chartData['data_keluar'] as $data)
                        <td style="text-align: center; color: #dc2626; font-weight: 600; background: #fef2f2;">{{ number_format($data) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="section">
        <div class="section-title">📋 RINGKASAN KINERJA</div>
        <div class="highlight-box">
            <div class="highlight-title">Ringkasan Kinerja Sistem</div>
            <div class="performance-grid">
                <div class="performance-card">
                    <div class="performance-icon bg-yellow">⚡</div>
                    <div class="performance-value text-yellow">
                        {{ number_format($statistik['total_laporan'] / max($statistik['hari_operasional'], 1), 1) }}
                    </div>
                    <div class="performance-label">Rata-rata Harian</div>
                </div>
                <div class="performance-card">
                    <div class="performance-icon bg-blue">📦</div>
                    <div class="performance-value text-blue">
                        {{ number_format($statistik['total_masuk'] - $statistik['total_keluar']) }}
                    </div>
                    <div class="performance-label">Stok Akhir</div>
                </div>
                <div class="performance-card">
                    <div class="performance-icon bg-green">📅</div>
                    <div class="performance-value text-green">{{ $statistik['hari_operasional'] }}</div>
                    <div class="performance-label">Hari Operasional</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="signature">
            <div class="signature-line">
                Manager Operasional<br>
                PT. Pengelola Kertas
            </div>
        </div>
        <p>Laporan ini dibuat secara otomatis oleh Sistem Management Storage Kertas</p>
        <p>© {{ date('Y') }} PT. Pengelolaan Kertas Mentah - All Rights Reserved</p>
        <p style="margin-top: 8px; font-size: 9px; color: #9ca3af;">
            Dokumen ini bersifat rahasia dan hanya untuk penggunaan internal
        </p>
    </div>
</body>
</html>
