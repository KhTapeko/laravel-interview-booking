<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'gender', 'birthday', 'role', 'password'];
    protected $visible = ['id', 'name', 'email', 'role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ✅ 多對多關聯：使用者應徵了哪些職缺
     */
    public function appliedJobs()
    {
        return $this->belongsToMany(Job::class)
                    ->withTimestamps()
                    ->withPivot(['status']);
    }

    /**
     * ✅ 如果你還有建立的職缺
     * 若使用者為 admin / employee，也可建立職缺
     * 從創建者 尋找職缺
     */
    public function createdJobs()
    {
        return $this->hasMany(Job::class, 'created_by');
    }
}
