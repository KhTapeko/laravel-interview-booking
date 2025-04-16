<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return response()->json(
            $query->orderBy('created_at', 'desc')->get()->makeVisible([
                'gender', 'birthday', 'created_at'
            ])
        );
    }

    // 取得單一使用者資料
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    // admin 更新單一使用者資料
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

    // admin 刪除使用者
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

    // 使用者編輯自己的資料
    public function selfUpdate(Request $request)
    {
        $user = $request->user();
    
        $rules = [
            'name' => 'required|string|max:50',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'required|date|before:today',
            'email' => "required|email|unique:users,email,{$user->id}",
        ];
    
        // ✅ 若真的要改密碼（有填），才驗證密碼欄位
        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|string|min:6|confirmed';
        }
    
        $validated = $request->validate($rules);
    
        // ✅ 更新基本欄位
        $user->update([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'birthday' => $validated['birthday'],
            'email' => $validated['email'],
        ]);
    
        // ✅ 密碼更新流程：用 $request->filled() 確保安全
        if ($request->filled('new_password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json([
                    'errors' => ['current_password' => ['現有密碼不正確']]
                ], 422);
            }
    
            $user->password = $request->input('new_password');
            $user->save();
        }
    
        return response()->json(['message' => '個人資料更新成功']);
    }
    
    

    // 使用者刪除自己帳號
    public function selfDestroy(Request $request)
    {
        $user = $request->user();
    
        // ✅ 預先記錄 ID，避免刪除後找不到
        $id = $user->id;
    
        // ✅ 登出先處理，清除 session/cookie
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        // ✅ 刪除帳號
        User::where('id', $id)->delete();
    
        // ✅ 成功回應（不再 serialize 已刪除的 $user）
        return response()->json(['message' => '帳號已刪除']);
    }
    
}
