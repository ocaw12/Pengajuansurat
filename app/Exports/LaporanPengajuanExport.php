<?php

// File: app/Exports/LaporanPengajuanExport.php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class LaporanPengajuanExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    ShouldAutoSize
{
    protected Collection $pengajuans;
    protected int    $bulan;
    protected int    $tahun;
    protected string $namaProdi;
    protected Collection $schemaKeys;

    public function __construct(Collection $pengajuans, int $bulan, int $tahun, string $namaProdi)
    {
        $this->pengajuans = $pengajuans;
        $this->bulan      = $bulan;
        $this->tahun      = $tahun;
        $this->namaProdi  = $namaProdi;

        // Kumpulkan semua key data_pendukung dinamis
        $keys = collect();
        foreach ($pengajuans as $p) {
            if (is_array($p->data_pendukung)) {
                $keys = $keys->merge(array_keys($p->data_pendukung));
            }
        }
        $this->schemaKeys = $keys->unique()->values();
    }

    public function title(): string
    {
        return 'Laporan ' . Carbon::createFromDate($this->tahun, $this->bulan, 1)->translatedFormat('F Y');
    }

    public function headings(): array
    {
        $base = [
            'No',
            'Tanggal Pengajuan',
            'NIM',
            'Nama Mahasiswa',
            'Program Studi',
            'Jenis Surat',
            'Kode Surat',
            'Keperluan',
            'Metode',
            'Status',
            'Nomor Surat',
            'Divalidasi Oleh',
            'Catatan Admin',
        ];

        $schema = $this->schemaKeys
            ->map(fn($k) => '[Form] ' . ucwords(str_replace('_', ' ', $k)))
            ->toArray();

        return array_merge($base, $schema);
    }

    public function collection(): Collection
    {
        return $this->pengajuans->map(function ($p, $i) {
            $dataPendukung = is_array($p->data_pendukung) ? $p->data_pendukung : [];

            $row = [
                $i + 1,
                Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y H:i'),
                $p->mahasiswa->nim ?? '-',
                $p->mahasiswa->nama_lengkap ?? '-',
                $p->mahasiswa->programStudi->nama_prodi ?? '-',
                $p->jenisSurat->nama_surat ?? '-',
                $p->jenisSurat->kode_surat ?? '-',
                $p->keperluan,
                ucfirst($p->metode_pengambilan),
                ucwords(str_replace('_', ' ', $p->status_pengajuan)),
                $p->nomor_surat ?? '-',
                $p->adminValidator->nama_lengkap ?? '-',
                $p->catatan_admin ?? '-',
            ];

            foreach ($this->schemaKeys as $key) {
                $val = $dataPendukung[$key] ?? '-';
                // Kalau path file, jangan tampilkan full path
                if (is_string($val) && str_starts_with($val, 'dokumen_pengajuan/')) {
                    $val = '[File: ' . basename($val) . ']';
                }
                $row[] = is_array($val) ? implode(', ', $val) : $val;
            }

            return $row;
        });
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 18,  // Tanggal
            'C' => 14,  // NIM
            'D' => 25,  // Nama
            'E' => 20,  // Prodi
            'F' => 28,  // Jenis Surat
            'G' => 12,  // Kode
            'H' => 35,  // Keperluan
            'I' => 12,  // Metode
            'J' => 18,  // Status
            'K' => 28,  // Nomor Surat
            'L' => 22,  // Validator
            'M' => 25,  // Catatan
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // Header row styling
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A5F'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Data rows — alternating background
        for ($row = 2; $row <= $lastRow; $row++) {
            $bg = ($row % 2 === 0) ? 'F0F4F8' : 'FFFFFF';
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bg],
                ],
                'font'      => ['size' => 9],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);
        }

        // Border seluruh tabel
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Freeze header row
        $sheet->freezePane('A2');

        // Row height header
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }
}