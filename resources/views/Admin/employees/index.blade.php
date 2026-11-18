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
                                <ol class="breadcrumb mb-0"><li class="breadcrumb-item active">Manage employee records</li></ol>
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
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body"><div class="small">Total Employees</div><strong class="fs-3">{{ number_format($employeeStats['total']) }}</strong></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body"><div class="small">Offices</div><strong class="fs-3">{{ number_format($employeeStats['offices']) }}</strong></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-info text-white mb-4">
                                    <div class="card-body"><div class="small">Positions</div><strong class="fs-3">{{ number_format($employeeStats['positions']) }}</strong></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-dark mb-4">
                                    <div class="card-body"><div class="small">Average Salary</div><strong class="fs-3">${{ number_format($employeeStats['averageSalary'], 0) }}</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-chart-bar me-1"></i> Hiring Overview</div>
                            <div class="card-body">
                                <p class="text-muted small">Employee start dates grouped by year</p>
                                @if ($hiresByYear['labels']->isEmpty())
                                    <div class="text-center text-muted py-4">Add employee records to see hiring activity.</div>
                                @else
                                    <div style="height: 250px;">
                                        <canvas id="employeeHiringChart" aria-label="Employees by start year" role="img"></canvas>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-users me-1"></i> Employee Directory</div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-2 mb-3">
                                    <div class="col-md-6 col-lg-4">
                                        <label class="visually-hidden" for="employeeSearch">Search employees</label>
                                        <input id="employeeSearch" class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Search name, position, or office">
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <label class="visually-hidden" for="officeFilter">Office</label>
                                        <select class="form-select" id="officeFilter" name="office">
                                            <option value="all" @selected(! in_array(request('office'), $offices, true))>All offices</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office }}" @selected(request('office') === $office)>{{ $office }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <label class="visually-hidden" for="positionFilter">Position</label>
                                        <select class="form-select" id="positionFilter" name="position">
                                            <option value="all" @selected(! in_array(request('position'), $positions, true))>All positions</option>
                                            @foreach ($positions as $position)
                                                <option value="{{ $position }}" @selected(request('position') === $position)>{{ $position }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <label class="visually-hidden" for="perPageFilter">Entries per page</label>
                                        <select class="form-select" id="perPageFilter" name="per_page" onchange="this.form.submit()">
                                            @foreach ([10, 15, 25, 50] as $perPageOption)
                                                <option value="{{ $perPageOption }}" @selected((string) request('per_page', '15') === (string) $perPageOption)>{{ $perPageOption }} per page</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-lg-2 d-flex gap-2">
                                        <button class="btn btn-outline-primary flex-grow-1" type="submit">Filter</button>
                                        <a class="btn btn-outline-secondary" href="{{ route('admin.employees.index') }}" aria-label="Clear filters" title="Clear filters"><i class="fas fa-undo" aria-hidden="true"></i></a>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle">
                                        <thead>
                                            <tr><th>Employee ID</th><th>Full Name</th><th>Position</th><th>Office</th><th>Age</th><th>Start Date</th><th>Salary</th><th>Actions</th></tr>
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
                                                        <div class="dropdown user-actions-dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-action-menu-toggle aria-expanded="false">Actions</button>
                                                            <div class="dropdown-menu dropdown-menu-end user-actions-menu">
                                                                <a class="dropdown-item" href="{{ route('admin.employees.edit', $employee) }}">Edit</a>
                                                                <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Delete this employee? This cannot be undone.')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td class="text-center text-muted py-4" colspan="8">No employee records match these filters.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $employees->links() }}
                            </div>
                        </div>
                    </div>
                </main>
                @include('partials.footer')
            </div>
        </div>
        @include('partials.scripts')
        @if ($hiresByYear['labels']->isNotEmpty())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const canvas = document.getElementById('employeeHiringChart');

                    if (!canvas || typeof Chart === 'undefined') {
                        return;
                    }

                    new Chart(canvas.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: @json($hiresByYear['labels']),
                            datasets: [{
                                label: 'Employees hired',
                                data: @json($hiresByYear['values']),
                                backgroundColor: 'rgba(13, 110, 253, 0.72)',
                                borderColor: '#0d6efd',
                                borderWidth: 1,
                                barPercentage: 0.65,
                                categoryPercentage: 0.75,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: { display: false },
                            tooltips: { displayColors: false },
                            scales: {
                                xAxes: [{ gridLines: { display: false } }],
                                yAxes: [{
                                    ticks: { beginAtZero: true, precision: 0, stepSize: 1 },
                                    gridLines: { color: 'rgba(0, 0, 0, 0.06)' },
                                }],
                            },
                        },
                    });
                });
            </script>
        @endif
    </body>
</html>
