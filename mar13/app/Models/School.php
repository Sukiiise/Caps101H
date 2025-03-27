<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street_number',
        'barangay',
        'city',
        'province',
        'contact_number',
        'logo_path'
    ];

    public function getFullAddressAttribute()
    {
        return "{$this->street_number}, {$this->barangay}, {$this->city}, {$this->province}";
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'school_user')->withPivot('role', 'is_active')->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'school_user')
                    ->withPivot('role', 'is_active')
                    ->wherePivot('role', 'student')
                    ->wherePivot('is_active', true);
    }

    // Add this accessor for easy logo URL access
    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? asset($this->logo_path) : null;
    }
    
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    // Get active staff
    public function activeStaff()
    {
        return $this->staff()->where('is_active', true);
    }

    public function getStaffByPosition($position)
    {
        return $this->staff()->where('position', $position)->where('is_active', true);
    }
}
