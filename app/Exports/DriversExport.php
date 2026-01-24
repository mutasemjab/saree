<?php

namespace App\Exports;

use App\Models\Driver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DriversExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Driver::with('city');

        // Apply same filters as index page
        if ($this->request) {
            if ($this->request->filled('search')) {
                $searchTerm = $this->request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('id', 'LIKE', "%{$searchTerm}%");
                });
            }

            if ($this->request->filled('status')) {
                if ($this->request->status === 'active') {
                    $query->where('activate', 1);
                } elseif ($this->request->status === 'inactive') {
                    $query->where('activate', 2);
                }
            }

            if ($this->request->filled('city_id')) {
                $query->where('city_id', $this->request->city_id);
            }

            if ($this->request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $this->request->date_from);
            }

            if ($this->request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $this->request->date_to);
            }
        }

        return $query->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('messages.id'),
            __('messages.name'),
            __('messages.phone'),
            __('messages.identity_number'),
            __('messages.plate_number'),
            __('messages.Car type'),
            __('messages.City'),
            __('messages.status'),
            __('messages.created_at'),
        ];
    }

    /**
     * @param mixed $driver
     * @return array
     */
    public function map($driver): array
    {
        return [
            $driver->id,
            $driver->name,
            $driver->phone,
            $driver->identity_number,
            $driver->plate_number ?? '-',
            $driver->car_type == 1 ? __('messages.car') : __('messages.motosycle'),
            $driver->city->name ?? __('messages.not_available'),
            $driver->activate == 1 ? __('messages.active') : __('messages.inactive'),
            $driver->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
