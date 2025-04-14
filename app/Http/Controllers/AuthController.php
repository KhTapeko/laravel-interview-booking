<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $cred = $r->validate(['email'=>'required|email','password'=>'required']);
        if (!Auth::attempt($cred, $r->boolean('remember'))) {
            return response()->json(['message'=>'Invalid'], 422);
        }
        $r->session()->regenerate();
        return response()->json(Auth::user());
    }

    public function me(Request $request)
    {
        return response()
            ->json($request->user()->only(['id','name','email','role']))
            ->header('Cache-Control', 'no-store');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return response()->noContent();
    }
}
