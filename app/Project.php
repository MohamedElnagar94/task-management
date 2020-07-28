<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function userName(){
        return $this->belongsTo("\App\User","user_id");
    }

    public function tasks(){
        return $this->hasMany("\App\Task","project_id");
    }
}
