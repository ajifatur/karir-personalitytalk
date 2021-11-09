<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'temp';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_temp';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'email',
        'json',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
