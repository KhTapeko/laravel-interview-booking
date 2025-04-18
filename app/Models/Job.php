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
        'job_type',
        'experience_required',
        'education_level',
        'work_schedule',
        'remote_option',
        'travel_required',
    ];

    // ✅ 創建者（admin or employee） 從職缺 尋找創建者
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ✅ 多對多：被誰應徵了？
    public function applicants()
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps()
                    ->withPivot(['status']);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

}
