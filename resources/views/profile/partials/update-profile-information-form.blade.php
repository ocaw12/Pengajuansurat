<section>
    <div class="bg-white shadow-sm rounded-4 p-4 border">

        {{-- HEADER --}}
        <div class="mb-4">
            <h5 class="fw-bold mb-1">Profile Information</h5>
            <p class="text-muted small mb-0">Kelola email dan nomor telepon kamu</p>
        </div>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input 
                        type="email" 
                        name="email"
                        class="form-control border-0 shadow-sm"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

          @if($role !== 'admin akademik')

{{-- NO TELEPON --}}
<div class="mb-3">
    <label class="form-label fw-semibold">No Telepon</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0">
            <i class="bi bi-telephone"></i>
        </span>
        <input 
            type="text" 
            name="no_telepon"
            class="form-control border-0 shadow-sm"
            value="{{ old('no_telepon', 
                $user->mahasiswa->no_telepon 
                ?? $user->adminStaff->no_telepon 
                ?? $user->pejabat->no_telepon 
                ?? $user->adminAkademik->no_telepon 
                ?? ''
            ) }}"
        >
    </div>
    @error('no_telepon')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

@endif

            {{-- BUTTON --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button class="btn btn-warning fw-semibold px-4 shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Simpan
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