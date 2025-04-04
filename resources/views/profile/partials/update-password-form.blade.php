
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-bold text-uppercase">Change Password</div>
        <div class="card-body row g-4">

            {{-- Current Password --}}
            <div class="col-12">
                <label class="form-label">Current Password</label>
                <div class="input-group">
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="col-md-6">
                <label class="form-label">New Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="new_password" class="form-control" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="confirm_password" class="form-control" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="text-end p-4 pt-3">
            <button class="btn btn-warning">Update Password</button>
        </div>
    </div>
</form>


{{-- Mostrar contrasena --}}
<script>
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
</script>


