<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'location',
        'description',
        'interview_type',
        'duration_minutes',
        'target_applicants',
        'salary_min',
        'salary_max',
        'salary_note',
        'requirement',
        'benefits',
        'contact_info',
        'created_by',
    ];

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, TimeSlot::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
