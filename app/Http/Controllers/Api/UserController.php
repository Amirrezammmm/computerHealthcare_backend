<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // لیست کل کاربران به همراه پروژه‌های تخصیص‌یافته
    public function index()
    {
        $users = User::with('projects:id,name,code')->latest()->get();
        return response()->json($users);
    }

    // ایجاد کاربر جدید
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,username',
            'password'   => 'required|string|min:6',
            'role'       => ['required', Rule::in(['manager', 'admin', 'user'])],
            'project_ids'=> 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'is_active' => true,
        ]);

        if (!empty($validated['project_ids'])) {
            $user->projects()->sync($validated['project_ids']);
        }

        return response()->json([
            'message' => 'کاربر با موفقیت ایجاد شد.',
            'user'    => $user->load('projects:id,name,code'),
        ], 201);
    }

    // تغییر وضعیت فعال / غیرفعال
    public function toggleStatus(User $user)
    {
        // جلوگیری از غیرفعال کردن کاربر ارشد توسط خودش
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'امکان غیرفعال‌سازی حساب کاربری جاری وجود ندارد.'], 400);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'message'   => 'وضعیت کاربر به‌روزرسانی شد.',
            'is_active' => $user->is_active,
        ]);
    }

    // بازنشانی رمز عبور
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'رمز عبور با موفقیت بازنشانی شد.']);
    }

    // تخصیص پروژه‌ها به کاربر (مخصوص نقش Admin)
    public function syncProjects(Request $request, User $user)
    {
        $request->validate([
            'project_ids'   => 'array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        $user->projects()->sync($request->project_ids ?? []);

        return response()->json([
            'message'  => 'پروژه‌های کاربر با موفقیت به‌روزرسانی شدند.',
            'projects' => $user->projects,
        ]);
    }
}
