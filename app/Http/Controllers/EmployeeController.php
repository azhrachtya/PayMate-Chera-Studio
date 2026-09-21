<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Store;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['store', 'position']);

            if ($request->filled('nama')) {
            $query->where('name', 'like', '%' . $request->nama . '%');
        }

            if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

            if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

            if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

            $employees = $query->paginate(20)->withQueryString();
            $stores = Store::all();
            $positions = Position::all();

            return view('employees.index', compact('employees', 'stores', 'positions'));
    }   

    public function create()
    {
        $stores = Store::all();
        $positions = Position::all();
        return view('employees.create', compact('stores', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);
        Employee::create($validated);

        return redirect()->route('employees.index')->with('info', 'Karyawan baru berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        $stores = Store::all();
        $positions = Position::all();
        return view('employees.edit', compact('employee', 'stores', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee->employee_id);
        $employee->update($validated);

        return redirect()->route('employees.index')->with('info', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('info', 'Karyawan berhasil dihapus.');
    }

    public function detail(Payroll $payroll)
    {
        $payroll->load(['employee.store', 'employee.position', 'details', 'period']);
        $employee = $payroll->employee;
        $period = $payroll->period;

        $attendances = $employee->attendances()
            ->whereBetween('tanggal', [$period->tanggal_mulai, $period->tanggal_selesai])
            ->orderBy('tanggal')
            ->get()
            ->map(function ($a) {
                $a->is_weekend = $a->tanggal->isWeekend();
                return $a;
            });

        $summary = [
            'work_days' => $attendances->where('jam_kerja', '>', 0)->count(),
            'work_hours' => $attendances->sum('jam_kerja'),
        ];

        return view('payroll.detail', compact('payroll', 'employee', 'period', 'attendances', 'summary'));
    }

    private function validateEmployee(Request $request, $ignoreId = null): array
{
    if ($request->filled(['birth_date_day', 'birth_date_month', 'birth_date_year'])) {
        $request->merge([
            'birth_date' => "{$request->birth_date_year}-{$request->birth_date_month}-{$request->birth_date_day}",
        ]);
    }

    return $request->validate([
        'store_id' => 'required|exists:stores,store_id',
        'position_id' => [
            'required',
            'exists:positions,position_id',
            function ($attribute, $value, $fail) use ($request) {
                $position = Position::find($value);
                if (!$position) {
                    return; // sudah ditangani oleh rule 'exists' di atas
                }

                $isParttimePosition = $position->position_name === 'Part Time Tea Barista';
                $isParttimeType = $request->input('employment_type') === 'parttime';

                if ($isParttimePosition !== $isParttimeType) {
                    $fail('Jabatan tidak sesuai dengan status kepegawaian yang dipilih.');
                }
            },
        ],
        'name' => 'required|string|max:255',
        'employment_type' => 'required|in:fulltime,parttime',
        'birth_date' => 'required|date',
        'email' => 'required|email|unique:employees,email,' . $ignoreId . ',employee_id',
        'join_date' => 'required|date',
        'salary_rate' => 'required|integer|min:0',
        'salary_unit' => 'required|in:per_bulan,per_jam',
        'bank_name' => 'nullable|string|max:50',
        'account_name' => 'nullable|string|max:100',
        'account_number' => 'nullable|string|max:50',
        'nik' => 'required|string|max:20|unique:employees,nik,' . $ignoreId . ',employee_id',
        'npwp' => 'nullable|string|max:25',
    ]);
}
}