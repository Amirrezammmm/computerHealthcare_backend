<?php

namespace App\Imports;

use App\Models\Computer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ComputersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // اگه ردیف کلاً خالی بود یا کد اموال نداشت، ردش کن تا سیستم کرش نکنه
        if (empty($row['property_code'])) {
            return null;
        }

        // پشتیبانی از پلمپ تک یا پلمپ دوگانه در هدر اکسل
        $primarySeal = $row['primary_seal_code'] ?? $row['seal_code'] ?? null;
        $secondarySeal = $row['secondary_seal_code'] ?? null;

        // نرمال‌سازی وضعیت سلامت سیستم
        $validStatuses = ['healthy', 'warning', 'critical'];
        $status = strtolower(trim($row['health_status'] ?? $row['status'] ?? 'healthy'));
        if (!in_array($status, $validStatuses)) {
            $status = 'healthy';
        }

        return Computer::updateOrCreate(
            [
                'property_code' => trim((string)$row['property_code'])
            ],
            [
                'primary_seal_code'   => $primarySeal ? trim((string)$primarySeal) : null,
                'secondary_seal_code' => $secondarySeal ? trim((string)$secondarySeal) : null,
                'label_code'          => !empty($row['label_code']) ? trim((string)$row['label_code']) : null,
                'last_service_date'   => !empty($row['last_service_date']) ? $row['last_service_date'] : (!empty($row['last_service']) ? $row['last_service'] : null),
                'next_service_date'   => !empty($row['next_service_date']) ? $row['next_service_date'] : (!empty($row['next_service']) ? $row['next_service'] : null),
                'health_status'       => $status,
                'description'         => !empty($row['description']) ? trim((string)$row['description']) : 'ورود اولیه از فایل اکسل',
            ]
        );
    }
}
