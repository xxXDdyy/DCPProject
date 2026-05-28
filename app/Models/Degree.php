<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Degree extends Model
{
    protected $primaryKey = 'degree_id';

    protected $fillable = [
        'degree'
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'degree_id', 'degree_id');
    }
}
