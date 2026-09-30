<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('computers', function (Blueprint $table) {
        $table->string('item_title')->nullable()->after('property_code'); // عنوان کالا
        $table->string('user_name')->nullable()->after('item_title');     // کاربر سیستم

        // تاریخ‌ها رشته‌ای شدن تا تاریخ شمسی (1403/01/17) بدون دردسر ذخیره بشه
        $table->string('last_service_date', 20)->nullable()->change();
        $table->string('next_service_date', 20)->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('computers', function (Blueprint $table) {
        $table->dropColumn(['item_title', 'user_name']);
    });
}

};
