<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $fillable = [
        'course_id',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out'
    ];

    protected $casts = [
        'morning_in' => 'datetime:H:i',
        'morning_out' => 'datetime:H:i',
        'afternoon_in' => 'datetime:H:i',
        'afternoon_out' => 'datetime:H:i',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
