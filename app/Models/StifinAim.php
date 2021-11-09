<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StifinAim extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stifin_aim';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_sa';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = ['aim'];
}
