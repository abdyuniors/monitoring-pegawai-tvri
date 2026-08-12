<?php

namespace App\Livewire\Security;

use App\Models\ExitRecord;
use App\Models\Employee;
use App\Services\EmployeeExitService;
use Illuminate\Support\Collection;
use Livewire\Component;
use DomainException;
use App\Services\WorkScheduleService;

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
            'workStatus' => $workScheduleService->status(),
            'selectedEmployee' => $this->selectedEmployee(),
        ]);
    }

    protected function searchEmployees(): Collection
    {
        if (strlen(trim($this->search)) < 2) {
            return collect();
        }

        return Employee::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->where('name', 'like', '%' . trim($this->search) . '%')
                    ->orWhere('nip', 'like', '%' . trim($this->search) . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    protected function activeExits(): Collection
    {
        return ExitRecord::query()
            ->with('employee.department')
            ->where('status', 'out')
            ->latest('exit_at')
            ->get();
    }

    protected function todayTotal(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->count();
    }

    protected function todayReturned(): int
    {
        return ExitRecord::query()
            ->whereDate('exit_at', today())
            ->where('status', 'returned')
            ->count();
    }

    public function selectEmployee(int $employeeId): void
    {
        $this->selectedEmployeeId = $employeeId;
        $this->search = '';
        $this->showExitModal = true;
    }

    public function closeExitModal(): void
    {
        $this->reset([
            'showExitModal',
            'selectedEmployeeId',
            'purpose',
            'destination',
        ]);
    }

    public function recordExit(EmployeeExitService $service): void
    {
        $this->validate([
            'selectedEmployeeId' => ['required', 'integer', 'exists:employees,id'],
            'purpose' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
        ]);

        $employee = Employee::findOrFail($this->selectedEmployeeId);

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
            $this->addError('exit', $exception->getMessage());
        }
    }

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

            session()->flash(
                'success',
                "{$employeeName} berhasil dicatat kembali."
            );
        } catch (DomainException $exception) {
            $this->addError('return', $exception->getMessage());
        }
    }
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