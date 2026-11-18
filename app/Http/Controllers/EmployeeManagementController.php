<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeManagementController extends Controller
{
    private const ADD_NEW_OPTION = '__add_new__';

    public function index(Request $request): View
    {
        $options = $this->formOptions();
        $search = trim($request->string('search')->toString());
        $office = $request->input('office');
        $position = $request->input('position');
        $perPage = in_array($request->input('per_page'), ['10', '15', '25', '50'], true)
            ? (int) $request->input('per_page')
            : 15;

        $employees = Employee::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('office', 'like', "%{$search}%");
                });
            })
            ->when(in_array($office, $options['offices'], true), fn ($query) => $query->where('office', $office))
            ->when(in_array($position, $options['positions'], true), fn ($query) => $query->where('position', $position))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $hiresByYear = Employee::query()
            ->selectRaw('YEAR(start_date) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return view('Admin.employees.index', [
            'employees' => $employees,
            'employeeStats' => [
                'total' => Employee::count(),
                'offices' => Employee::query()->distinct()->count('office'),
                'positions' => Employee::query()->distinct()->count('position'),
                'averageSalary' => (float) (Employee::query()->avg('salary') ?? 0),
            ],
            'hiresByYear' => [
                'labels' => $hiresByYear->pluck('year')->values(),
                'values' => $hiresByYear->pluck('total')->values(),
            ],
            'offices' => $options['offices'],
            'positions' => $options['positions'],
        ]);
    }

    public function create(): View
    {
        return view('Admin.employees.create', array_merge(['employee' => null], $this->formOptions()));
    }

    public function store(Request $request): RedirectResponse
    {
        Employee::create($this->resolveAdditionalOptions($request->validate($this->rules($request))));

        return redirect()->route('admin.employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Employee $employee): View
    {
        return view('Admin.employees.edit', array_merge(['employee' => $employee], $this->formOptions()));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $employee->update($this->resolveAdditionalOptions($request->validate($this->rules($request))));

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function rules(Request $request): array
    {
        $options = $this->formOptions();

        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255', Rule::in([...$options['positions'], self::ADD_NEW_OPTION])],
            'position_new' => [Rule::requiredIf($request->input('position') === self::ADD_NEW_OPTION), 'nullable', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:255', Rule::in([...$options['offices'], self::ADD_NEW_OPTION])],
            'office_new' => [Rule::requiredIf($request->input('office') === self::ADD_NEW_OPTION), 'nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'start_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function resolveAdditionalOptions(array $validated): array
    {
        foreach (['position', 'office'] as $field) {
            $newField = $field.'_new';

            if (($validated[$field] ?? null) === self::ADD_NEW_OPTION) {
                $validated[$field] = trim($validated[$newField]);
            }

            unset($validated[$newField]);
        }

        return $validated;
    }

    /**
     * @return array{offices: array<int, string>, positions: array<int, string>, addNewOption: string}
     */
    private function formOptions(): array
    {
        return [
            'offices' => Employee::query()->select('office')->distinct()->orderBy('office')->pluck('office')->all(),
            'positions' => Employee::query()->select('position')->distinct()->orderBy('position')->pluck('position')->all(),
            'addNewOption' => self::ADD_NEW_OPTION,
        ];
    }
}
