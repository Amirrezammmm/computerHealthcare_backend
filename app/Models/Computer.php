<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Computer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_code',
        'item_title',          // عنوان کالا
        'user_name',           // نام کاربر متصدی
        'primary_seal_code',   // پلمپ اول
        'secondary_seal_code', // پلمپ دوم
        'label_code',
        'last_service_date',   // تاریخ شمسی به صورت رشته
        'next_service_date',   // تاریخ شمسی به صورت رشته
        'health_status',
        'description',
        'project_id',          // جهت اتصال به سیستم چند پروژه‌ای القارعه
    ];

    // کست تاریخ میلادی رو حذف کردیم چون تاریخ‌ها به صورت شمسی و رشته‌ای ذخیره میشن
    protected $casts = [];

    // رابطه با پروژه
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
