<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="container py-5">
            <div class="text-center mb-5">
                <h5 class="fw-bold text-primary">Welcome back</h5>
                <p class="text-muted">Please log in to access your dashboard</p>
            </div>

            <!-- Login Card -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body p-4">

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success mb-3" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">Remember me</label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">Forgot your password?</a>
                                @endif

                                <button type="submit" class="btn btn-primary px-4">Log in</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-guest-layout>
