<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        $query = User::with('city');

        // Apply same filters as index page
        if ($this->request) {
            if ($this->request->filled('search')) {
                $search = $this->request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            if ($this->request->filled('status')) {
                $query->where('activate', $this->request->status);
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
            __('messages.City'),
            __('messages.status'),
            __('messages.latitude'),
            __('messages.longitude'),
            __('messages.created_at'),
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->phone,
            $user->city->name ?? __('messages.not_available'),
            $user->activate == 1 ? __('messages.active') : __('messages.inactive'),
            $user->lat ?? '-',
            $user->lng ?? '-',
            $user->created_at->format('Y-m-d H:i:s'),
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
