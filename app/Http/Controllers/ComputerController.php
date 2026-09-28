<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ComputerController extends Controller
{
    /**
     * رادار پایش و لیست سیستم‌ها با قابلیت فیلتر چندمنظوره
     */
    public function index(Request $request): JsonResponse
    {
        $query = Computer::query();

        // سیستم جستجوی پیشرفته بر اساس کد اموال، برچسب القارعه یا شماره‌های پلمپ
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('property_code', 'like', "%{$search}%")
                  ->orWhere('label_code', 'like', "%{$search}%")
                  ->orWhere('primary_seal_code', 'like', "%{$search}%")
                  ->orWhere('secondary_seal_code', 'like', "%{$search}%");
            });
        }

        // فیلتر بر اساس وضعیت سلامت
        if ($status = $request->query('status')) {
            $query->where('health_status', $status);
        }

        // صفحه‌بندی ۱۲تایی تمیز
        $computers = $query->latest()->paginate(12);

        return response()->json($computers);
    }

    /**
     * ثبت کیس جدید در سامانه
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_code'       => 'required|string|unique:computers,property_code',
            'primary_seal_code'   => 'nullable|string',
            'secondary_seal_code' => 'nullable|string',
            'label_code'          => 'nullable|string',
            'last_service_date'   => 'nullable|date',
            'next_service_date'   => 'nullable|date',
            'health_status'       => 'required|in:healthy,warning,critical',
            'description'         => 'nullable|string',
        ]);

        $computer = Computer::create($validated);

        return response()->json([
            'message' => 'سیستم با موفقیت به پایگاه سلامت افزوده شد!',
            'data'    => $computer
        ], 201);
    }

    /**
     * مشاهده تکی مشخصات یک کیس
     */
    public function show(Computer $computer): JsonResponse
    {
        return response()->json($computer);
    }

    /**
     * ویرایش اطلاعات کیس
     */
    public function update(Request $request, Computer $computer): JsonResponse
    {
        $validated = $request->validate([
            'property_code'       => 'required|string|unique:computers,property_code,' . $computer->id,
            'primary_seal_code'   => 'nullable|string',
            'secondary_seal_code' => 'nullable|string',
            'label_code'          => 'nullable|string',
            'last_service_date'   => 'nullable|date',
            'next_service_date'   => 'nullable|date',
            'health_status'       => 'required|in:healthy,warning,critical',
            'description'         => 'nullable|string',
        ]);

        $computer->update($validated);

        return response()->json([
            'message' => 'مشخصات کیس با موفقیت به‌روزرسانی شد.',
            'data'    => $computer
        ]);
    }

    /**
     * حذف نرم (Soft Delete) برای عدم پاک شدن سوابق
     */
    public function destroy(Computer $computer): JsonResponse
    {
        $computer->delete();

        return response()->json([
            'message' => 'کیس مورد نظر بایگانی شد (Soft Delete).'
        ]);
    }
}
