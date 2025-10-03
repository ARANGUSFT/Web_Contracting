<x-guest-layout>
{{-- Forgot Password (Bootstrap, matching registration design) --}}
<div class="registration-container">
  <div class="registration-header">
    @if (Route::has('login'))
      <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('login') }}"
           class="btn btn-outline-light btn-sm rounded-pill fw-semibold">
          <i class="bi bi-box-arrow-in-right me-1"></i> Log in
        </a>
      </div>
    @endif

    <h1>Forgot your password?</h1>
    <p>Enter your email and we’ll send you a reset link.</p>
  </div>

  <div class="registration-body">
    {{-- Session Status --}}
    @if (session('status'))
      <div class="alert alert-success">
        <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
      </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Please check the following errors:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email address *</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input id="email" type="email" name="email" class="form-control"
                 value="{{ old('email') }}" required autofocus>
        </div>
      </div>

      <div class="d-grid gap-2 d-sm-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill px-4">
          <i class="bi bi-arrow-left me-1"></i> Back to login
        </a>
        <button type="submit" class="btn btn-primary rounded-pill px-4">
          <i class="bi bi-send me-1"></i> Email reset link
        </button>
      </div>
    </form>

    @if (Route::has('register'))
      <div class="text-center mt-3">
        <a href="{{ route('register') }}" class="link-primary text-decoration-none fw-semibold">
          <i class="bi bi-person-plus me-1"></i> Create account
        </a>
      </div>
    @endif
  </div>
</div>

</x-guest-layout>
