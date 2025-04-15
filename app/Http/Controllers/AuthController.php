<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    // 登入
    public function login(Request $r)
    {
        $cred = $r->validate(['email'=>'required|email','password'=>'required']);
        if (!Auth::attempt($cred, $r->boolean('remember'))) {
            return response()->json(['message'=>'Invalid'], 422);
        }
        $r->session()->regenerate();
        return response()->json(Auth::user());
    }
    // 權限
    public function me(Request $request)
    {
        $user = $request->user();
    
        return response()->json([
            'isLoggedIn' => (bool) $user,
            'user' => $user
                ? $user->only(['id', 'name', 'email', 'role'])
                : null
        ])->header('Cache-Control', 'no-store');
    }
    

    // 登出
    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return response()->noContent();
    }

    // 註冊
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'gender' => 'required|in:male,female,other',
                'birthday' => 'required|date|before:today',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);
    
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'candidate',
                'name' => $request->name,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
            ]);
    
            return response()->json(['message' => '註冊成功'], 201);
    
        } catch (ValidationException $e) {
            // ✅ 保留原本 Laravel 的 422 回傳格式
            throw $e;
        } catch (Throwable $e) {
            // ✅ 只處理非驗證型錯誤
            return response()->json([
                'message' => '後端發生錯誤',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}