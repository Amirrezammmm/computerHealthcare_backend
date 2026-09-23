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
        'primary_seal_code',   // پلمپ اول
        'secondary_seal_code', // پلمپ دوم
        'label_code',
        'last_service_date',
        'next_service_date',
        'health_status',
        'description',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'next_service_date' => 'date',
    ];
}
