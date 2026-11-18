<!DOCTYPE html>
<html lang="en">
    @section('title', 'Add Employee')
    @include('partials.header')

    <body class="sb-nav-fixed">
        @include('partials.navbar')
        <div id="layoutSidenav">
            @include('partials.sidebar')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Add Employee</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employee Directory</a></li>
                            <li class="breadcrumb-item active">Add Employee</li>
                        </ol>

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                        @endif

                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-user-plus me-1"></i> Employee Details</div>
                            <div class="card-body">@include('Admin.employees._form', ['employee' => null])</div>
                        </div>
                    </div>
                </main>
                @include('partials.footer')
            </div>
        </div>
        @include('partials.scripts')
    </body>
</html>
