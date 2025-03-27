<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseBatch extends Model
{
    protected $fillable = [
        'course_id',
        'batch_name',
        'start_date',
        'end_date',
        'max_students',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function enrollments()
    {
        return $this->hasMany(BatchEnrollment::class, 'batch_id');
    }
}
