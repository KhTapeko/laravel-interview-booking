<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 取得所有使用者資料（支援搜尋）
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => '無權限'], 403);
        }

        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    // 更新單一使用者資料
    public function update(Request $request, $id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            return response()->json(['message' => '無權限'], 403);
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'required|date|before:today',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|in:admin,employee,candidate',
        ]);

        $user->update($validated);

        return response()->json(['message' => '更新成功']);
    }

    // 刪除使用者
    public function destroy($id)
    {
        $admin = Auth::user();
        if (!$admin || $admin->role !== 'admin') {
            return response()->json(['message' => '無權限'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => '刪除成功']);
    }
}
