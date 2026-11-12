<!DOCTYPE html>
<html lang="en">
    @section('title', 'User Dashboard')
    @include('partials.header')

    <body class="sb-nav-fixed">
        @include('partials.navbar')
        <div id="layoutSidenav">
            @include('partials.sidebar')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">User Dashboard</h1>
                        <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Welcome, {{ auth()->user()->full_name }}</li></ol>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-header"><i class="fas fa-user me-1"></i> My Information</div>
                                    <div class="card-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4">Full Name</dt><dd class="col-sm-8">{{ auth()->user()->full_name ?: 'Not provided' }}</dd>
                                            <dt class="col-sm-4">Username</dt><dd class="col-sm-8">{{ auth()->user()->username ?: 'Not provided' }}</dd>
                                            <dt class="col-sm-4">Email Address</dt><dd class="col-sm-8">{{ auth()->user()->email }}</dd>
                                            <dt class="col-sm-4">Role</dt><dd class="col-sm-8">{{ auth()->user()->role }}</dd>
                                            <dt class="col-sm-4">Account Status</dt><dd class="col-sm-8"><span class="badge bg-{{ auth()->user()->status ? 'success' : 'danger' }}">{{ auth()->user()->status ? 'Active' : 'Inactive' }}</span></dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="card mb-4"><div class="card-body">Your authorized records and features will appear here.</div></div>
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
