<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'duration_days',
        'school_id',
        'sector_id',
        'morning_schedule',
        'afternoon_schedule',
        'staff_id'
    ];

    protected $casts = [
        'morning_schedule' => 'array',
        'afternoon_schedule' => 'array'
    ];

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relationship with Sector
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    // Relationship with Course Schedule
    public function schedule()
    {
        return $this->hasOne(CourseSchedule::class);
    }

    // Relationship with Course Batches
    public function batches()
    {
        return $this->hasMany(CourseBatch::class);
    }

    // Relationship with Staff (User)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    
    // Relationship with users (students enrolled in the course)
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withTimestamps()
            ->withPivot('status', 'enrolled_at');
    }

    // Get active batches
    public function activeBatches()
    {
        return $this->batches()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // Get upcoming batches
    public function upcomingBatches()
    {
        return $this->batches()
            ->where('start_date', '>', now());
    }

    // Get completed batches
    public function completedBatches()
    {
        return $this->batches()
            ->where('end_date', '<', now());
    }

    // Get total enrolled students across all batches
    public function getTotalEnrolledStudentsAttribute()
    {
        return $this->batches()
            ->withCount('enrollments')
            ->get()
            ->sum('enrollments_count');
    }

    // Get available slots across all active batches
    public function getAvailableSlotsAttribute()
    {
        $activeBatches = $this->activeBatches()->get();
        $totalSlots = $activeBatches->sum('max_students');
        $totalEnrolled = $activeBatches->sum(function($batch) {
            return $batch->enrollments()->count();
        });
        
        return $totalSlots - $totalEnrolled;
    }

    // Format schedule for display
    public function getFormattedScheduleAttribute()
    {
        $morning = $this->morning_schedule;
        $afternoon = $this->afternoon_schedule;

        return sprintf(
            'Morning: %s - %s, Afternoon: %s - %s',
            $morning['in'] ?? 'N/A',
            $morning['out'] ?? 'N/A',
            $afternoon['in'] ?? 'N/A',
            $afternoon['out'] ?? 'N/A'
        );
    }

    // Scope for searching courses
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    // Scope for filtering by sector
    public function scopeBySector($query, $sectorId)
    {
        return $query->where('sector_id', $sectorId);
    }

    // Scope for filtering by school
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    // Scope for courses with available slots
    public function scopeWithAvailableSlots($query)
    {
        return $query->whereHas('batches', function($query) {
            $query->whereRaw('max_students > (SELECT COUNT(*) FROM enrollments WHERE batch_id = course_batches.id)');
        });
    }
}
