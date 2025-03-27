<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'email',
        'password',
        'usertype',
        'contact_number',
        'street_address',
        'barangay',
        'municipality',
        'province',
        'gender',
        'birthdate',
        'civil_status',
        'nationality',
        'classification'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'birthdate'
    ];

    public function batchEnrollments()
    {
        return $this->hasMany(BatchEnrollment::class);
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'enrollment_date' => 'date',
        'completion_date' => 'date'
    ];

    public function getNameAttribute()
    {
        return "{$this->firstname} " . ($this->middlename ? $this->middlename . ' ' : '') . "{$this->lastname}";
    }

    public function enrollments()
    {
        return $this->hasMany(BatchEnrollment::class);
    }

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scopes for students
    public function scopeStudents($query)
    {
        return $query->where('usertype', 'student');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('usertype', 'student')
                    ->where('status', $status);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('usertype', 'student')
                    ->where('course_id', $courseId);
    }

    // Helper methods
    public function isStudent()
    {
        return $this->usertype === 'student';
    }

    public function isCompetent()
    {
        return $this->status === 'competent';
    }

    public function markAsCompetent()
    {
        $this->update([
            'status' => 'competent',
            'completion_date' => now()
        ]);
    }

    public function markAsDropped()
    {
        $this->update([
            'status' => 'dropped'
        ]);
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    
    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_user')
                    ->withPivot('role', 'is_active');
    }

    public function staffPositions()
    {
        return $this->hasMany(Staff::class);
    }

    public function isStaffAt(School $school)
    {
        return $this->staffPositions()
                    ->where('school_id', $school->id)
                    ->where('is_active', true)
                    ->exists();
    }

    public function getCurrentStaffPosition()
    {
        return $this->staffPositions()
                    ->where('is_active', true)
                    ->first();
    }
}
