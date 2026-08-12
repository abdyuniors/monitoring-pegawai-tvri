<?php

namespace App\Livewire\Security;

use App\Models\Employee;
use App\Models\ExitRecord;
use App\Services\EmployeeExitService;
use App\Services\WorkScheduleService;
use DomainException;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';

    public bool $showExitModal = false;

    public ?int $selectedEmployeeId = null;

    public string $purpose = '';

    public string $destination = '';

    public function render(WorkScheduleService $workScheduleService)
    {
        return view('livewire.security.dashboard', [
            'employees' => $this->searchEmployees(),
            'activeExits' => $this->activeExits(),
            'todayTotal' => $this->todayTotal(),
            'todayReturned' => $this->todayReturned(),
            'todayOut' => $this->todayOut(),
            'workStatus' => $workScheduleService->status(),
            'selectedEmployee' => $this->selectedEmployee(),
        ])->layout('layouts.security');
    }

    /**
     * Mencari pegawai aktif berdasarkan nama atau NIP.
     */
    protected function searchEmployees(): Collection
    {
        $search = trim($this->search);

        if (strlen($search) < 2) {
            return collect();
        }

        return Employee::query()
            ->with('department')
            ->where('is_active', true)
            ->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    /**
     * Semua pegawai yang masih berada di luar.
     */
    protected function activeExits(): Collection
    {
        return ExitRecord::query()
            ->with('employee.department')
            ->where('status', 'out')
            ->latest('exit_at')
            ->get();
    }

    /**
     * Total pencatatan keluar hari ini.
     */
    protected function todayTotal(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->count();
    }

    /**
     * Total yang sudah kembali hari ini.
     */
    protected function todayReturned(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->where('status', 'returned')
            ->count();
    }

    /**
     * Total transaksi hari ini yang masih berstatus keluar.
     */
    protected function todayOut(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->where('status', 'out')
            ->count();
    }

    /**
     * Pilih pegawai untuk dicatat keluar.
     */
    public function selectEmployee(int $employeeId): void
    {
        $employee = Employee::query()
            ->with('department')
            ->whereKey($employeeId)
            ->where('is_active', true)
            ->first();

        if (! $employee) {
            $this->addError(
                'search',
                'Pegawai tidak ditemukan atau sudah tidak aktif.'
            );

            return;
        }

        $alreadyOut = ExitRecord::query()
            ->where('employee_id', $employee->id)
            ->where('status', 'out')
            ->exists();

        if ($alreadyOut) {
            $this->addError(
                'search',
                "{$employee->name} masih tercatat berada di luar kantor."
            );

            return;
        }

        $this->selectedEmployeeId = $employee->id;
        $this->search = '';
        $this->resetValidation();
        $this->showExitModal = true;
    }

    /**
     * Tutup modal dan bersihkan form.
     */
    public function closeExitModal(): void
    {
        $this->reset([
            'showExitModal',
            'selectedEmployeeId',
            'purpose',
            'destination',
        ]);

        $this->resetValidation();
    }

    /**
     * Catat pegawai keluar.
     */
    public function recordExit(EmployeeExitService $service): void
    {
        $this->validate([
            'selectedEmployeeId' => [
                'required',
                'integer',
                'exists:employees,id',
            ],
            'purpose' => [
                'required',
                'string',
                'max:255',
            ],
            'destination' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $employee = Employee::query()
            ->findOrFail($this->selectedEmployeeId);

        try {
            $service->recordExit(
                employee: $employee,
                purpose: $this->purpose,
                destination: $this->destination,
                operator: auth()->user(),
            );

            $this->closeExitModal();

            session()->flash(
                'success',
                "{$employee->name} berhasil dicatat keluar."
            );
        } catch (DomainException $exception) {
            $this->addError(
                'exit',
                $exception->getMessage()
            );
        }
    }

    /**
     * Catat pegawai kembali.
     */
    public function recordReturn(
        int $exitRecordId,
        EmployeeExitService $service,
    ): void {
        $record = ExitRecord::query()
            ->with('employee')
            ->whereKey($exitRecordId)
            ->where('status', 'out')
            ->first();

        if (! $record) {
            $this->addError(
                'return',
                'Data keluar tidak ditemukan atau sudah ditandai kembali.'
            );

            return;
        }

        $employeeName = $record->employee->name;

        try {
            $service->recordReturn(
                record: $record,
                operator: auth()->user(),
            );

            $this->resetValidation('return');

            session()->flash(
                'success',
                "{$employeeName} berhasil dicatat kembali."
            );
        } catch (DomainException $exception) {
            $this->addError(
                'return',
                $exception->getMessage()
            );
        }
    }

    /**
     * Pegawai yang sedang dipilih.
     */
    protected function selectedEmployee(): ?Employee
    {
        if (! $this->selectedEmployeeId) {
            return null;
        }

        return Employee::query()
            ->with('department')
            ->find($this->selectedEmployeeId);
    }
}