<?php

namespace App\Models;

class UserAttribute extends \Ajifatur\FaturHelper\Models\UserAttribute
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'office_id', 'position_id', 'vacancy_id', 'birthdate', 'birthplace', 'gender', 'country_code', 'dial_code', 'phone_number', 'address', 'identity_number', 'religion', 'relationship', 'latest_education', 'job_experience', 'start_date', 'end_date'
    ];
}
