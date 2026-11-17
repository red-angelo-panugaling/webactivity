<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeManagementController extends Controller
{
    public function index(): View
    {
        return view('Admin.employees.index', [
            'employees' => Employee::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('Admin.employees.create', ['employee' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        Employee::create($request->validate($this->rules()));

        return redirect()->route('admin.employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Employee $employee): View
    {
        return view('Admin.employees.edit', ['employee' => $employee]);
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validate($this->rules()));

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
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'start_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ];
    }
}
