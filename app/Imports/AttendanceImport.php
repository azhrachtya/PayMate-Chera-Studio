<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AttendanceImport implements ToCollection, SkipsEmptyRows
{
    public array $errors = [];
    public int $berhasil = 0;
    public int $dilewati = 0;

    public function __construct(private string $storeId)
    {
    }

    public function collection(Collection $rows)
    {
        // Baris index 2 (baris ke-3 di Excel) = header: Employe_id | Nama | Jabatan | tanggal-tanggal...
        $headerRow = $rows->get(2);

        if (!$headerRow) {
            $this->errors[] = 'Baris header (baris ke-3) tidak ditemukan di file Excel.';
            return;
        }

        $kolomTanggal = [];
        foreach ($headerRow as $idx => $val) {
            if ($idx < 3) continue;

            if ($val instanceof \DateTimeInterface) {
                $kolomTanggal[$idx] = Carbon::instance($val)->format('Y-m-d');
            } elseif (is_numeric($val)) {
                $kolomTanggal[$idx] = Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)
                )->format('Y-m-d');
            } elseif (is_string($val) && trim($val) !== '' && strtotime($val)) {
                $kolomTanggal[$idx] = Carbon::parse($val)->format('Y-m-d');
            }
        }

        if (empty($kolomTanggal)) {
            $this->errors[] = 'Tidak ada kolom tanggal yang terbaca di baris header.';
            return;
        }

        for ($i = 3; $i < $rows->count(); $i++) {
            $row = $rows->get($i);
            $baris = $i + 1;

            $employeeId = $row[0] ?? null;
            if ($employeeId === null || $employeeId === '' || !is_numeric($employeeId)) {
                continue;
            }

            $employee = Employee::where('employee_id', (int) $employeeId)
                ->where('store_id', $this->storeId)
                ->first();

            if (!$employee) {
                $this->errors[] = "Baris {$baris}: employee_id {$employeeId} tidak ditemukan di toko yang dipilih, dilewati.";
                $this->dilewati++;
                continue;
            }

            foreach ($kolomTanggal as $idx => $tanggal) {
                $value = $row[$idx] ?? null;

                if ($value === null || $value === '') {
                    continue;
                }

                if (is_numeric($value)) {
                    Attendance::updateOrCreate(
                        ['employee_id' => $employee->employee_id, 'tanggal' => $tanggal],
                        ['jam_kerja' => (float) $value, 'keterangan' => null]
                    );
                } else {
                    Attendance::updateOrCreate(
                        ['employee_id' => $employee->employee_id, 'tanggal' => $tanggal],
                        ['jam_kerja' => 0, 'keterangan' => trim((string) $value)]
                    );
                }

                $this->berhasil++;
            }
        }
    }
}