<?php

namespace App\Exports;

use App\Pelamar;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PelamarExport implements FromView
{
	use Exportable;

    /**
     * Create a new message instance.
     *
     * int id karyawan
     * @return void
     */
    public function __construct($pelamar)
    {
        $this->pelamar = $pelamar;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	// View
    	return view('pelamar/excel', [
    		'pelamar' => $this->pelamar
    	]);
    }
}
