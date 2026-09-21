<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // UMR 2026 acuan per kota, dipakai sebagai gaji_pokok fulltime
        $umrByStore = [
            'DPK01' => 5522662,
            'JKT01' => 5729876,
            'BKS01' => 5999422,
        ];

        $StoreManager = Position::where('position_name', 'Store Manager')->first();
        $AssistantStoreManager = Position::where('position_name', 'Assistant Store Manager')->first();
        $SeniorTeaBarista = Position::where('position_name', 'Senior Tea Barista')->first();
        $TeaBarista = Position::where('position_name', 'Tea Barista')->first();
        $Parttime = Position::where('position_name', 'Part Time Tea Barista')->first();

        $employees = [
            [
                'store_id' => 'DPK01', 'position_id' => $StoreManager->position_id,
                'name' => 'Andi Saputra', 'employment_type' => 'fulltime',
                'birth_date' => '1990-05-12', 'email' => 'andi.saputra@gmail.com',
                'join_date' => '2023-01-10',
                'salary_rate' => $umrByStore['DPK01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Andi Saputra', 'account_number' => '4567876568001',
                'nik' => '3276011205900001', 'npwp' => '09.123.456.7-412.000',
            ],
            [
                'store_id' => 'DPK01', 'position_id' => $AssistantStoreManager->position_id,
                'name' => 'Sri Wahyuni', 'employment_type' => 'fulltime',
                'birth_date' => '1998-08-20', 'email' => 'sri.wahyuni@gmail.com',
                'join_date' => '2024-03-01',
                'salary_rate' => $umrByStore['DPK01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Sri Wahyuni', 'account_number' => '450875678002',
                'nik' => '3276012008980002', 'npwp' => '09.143.476.7-312.000',
            ],
            [
                'store_id' => 'DPK01', 'position_id' => $Parttime->position_id,
                'name' => 'Rizky Ramadhan', 'employment_type' => 'parttime',
                'birth_date' => '2003-02-14', 'email' => 'rizky.ramadhan@gmail.com',
                'join_date' => '2025-06-15',
                'salary_rate' => (int) round($umrByStore['DPK01'] / 173), 'salary_unit' => 'per_jam',
                'bank_name' => 'BCA', 'account_name' => 'Rizky Ramadhan', 'account_number' => '88765434568003',
                'nik' => '3276011402030003', 'npwp' => '09.123.346.7-983.000',
            ],
            [
                'store_id' => 'JKT01', 'position_id' => $StoreManager->position_id,
                'name' => 'Dewi Lestari', 'employment_type' => 'fulltime',
                'birth_date' => '1988-11-03', 'email' => 'dewi.lestari@gmail.com',
                'join_date' => '2022-09-01',
                'salary_rate' => $umrByStore['JKT01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Dewi Lestari', 'account_number' => '986543456004',
                'nik' => '3171011103880004', 'npwp' => '10.234.567.8-013.000',
            ],
            [
                'store_id' => 'JKT01', 'position_id' => $SeniorTeaBarista->position_id,
                'name' => 'Fajar Nugroho', 'employment_type' => 'fulltime',
                'birth_date' => '1997-07-22', 'email' => 'fajar.nugroho@gmail.com',
                'join_date' => '2023-11-20',
                'salary_rate' => $umrByStore['JKT01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Fajar Nugroho', 'account_number' => '23467432345005',
                'nik' => '3171012207970005', 'npwp' => '97.345.678.9-135.000',
            ],
            [
                'store_id' => 'JKT01', 'position_id' => $Parttime->position_id,
                'name' => 'Nadia Putri', 'employment_type' => 'parttime',
                'birth_date' => '2004-01-30', 'email' => 'nadia.putri@gmail.com',
                'join_date' => '2026-01-05',
                'salary_rate' => (int) round($umrByStore['JKT01'] / 173), 'salary_unit' => 'per_jam',
                'bank_name' => 'BCA', 'account_name' => 'Nadia Putri', 'account_number' => '94567876543006',
                'nik' => '3171013001040006', 'npwp' => '98.456.789.0-246.000',
            ],
            [
                'store_id' => 'BKS01', 'position_id' => $StoreManager->position_id,
                'name' => 'Bagus Prasetyo', 'employment_type' => 'fulltime',
                'birth_date' => '1991-04-18', 'email' => 'bagus.prasetyo@gmail.com',
                'join_date' => '2023-05-02',
                'salary_rate' => $umrByStore['BKS01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Bagus Prasetyo', 'account_number' => '3456787654007',
                'nik' => '3275011804910007', 'npwp' => '11.345.678.9-407.000',
            ],
            [
                'store_id' => 'BKS01', 'position_id' => $AssistantStoreManager->position_id,
                'name' => 'Intan Permatasari', 'employment_type' => 'fulltime',
                'birth_date' => '1999-09-09', 'email' => 'intan.permatasari@gmail.com',
                'join_date' => '2024-08-19',
                'salary_rate' => $umrByStore['BKS01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Intan Permatasari', 'account_number' => '45678908008',
                'nik' => '3275010909990008', 'npwp' => '11.456.789.0-518.000',
            ],
            [
                'store_id' => 'BKS01', 'position_id' => $AssistantStoreManager->position_id,
                'name' => 'Intan Permatasari', 'employment_type' => 'fulltime',
                'birth_date' => '1999-09-09', 'email' => 'intan.permatasari@gmail.com',
                'join_date' => '2024-08-19',
                'salary_rate' => $umrByStore['BKS01'], 'salary_unit' => 'per_bulan',
                'bank_name' => 'BCA', 'account_name' => 'Intan Permatasari', 'account_number' => '45678908008',
                'nik' => '3275010909990008', 'npwp' => '11.456.789.0-518.000',
            ],
            [
                'store_id' => 'DPK01', 'position_id' => $Parttime->position_id,
                'name' => 'Azhra Chandatya Utama', 'employment_type' => 'parttime',
                'birth_date' => '2004-07-23', 'email' => 'azhrachtya@gmail.com',
                'join_date' => '2025-10-11',
                'salary_rate' => (int) round($umrByStore['DPK01'] / 173), 'salary_unit' => 'per_jam',
                'bank_name' => 'BCA', 'account_name' => 'Azhra Chandatya Utama', 'account_number' => '45654345654',
                'nik' => '3275056342020009', 'npwp' => '19.567.900.1-629.000',
            ],
        ];

        foreach ($employees as $e) {
            Employee::updateOrCreate(['email' => $e['email']], $e);
        }
    }
}