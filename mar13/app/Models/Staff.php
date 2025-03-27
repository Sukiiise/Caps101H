<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'school_id',
        'position',
        'department',
        'employment_status',
        'date_hired',
        'employee_id',
        'qualifications',
        'responsibilities',
        'is_active'
    ];
    protected $casts = [
        'date_hired' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scope for active staff
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for specific position
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    // Get full name through user relationship
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    // Get email through user relationship
    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    // Get contact number through user relationship
    public function getContactNumberAttribute()
    {
        return $this->user->contact_number;
    }

    // Check if staff is active
    public function isActive()
    {
        return $this->is_active;
    }

    // Deactivate staff
    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    // Activate staff
    public function activate()
    {
        $this->update(['is_active' => true]);
    }
}
