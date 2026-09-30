<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'GAPGPTMASKTOKENyhksd2cqltoX0X',
            'password' => 'GAPGPTMASKTOKENyhksd2cqltoX1X',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['نام کاربری یا رمز عبور اشتباه است.'],
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'حساب کاربری شما غیرفعال شده است. با مدیریت تماس بگیرید.'
            ], 403);
        }

        // احراز هویت مبتنی بر Session/Cookie برای امنیت بالا
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود.',
            'user' => $user->load('projects:id,name,code'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'خروج با موفقیت انجام شد.']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('projects:id,name,code'),
        ]);
    }
}
