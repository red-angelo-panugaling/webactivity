<!DOCTYPE html>
<html lang="en">
    @section('title', 'Login')
    @include('partials.header')

    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4">Login</h3>
                                    </div>
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                                        @endif

                                        @if (session('danger'))
                                            <div class="alert alert-danger" role="alert">{{ session('danger') }}</div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="alert alert-danger" role="alert">
                                                Please enter a valid email or username and password.
                                            </div>
                                        @endif

                                        <form action="{{ route('process_login') }}" method="POST">
                                            @csrf
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" name="email" type="text" value="{{ old('email') }}" placeholder="Email or username" autocomplete="username" required />
                                                <label for="inputEmail">Email address or username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" autocomplete="current-password" required />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" name="remember" type="checkbox" value="1" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember me</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                                <button class="btn btn-primary" type="submit">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        @include('partials.scripts')
    </body>
</html>
