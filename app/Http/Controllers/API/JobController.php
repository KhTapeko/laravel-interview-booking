<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // job 搜尋
    public function index(Request $request)
    {
        $query = Job::query();
    
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }
    
        return $query->latest()->take(6)->get();
    }

    // 顯示job所有資料+ 應徵人數
    public function show($id)
    {
        $job = Job::withCount('applicants')->findOrFail($id);
        $user = Auth::user();
    
        $hasApplied = false;
        if ($user && in_array($user->role, ['candidate', 'employee'])) {
            $hasApplied = $job->applicants()->where('user_id', $user->id)->exists();
        }
    
        // 加到回傳資料中
        $job->has_applied = $hasApplied;
    
        return $job;
    }
    
    // 應徵job
    public function apply($id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::user();

        // ✅ 允許 candidate 和 employee 應徵
        if (!in_array($user->role, ['candidate', 'employee'])) {
            return response()->json(['message' => '只有應徵者或員工可以應徵'], 403);
        }

        // 檢查是否已經應徵過
        if ($job->applicants()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => '你已經應徵過此職缺'], 409);
        }

        // 寫入應徵
        $job->applicants()->attach($user->id, ['status' => 'applied']);

        return response()->json(['message' => '應徵成功']);
    }

}
