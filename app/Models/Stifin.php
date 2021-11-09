<?php

namespace App\Models;

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
        return $this->hasOne(StifinTest::class, 'id_st', 'test');
    }

    /**
     * Get the HRD record associated with the stifin.
     */
    public function hrd(){
        return $this->hasOne(HRD::class, 'id_hrd', 'hrd_id');
    }
}
