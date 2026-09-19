<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingReportExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        protected ?string $startDate = null,
        protected ?string $endDate = null,
        protected ?string $status = null,
        protected ?int $locationId = null
    ) {}

    public function query(): Builder
    {
        $query = Booking::with(['requester.location', 'vehicle.category', 'vehicle.location', 'driver', 'creator', 'approvals.approver', 'usageHistory']);

        if ($this->startDate) {
            $query->whereDate('start_datetime', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('end_datetime', '<=', $this->endDate);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->locationId) {
            $query->whereHas('vehicle', function ($q) {
                $q->where('location_id', $this->locationId);
            });
        }

        return $query->latest();
    }

    public function title(): string
    {
        return 'Laporan Pemesanan Kendaraan';
    }

    public function headings(): array
    {
        return [
            'Kode Booking',
            'Tanggal Dibuat',
            'Pemohon',
            'Departemen',
            'Lokasi / Pool',
            'Kendaraan',
            'Plat Nomor',
            'Driver',
            'Tujuan',
            'Keperluan',
            'Waktu Mulai',
            'Waktu Selesai',
            'Jumlah Penumpang/Muatan',
            'Status Pemesanan',
            'Approver Level 1',
            'Status Level 1',
            'Approver Level 2',
            'Status Level 2',
            'Odometer Awal',
            'Odometer Akhir',
        ];
    }

    public function map($booking): array
    {
        $l1 = $booking->approvals->where('approval_level', 1)->first();
        $l2 = $booking->approvals->where('approval_level', 2)->first();

        return [
            $booking->booking_code,
            $booking->created_at?->format('d/m/Y H:i'),
            $booking->requester?->name ?? '-',
            $booking->requester?->department ?? '-',
            $booking->vehicle?->location?->name ?? '-',
            ($booking->vehicle?->brand ?? '').' '.($booking->vehicle?->model ?? ''),
            $booking->vehicle?->plate_number ?? '-',
            $booking->driver?->name ?? '-',
            $booking->destination,
            $booking->purpose,
            $booking->start_datetime?->format('d/m/Y H:i'),
            $booking->end_datetime?->format('d/m/Y H:i'),
            $booking->passenger_or_load_qty,
            strtoupper(str_replace('_', ' ', $booking->status)),
            $l1?->approver?->name ?? '-',
            strtoupper($l1?->status ?? '-'),
            $l2?->approver?->name ?? '-',
            strtoupper($l2?->status ?? '-'),
            $booking->usageHistory?->start_odometer ?? '-',
            $booking->usageHistory?->end_odometer ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F172A'],
                ],
            ],
        ];
    }
}
