<?php

namespace App\Exports;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class KaryawanExport implements FromView
{
	use Exportable;

    /**
     * Create a new message instance.
     *
     * @param  object $employees
     * @return void
     */
    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	// View
    	return view('admin.employee.excel', [
    		'employees' => $this->employees
    	]);
    }
}
