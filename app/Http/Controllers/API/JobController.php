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

    // 所有job 
    public function listAll(Request $request)
    {
        $query = Job::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%");
            });
        }

        return $query->latest()->get(); // 取得所有職缺，不限筆數
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

    // 新增職缺
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'interview_type' => 'required|in:individual,group',
            'duration_minutes' => 'required|integer|min:30',
            'target_applicants' => 'nullable|integer|min:1',
            'salary_min' => 'required|integer|min:1',
            'salary_max' => 'required|integer|min:1',
            'salary_note' => 'nullable|string|max:255',
            'requirement' => 'nullable|string',
            'experience_required' => 'required|string|max:255',
            'education_level' => 'required|string|max:255',
            'benefits' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'job_type' => 'required|string|max:255',
            'work_schedule' => 'nullable|string|max:255',
            'remote_option' => 'required|boolean',
            'travel_required' => 'required|boolean',
        ]);

        // 這裡加上登入者 ID（created_by）
        $validated['created_by'] = Auth::id();

        $job = Job::create($validated);

        return response()->json($job, 201);
    }

    // 更新職缺
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::user();

        // ✅ 權限判斷：只有 admin 或職缺建立者可以修改
        if (!($user->role === 'admin' || ($user->role === 'employee' && $job->created_by === $user->id))) {
            return response()->json(['message' => '你沒有權限修改此職缺'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'interview_type' => 'required|in:individual,group',
            'duration_minutes' => 'required|integer|min:30',
            'target_applicants' => 'nullable|integer|min:1',
            'salary_min' => 'required|integer|min:1',
            'salary_max' => 'required|integer|min:1',
            'salary_note' => 'nullable|string|max:255',
            'requirement' => 'nullable|string',
            'experience_required' => 'required|string|max:255',
            'education_level' => 'required|string|max:255',
            'benefits' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'job_type' => 'required|string|max:255',
            'work_schedule' => 'nullable|string|max:255',
            'remote_option' => 'required|boolean',
            'travel_required' => 'required|boolean',
        ]);

        $job->update($validated);

        return response()->json(['message' => '職缺更新成功', 'job' => $job]);
    }

    // 刪除職缺
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::user();
    
        if (
            $user->role === 'admin' ||
            ($user->role === 'employee' && $job->created_by === $user->id)
        ) {
            $job->delete();
            return response()->json(['message' => '職缺已刪除']);
        }
    
        return response()->json(['message' => '你沒有權限刪除此職缺'], 403);
    }
    
}   
