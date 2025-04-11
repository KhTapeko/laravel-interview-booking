<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // ✅ 允許可填寫欄位（避免 mass assignment 錯誤）
    protected $fillable = [
        'title',
        'company',
        'location',
        'description',
        'interview_type',
    ];
}
