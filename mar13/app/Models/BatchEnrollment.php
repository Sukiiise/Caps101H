<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchEnrollment extends Model
{
    protected $fillable = [
        'batch_id',
        'user_id',
        'registration_status',
        'delivery_mode',
        'provider_type',
        'region',
        'province',
        'congressional_district',
        'municipality',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function courseBatch()
    {
        return $this->belongsTo(CourseBatch::class, 'batch_id');
    }

    public function batchEnrollments()
    {
        return $this->hasMany(BatchEnrollment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
