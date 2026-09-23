<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            
            // کد اموال؛ یونیک و دارای ایندکس سریع برای جستجوهای برق‌آسا
            $table->string('property_code')->unique()->index();
            
            // مشخصات پلمپ‌های دوگانه امنیتی
            $table->string('primary_seal_code')->nullable()->comment('پلمپ شماره یک');
            $table->string('secondary_seal_code')->nullable()->comment('پلمپ شماره دو');
            
            // برچسب اختصاصی سامانه القارعه
            $table->string('label_code')->nullable()->comment('برچسب اختصاصی القارعه');
            
            // تاریخچه‌ها
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable()->index();
            
            // وضعیت سلامت سیستم (healthy / warning / critical)
            $table->enum('health_status', ['healthy', 'warning', 'critical'])
                  ->default('healthy')
                  ->index();
            
            // توضیحات برای همگام‌سازی آینده با Active Directory یا یادداشت ادمین
            $table->text('description')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // مبادا شیطنت بشه و کیسی بی‌هوا از دیتابیس بپره!
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('computers');
    }
};
