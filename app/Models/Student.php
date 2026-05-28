<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'email',
        'contact_no',
        'image_path',
        'degree_id',
        'user_account_id',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class, 'degree_id', 'degree_id');
    }

    public function courses(){
        return $this->belongsToMany(Course::class, 'course__students' , 'student_id', 'course_id');
    }

    public function userAccount(){
        return $this->belongsTo(UserAccounts::class, 'user_account_id', 'id');
    }

    public function getEmailAttribute($value)
    {
        return $value ?? $this->userAccount?->email;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}
