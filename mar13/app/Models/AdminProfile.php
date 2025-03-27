<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    protected $fillable = [
        'user_id',
        'department',
        'role'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
