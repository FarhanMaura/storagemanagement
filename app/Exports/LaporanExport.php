<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $search;
    protected $startDate;
    protected $endDate;

    public function __construct($search = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Laporan::with('user');

        // Filter berdasarkan pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('kode_barang', 'like', "%{$this->search}%")
                  ->orWhere('nama_barang', 'like', "%{$this->search}%")
                  ->orWhere('keterangan', 'like', "%{$this->search}%");
            });
        }

        // Filter berdasarkan tanggal
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'JAM',
            'JENIS LAPORAN',
            'KODE BARANG',
            'NAMA BARANG',
            'JUMLAH',
            'SATUAN',
            'LOKASI',
            'KETERANGAN',
            'DIBUAT OLEH',
            'STATUS'
        ];
    }

    public function map($laporan): array
    {
        static $no = 1;

        return [
            $no++,
            $laporan->created_at->format('d/m/Y'),
            $laporan->created_at->format('H:i:s'),
            $laporan->jenis_laporan === 'masuk' ? 'BARANG MASUK' : 'BARANG KELUAR',
            $laporan->kode_barang,
            $laporan->nama_barang,
            $laporan->jumlah,
            strtoupper($laporan->satuan),
            $laporan->lokasi,
            $laporan->keterangan ?? '-',
            $laporan->user->name,
            $laporan->created_at->isToday() ? 'BARU' : 'LAMA'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Auto size columns
        foreach(range('A','L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Border untuk semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:L{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Barang';
    }
}
