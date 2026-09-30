<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $query = Computer::query();

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('property_code', 'like', "%{$search}%")
                    ->orWhere('item_title', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('label_code', 'like', "%{$search}%")
                    ->orWhere('primary_seal_code', 'like', "%{$search}%")
                    ->orWhere('secondary_seal_code', 'like', "%{$search}%");
            });
        }

        return response()->json($query->latest('id')->get());
    }

    public function store(Request $request)
    {
        $computer = Computer::create($this->validateData($request));

        return response()->json([
            'status'  => 'success',
            'message' => 'سیستم جدید در سامانه القارعه ثبت شد 🚀',
            'data'    => $computer,
        ], 201);
    }

    public function show(Computer $computer)
    {
        return response()->json($computer);
    }

    public function update(Request $request, Computer $computer)
    {
        $computer->update($this->validateData($request, $computer->id));

        return response()->json([
            'status'  => 'success',
            'message' => 'اطلاعات با موفقیت به‌روزرسانی شد ✅',
            'data'    => $computer->fresh(),
        ]);
    }

    public function destroy(Computer $computer)
    {
        $computer->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'هدف از رده خارج و از سامانه حذف شد 💀',
        ]);
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $unique = $id
            ? "unique:computers,property_code,{$id}"
            : 'unique:computers,property_code';

        return $request->validate([
            'property_code'       => ['required', 'string', 'max:100', $unique],
            'item_title'          => ['nullable', 'string', 'max:255'],
            'user_name'           => ['nullable', 'string', 'max:255'],
            'label_code'          => ['nullable', 'string', 'max:100'],
            'primary_seal_code'   => ['nullable', 'string', 'max:100'],
            'secondary_seal_code' => ['nullable', 'string', 'max:100'],
            'last_service_date'   => ['nullable', 'string', 'max:20'],
            'next_service_date'   => ['nullable', 'string', 'max:20'],
            'health_status'       => ['required', 'in:healthy,warning,critical'],
            'description'         => ['nullable', 'string'],
        ]);
    }
}
