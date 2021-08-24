<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stifin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stifin';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = ['name', 'birthdate', 'test', 'test_at'];

    /**
     * Get the test record associated with the stifin.
     */
    public function tests(){
        return $this->hasOne('App\StifinTest', 'id_st', 'test');
    }
}
