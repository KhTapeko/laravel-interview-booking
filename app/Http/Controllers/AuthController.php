<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidate', // 預設為最低權限
        ]);

        // Auth::login($user); // 可移除自動登入（視需求）

        return response()->json(['message' => '註冊成功'], 201);
    }
}
