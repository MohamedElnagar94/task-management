<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'priority',
        'project_id',
        'user_id'
    ];

    public function project(){
        return $this->belongsTo("\App\Project","project_id","id");
    }
}
