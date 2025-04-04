
<form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?');">
    @csrf
    @method('DELETE')

    <div class="card mb-4 shadow-sm border-danger">
        <div class="card-header bg-white text-danger fw-bold text-uppercase">Delete Account</div>
        <div class="card-body">

            <p class="text-muted">
                Once your account is deleted, all of its resources and data will be permanently deleted.
                Before deleting your account, please ensure you have downloaded any data or information you wish to retain.
            </p>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-outline-danger">Yes, delete my account</button>
            </div>
        </div>
    </div>
</form>
