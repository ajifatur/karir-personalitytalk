<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'score'
    ];
    
    /**
     * Get the skill that owns the user skill.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
