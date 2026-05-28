<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'email',
        'contact_no',
        'image_path',
        'user_account_id',
    ];

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccounts::class, 'user_account_id', 'id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}
