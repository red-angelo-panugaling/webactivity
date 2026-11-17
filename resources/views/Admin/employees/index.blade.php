<!DOCTYPE html>
<html lang="en">
    @section('title', 'Employee Management')
    @include('partials.header')

    <body class="sb-nav-fixed">
        @include('partials.navbar')
        <div id="layoutSidenav">
            @include('partials.sidebar')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-3">
                            <div>
                                <h1>Employee Management</h1>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item active">Employee Directory</li>
                                </ol>
                            </div>
                            <a class="btn btn-primary mt-2" href="{{ route('admin.employees.create') }}"><i class="fas fa-user-plus me-1"></i> Add Employee</a>
                        </div>

                        @foreach (['success' => 'success', 'danger' => 'danger'] as $message => $type)
                            @if (session($message))
                                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                                    {{ session($message) }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        @endforeach

                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-address-book me-1"></i> Employee Directory</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle" id="datatablesSimple" data-per-page="10">
                                        <thead>
                                            <tr><th>ID</th><th>Name</th><th>Position</th><th>Office</th><th>Age</th><th>Start Date</th><th>Salary</th><th>Actions</th></tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($employees as $employee)
                                                <tr>
                                                    <td>{{ $employee->id }}</td>
                                                    <td>{{ $employee->name }}</td>
                                                    <td>{{ $employee->position }}</td>
                                                    <td>{{ $employee->office }}</td>
                                                    <td>{{ $employee->age ?? '—' }}</td>
                                                    <td>{{ $employee->start_date->format('M j, Y') }}</td>
                                                    <td>${{ number_format((float) $employee->salary, 2) }}</td>
                                                    <td class="text-nowrap">
                                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.employees.edit', $employee) }}">Edit</a>
                                                        <form class="d-inline" method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Delete this employee? This cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td class="text-center text-muted py-4" colspan="8">No employees yet. Add an employee to start the directory.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                @include('partials.footer')
            </div>
        </div>
        @include('partials.scripts')
    </body>
</html>
