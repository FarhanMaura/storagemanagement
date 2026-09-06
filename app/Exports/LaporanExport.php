<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $search;
    protected $jenisLaporan;
    protected $startDate;
    protected $endDate;

    public function __construct($search = null, $jenisLaporan = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->jenisLaporan = $jenisLaporan;
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
                  ->orWhere('keterangan', 'like', "%{$this->search}%")
                  ->orWhere('lokasi', 'like', "%{$this->search}%");
            });
        }

        // Filter berdasarkan jenis laporan
        if ($this->jenisLaporan && in_array($this->jenisLaporan, ['masuk', 'keluar'])) {
            $query->where('jenis_laporan', $this->jenisLaporan);
        }

        // Filter berdasarkan tanggal CREATED_AT
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
        $filterInfo = $this->getFilterInfo();

        return [
            ['LAPORAN BARANG'],
            ['Diexport pada: ' . date('d/m/Y H:i:s')],
            [$filterInfo],
            [''], // Empty row
            [
                'NO',
                'TANGGAL LAPORAN',
                'JAM LAPORAN',
                'JENIS LAPORAN',
                'KODE BARANG',
                'NAMA BARANG',
                'JUMLAH',
                'SATUAN',
                'LOKASI',
                'KETERANGAN',
                'DIBUAT OLEH',
                'STATUS'
            ]
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
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');

        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
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

        $sheet->getStyle('A2:L3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '333333']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDEBF7']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        $sheet->getStyle('A5:L5')->applyFromArray([
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

        return [
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Barang';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Add total row
                $totalRows = $this->collection()->count();
                $event->sheet->setCellValue('A' . ($totalRows + 7), 'TOTAL DATA:');
                $event->sheet->setCellValue('B' . ($totalRows + 7), $totalRows);

                $event->sheet->getStyle('A' . ($totalRows + 7) . ':B' . ($totalRows + 7))->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2']
                    ]
                ]);
            },
        ];
    }

    private function getFilterInfo()
    {
        $filters = [];

        if ($this->search) {
            $filters[] = "Pencarian: {$this->search}";
        }
        if ($this->jenisLaporan) {
            $filters[] = "Jenis: " . ($this->jenisLaporan === 'masuk' ? 'Barang Masuk' : 'Barang Keluar');
        }
        if ($this->startDate) {
            $filters[] = "Tanggal Mulai: {$this->startDate}";
        }
        if ($this->endDate) {
            $filters[] = "Tanggal Akhir: {$this->endDate}";
        }

        return $filters ? 'Filter: ' . implode(' | ', $filters) : 'Semua Data';
    }
}
