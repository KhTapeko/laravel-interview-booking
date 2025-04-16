<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'interviewer_id',
        'interview_time',
        'max_applicants',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
