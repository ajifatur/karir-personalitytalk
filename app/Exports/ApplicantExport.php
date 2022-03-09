<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ApplicantExport implements FromView
{
	use Exportable;

    /**
     * Create a new message instance.
     *
     * @param  object $applicants
     * @return void
     */
    public function __construct($applicants)
    {
        $this->applicants = $applicants;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	// View
    	return view('admin/applicant/excel', [
    		'applicants' => $this->applicants
    	]);
    }
}
