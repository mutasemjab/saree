<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery
{
    use Exportable;

    protected $search;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function query()
    {
        if ($this->search) {
            return User::query()
                ->whereRaw('CONCAT_WS(" ", `first_name`, `last_name`, `email`, `phone`) like ?', ['%' . $this->search . '%']);
        } else {
            return User::query();
        }
    }
}
