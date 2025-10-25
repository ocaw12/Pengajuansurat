@php
    $color = 'secondary';
    switch ($status) {
        case 'pending':
        case 'menunggu_pejabat':
            $color = 'warning';
            break;
        case 'divalidasi_admin':
            $color = 'info';
            break;
        case 'selesai':
        case 'sudah_diambil':
            $color = 'success';
            break;
        case 'siap_diambil':
            $color = 'primary';
            break;
        case 'ditolak':
        case 'perlu_revisi':
            $color = 'danger';
            break;
    }
@endphp
<span class="badge bg-{{ $color }}">{{ Str::title(str_replace('_', ' ', $status)) }}</span> 
