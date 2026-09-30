<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // تبدیل ایمیل به فیلد اختیاری یا اضافه کردن نام کاربری
            $table->string('username')->unique()->after('name');
            $table->enum('role', ['manager', 'admin', 'user'])->default('user')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_seen_at')->nullable()->after('is_active');
            
            // اگه ایمیل اجباریه، نال‌پذیرش می‌کنیم تا گیر نده
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'is_active', 'last_seen_at']);
        });
    }
};
