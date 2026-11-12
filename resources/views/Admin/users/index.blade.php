<!DOCTYPE html>
<html lang="en">
    @section('title', 'User Management')
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
                                <h1>User Management</h1>
                                <ol class="breadcrumb mb-0"><li class="breadcrumb-item active">Manage system accounts</li></ol>
                            </div>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-user-plus me-1"></i> Add User</button>
                        </div>

                        @foreach (['success' => 'success', 'danger' => 'danger'] as $message => $type)
                            @if (session($message))
                                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">{{ session($message) }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                            @endif
                        @endforeach
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                        @endif

                        <div class="row">
                            <div class="col-xl-4 col-md-6"><div class="card bg-primary text-white mb-4"><div class="card-body"><div class="small">Total Users</div><strong class="fs-3">{{ $totalUsers }}</strong></div></div></div>
                            <div class="col-xl-4 col-md-6"><div class="card bg-success text-white mb-4"><div class="card-body"><div class="small">Active Users</div><strong class="fs-3">{{ $activeUsers }}</strong></div></div></div>
                            <div class="col-xl-4 col-md-6"><div class="card bg-danger text-white mb-4"><div class="card-body"><div class="small">Inactive Users</div><strong class="fs-3">{{ $inactiveUsers }}</strong></div></div></div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-users me-1"></i> System Accounts</div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
                                    <div class="col-lg-6"><label class="visually-hidden" for="userSearch">Search users</label><input id="userSearch" class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Search name, username, or email"></div>
                                    <div class="col-sm-5 col-lg-2"><label class="visually-hidden" for="roleFilter">Role</label><select class="form-select" id="roleFilter" name="role"><option value="">All roles</option><option value="Admin" @selected(request('role') === 'Admin')>Admin</option><option value="User" @selected(request('role') === 'User')>User</option></select></div>
                                    <div class="col-sm-5 col-lg-2"><label class="visually-hidden" for="statusFilter">Status</label><select class="form-select" id="statusFilter" name="status"><option value="">All statuses</option><option value="1" @selected(request('status') === '1')>Active</option><option value="0" @selected(request('status') === '0')>Inactive</option></select></div>
                                    <div class="col-sm-2"><button class="btn btn-outline-primary w-100" type="submit">Filter</button></div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle">
                                        <thead><tr><th>User ID</th><th>Full Name</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Date Created</th><th>Actions</th></tr></thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td><td>{{ $user->full_name }}</td><td>{{ $user->username }}</td><td>{{ $user->email }}</td><td>{{ $user->role }}</td>
                                                    <td><span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></td><td>{{ $user->created_at->format('M j, Y') }}</td>
                                                    <td class="text-nowrap">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUser{{ $user->id }}">Edit</button>
                                                        @if ($user->is(auth()->user()))
                                                            <button class="btn btn-sm btn-outline-secondary" disabled title="You cannot deactivate your own account">{{ $user->status ? 'Active' : 'Inactive' }}</button>
                                                        @else
                                                            <form class="d-inline" method="POST" action="{{ route('admin.users.status', $user) }}" onsubmit="return confirm('Are you sure you want to {{ $user->status ? 'deactivate' : 'activate' }} this account?')">
                                                                @csrf @method('PATCH')<input type="hidden" name="status" value="{{ $user->status ? 'inactive' : 'active' }}"><button class="btn btn-sm btn-outline-{{ $user->status ? 'danger' : 'success' }}" type="submit">{{ $user->status ? 'Deactivate' : 'Activate' }}</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1" aria-labelledby="editUserLabel{{ $user->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                                                        <form method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
                                                            <div class="modal-header"><h5 class="modal-title" id="editUserLabel{{ $user->id }}">Edit User</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                                            <div class="modal-body">
                                                                <div class="mb-3"><label class="form-label" for="editName{{ $user->id }}">Full Name</label><input class="form-control" id="editName{{ $user->id }}" name="full_name" value="{{ $user->full_name }}" required></div>
                                                                <div class="mb-3"><label class="form-label" for="editUsername{{ $user->id }}">Username</label><input class="form-control" id="editUsername{{ $user->id }}" name="username" value="{{ $user->username }}" required></div>
                                                                <div class="mb-3"><label class="form-label" for="editEmail{{ $user->id }}">Email Address</label><input class="form-control" id="editEmail{{ $user->id }}" name="email" type="email" value="{{ $user->email }}" required></div>
                                                                <div class="mb-3"><label class="form-label" for="editRole{{ $user->id }}">Role</label><select class="form-select" id="editRole{{ $user->id }}" name="role" @disabled($user->is(auth()->user()))><option value="Admin" @selected($user->role === 'Admin')>Admin</option><option value="User" @selected($user->role === 'User')>User</option></select>@if($user->is(auth()->user()))<input type="hidden" name="role" value="Admin"><div class="form-text">You cannot change your own role.</div>@endif</div>
                                                                <div class="mb-3"><label class="form-label" for="editPassword{{ $user->id }}">New Password <span class="text-muted">(leave blank to keep current)</span></label><input class="form-control" id="editPassword{{ $user->id }}" name="password" type="password" autocomplete="new-password"><div class="form-text">If provided, confirm the new password below.</div></div>
                                                                <div><label class="form-label" for="editPasswordConfirm{{ $user->id }}">Confirm New Password</label><input class="form-control" id="editPasswordConfirm{{ $user->id }}" name="password_confirmation" type="password" autocomplete="new-password"></div>
                                                            </div>
                                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit">Save Changes</button></div>
                                                        </form>
                                                    </div></div>
                                                </div>
                                            @empty
                                                <tr><td class="text-center text-muted py-4" colspan="8">No user accounts match these filters.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </main>
                @include('partials.footer')
            </div>
        </div>

        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                <form method="POST" action="{{ route('admin.users.store') }}">@csrf
                    <div class="modal-header"><h5 class="modal-title" id="addUserLabel">Add User</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label" for="fullName">Full Name</label><input class="form-control" id="fullName" name="full_name" value="{{ old('full_name') }}" required></div>
                        <div class="mb-3"><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="{{ old('username') }}" required></div>
                        <div class="mb-3"><label class="form-label" for="email">Email Address</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required></div>
                        <div class="mb-3"><label class="form-label" for="password">Password</label><input class="form-control" id="password" name="password" type="password" autocomplete="new-password" required></div>
                        <div class="mb-3"><label class="form-label" for="passwordConfirmation">Confirm Password</label><input class="form-control" id="passwordConfirmation" name="password_confirmation" type="password" autocomplete="new-password" required></div>
                        <div class="mb-3"><label class="form-label" for="role">Role</label><select class="form-select" id="role" name="role" required><option value="User">User</option><option value="Admin">Admin</option></select></div>
                        <div><label class="form-label" for="status">Account Status</label><select class="form-select" id="status" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit">Create Account</button></div>
                </form>
            </div></div>
        </div>
        @include('partials.scripts')
    </body>
</html>
