@extends('layouts.app') {{-- Menggunakan layout utama yang sama --}}

@section('title', 'Dashboard Admin Akademik')
@section('page-title', 'Dashboard Admin Akademik') {{-- Judul Halaman --}}

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Selamat Datang, {{ Auth::user()->adminAkademik->nama_lengkap ?? Auth::user()->email }}!</h5>
            </div>
            <div class="card-body">
                <p>Ini adalah halaman dashboard Admin Akademik.</p>
                <p>Fitur-fitur manajemen data master (User, Fakultas, Prodi, Jabatan, Jenis Surat, Alur Approval) akan ditambahkan di sini.</p>

                {{-- Contoh Statistik Sederhana (bisa ditambahkan nanti) --}}
                {{-- <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah User</h5>
                                <p class="card-text fs-4 fw-bold">{{ \App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Jenis Surat</h5>
                                <p class="card-text fs-4 fw-bold">{{ \App\Models\JenisSurat::count() }}</p>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-3">
                         <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pengajuan Pending</h5>
                                <p class="card-text fs-4 fw-bold">{{ \App\Models\PengajuanSurat::where('status_pengajuan', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-3">
                         <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Approval Menunggu</h5>
                                <p class="card-text fs-4 fw-bold">{{ \App\Models\ApprovalPejabat::where('status_approval', 'menunggu')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</div>
@endsection
