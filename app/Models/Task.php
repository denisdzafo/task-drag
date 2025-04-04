<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'priority',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
