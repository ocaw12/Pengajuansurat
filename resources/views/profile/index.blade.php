@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4 d-flex align-items-center">
            <div class="rounded-circle bg-warning text-dark fw-bold d-flex justify-content-center align-items-center me-4"
                 style="width:70px;height:70px;font-size:22px;">
                {{ strtoupper(substr($user->email, 0, 1)) }}
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    {{ $profile->nama_lengkap ?? 'User' }}
                </h5>
                <div class="text-muted small">{{ $user->email }}</div>
                <span class="badge bg-light text-dark mt-2 text-capitalize">
                    {{ $role }}
                </span>
            </div>
        </div>
    </div>

    {{-- ================= DATA PROFIL (FULL WIDTH) ================= --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-4">Data Profil</h5>

            @if($profile)
                <div class="row g-3">

                    {{-- contoh mahasiswa --}}
                    @if($role === 'mahasiswa')
                        <div class="col-md-4">
                            <small class="text-muted">Nama</small>
                            <div class="fw-semibold">{{ $profile->nama_lengkap }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">NIM</small>
                            <div class="fw-semibold">{{ $profile->nim }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Angkatan</small>
                            <div class="fw-semibold">{{ $profile->angkatan }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Prodi</small>
                            <div class="fw-semibold">{{ $profile->programStudi->nama_prodi ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">No Telepon</small>
                            <div class="fw-semibold">{{ $profile->no_telepon ?? '-' }}</div>
                        </div>

                        <div class="col-md-12">
                            <small class="text-muted">Alamat</small>
                            <div class="fw-semibold">{{ $profile->alamat ?? '-' }}</div>
                        </div>
                    @endif

                    {{-- role lain tetap sama kayak sebelumnya --}}

                </div>
            @endif

        </div>
    </div>

    {{-- ================= FORM EDIT (2 KOLOM) ================= --}}
    <div class="row">

        {{-- EDIT PROFILE --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        {{-- EDIT PASSWORD --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

    </div>

</div>

@endsection