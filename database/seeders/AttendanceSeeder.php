<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $periodeMulai = Carbon::create(2026, 6, 26);
        $periodeSelesai = Carbon::create(2026, 7, 25);

        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Assign hari libur tetap secara acak per karyawan (1x di awal, beda-beda tiap orang)
            $hariLiburKaryawan = rand(0, 6); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu

            $tanggal = $periodeMulai->copy();

            while ($tanggal->lte($periodeSelesai)) {
                $isLibur = $tanggal->dayOfWeek === $hariLiburKaryawan;

                if ($isLibur) {
                    Attendance::create([
                        'employee_id' => $employee->employee_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_kerja' => 0,
                        'keterangan' => 'OFF',
                    ]);
                    $tanggal->addDay();
                    continue;
                }

                // Random kejadian: 75% masuk normal, 15% telat, 10% absen (jam_kerja = 0)
                $roll = rand(1, 100);

                if ($roll <= 10) {
                    // Absen
                    Attendance::create([
                        'employee_id' => $employee->employee_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_kerja' => 0,
                        'keterangan' => null,
                    ]);
                } elseif ($roll <= 25) {
                    // Telat -> disimulasikan sebagai jam kerja kurang dari 8 (6.0 - 7.5 jam)
                    $jamKerja = round(rand(60, 75) / 10, 1);

                    Attendance::create([
                        'employee_id' => $employee->employee_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_kerja' => $jamKerja,
                        'keterangan' => null,
                    ]);
                } else {
                    // Masuk normal, 8 jam kerja
                    Attendance::create([
                        'employee_id' => $employee->employee_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_kerja' => 8,
                        'keterangan' => null,
                    ]);
                }

                $tanggal->addDay();
            }
        }
    }
}