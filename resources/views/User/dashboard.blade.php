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
                                <div class="card mb-4" id="profile">
                                    <div class="card-header"><i class="fas fa-user me-1"></i> My Information</div>
                                    <div class="card-body">
                                        <div id="profile-view">
                                            <dl class="row mb-3">
                                                <dt class="col-sm-4">Full Name</dt><dd class="col-sm-8">{{ auth()->user()->full_name ?: 'Not provided' }}</dd>
                                                <dt class="col-sm-4">Username</dt><dd class="col-sm-8">{{ auth()->user()->username ?: 'Not provided' }}</dd>
                                                <dt class="col-sm-4">Email Address</dt><dd class="col-sm-8">{{ auth()->user()->email }}</dd>
                                                <dt class="col-sm-4">Role</dt><dd class="col-sm-8">{{ auth()->user()->role }}</dd>
                                                <dt class="col-sm-4">Account Status</dt><dd class="col-sm-8"><span class="badge bg-{{ auth()->user()->status ? 'success' : 'danger' }}">{{ auth()->user()->status ? 'Active' : 'Inactive' }}</span></dd>
                                            </dl>
                                            @unless (auth()->user()->isAdmin())
                                                <a class="btn btn-primary" href="{{ route('dashboard') }}#profile">Edit Profile</a>
                                            @endunless
                                        </div>

                                        @unless (auth()->user()->isAdmin())
                                        <div id="profile-edit" class="d-none">
                                            @if (session('success'))
                                                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                                            @endif

                                            @if ($errors->any())
                                                <div class="alert alert-danger" role="alert">
                                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route('profile.update') }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label class="form-label" for="full_name">Full Name</label>
                                                    <input class="form-control" id="full_name" name="full_name" type="text" value="{{ old('full_name', auth()->user()->full_name) }}" required maxlength="255" autocomplete="name">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="username">Username</label>
                                                    <input class="form-control" id="username" name="username" type="text" value="{{ old('username', auth()->user()->username) }}" required maxlength="255" autocomplete="username">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Email Address</label>
                                                    <input class="form-control" id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required maxlength="255" autocomplete="email">
                                                </div>

                                                <p class="text-muted">Leave the password fields blank to keep your current password.</p>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password">New Password</label>
                                                    <input class="form-control" id="password" name="password" type="password" minlength="8" autocomplete="new-password">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password">
                                                </div>

                                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                                <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Cancel</a>
                                            </form>
                                        </div>
                                        @endunless
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
        @unless (auth()->user()->isAdmin())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const profileView = document.getElementById('profile-view');
                const profileEdit = document.getElementById('profile-edit');

                const showProfileEditor = () => {
                    const isEditing = window.location.hash === '#profile';
                    profileView.classList.toggle('d-none', isEditing);
                    profileEdit.classList.toggle('d-none', !isEditing);
                };

                showProfileEditor();
                window.addEventListener('hashchange', showProfileEditor);
            });
        </script>
        @endunless
    </body>
</html>
