<?php

namespace App\Exports;

use App\Models\SantriAttendance;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SantriAttendanceExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        private string $startDate,
        private string $endDate,
        private ?int $classId = null
    ) {}

    public function query(): Builder
    {
        return SantriAttendance::query()
            ->with(['santri.tpqClass'])
            ->whereBetween('attendance_date', [
                $this->startDate,
                $this->endDate,
            ])
            ->when($this->classId, function ($query) {
                $query->whereHas('santri', function ($q) {
                    $q->where('tpq_class_id', $this->classId);
                });
            })
            ->orderBy('attendance_date')
            ->orderBy('santri_id');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nomor Santri',
            'Nama Santri',
            'Kelas TPQ/TPA',
            'Status Kehadiran',
            'Catatan',
        ];
    }

    public function map($attendance): array
    {
        $statusLabels = [
            'present' => 'Hadir',
            'sick' => 'Sakit',
            'permission' => 'Izin',
            'absent' => 'Alpa',
        ];

        return [
            $attendance->attendance_date?->format('d/m/Y'),
            $attendance->santri?->registration_number ?? '-',
            $attendance->santri?->name ?? '-',
            $attendance->santri?->tpqClass?->name ?? '-',
            $statusLabels[$attendance->status] ?? $attendance->status,
            $attendance->notes ?? '-',
        ];
    }
}
