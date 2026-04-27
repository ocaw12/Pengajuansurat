<section>
    <div class="bg-white shadow-sm rounded-4 p-4 border">

        {{-- HEADER --}}
        <div class="mb-4">
            <h5 class="fw-bold mb-1">Update Password</h5>
            <p class="text-muted small mb-0">Gunakan password minimal 8 karakter</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            {{-- CURRENT PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Current Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input 
                        type="password" 
                        name="current_password"
                        id="current_password"
                        class="form-control border-0 shadow-sm"
                        required
                    >
                </div>
                @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- NEW PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-key"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password"
                        id="password"
                        class="form-control border-0 shadow-sm"
                        required
                        minlength="8"
                    >
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control border-0 shadow-sm"
                        required
                    >
                </div>
                @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- BUTTON --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button class="btn btn-warning fw-semibold px-4 shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Simpan Password
                </button>

                @if (session('success'))
                    <span class="text-success small">
                        ✔ {{ session('success') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- VALIDASI JS --}}
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let password = document.getElementById('password').value;
    let confirm = document.getElementById('password_confirmation').value;

    if (password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter!');
        return;
    }

    if (password !== confirm) {
        e.preventDefault();
        alert('Konfirmasi password tidak sama!');
        return;
    }
});
</script>