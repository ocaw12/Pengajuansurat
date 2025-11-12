<?php
// Cek apakah GD tersedia
if (extension_loaded('gd')) {
    echo "Ekstensi GD sudah aktif!";
    
    // Coba buat gambar baru menggunakan GD
    $image = imagecreatetruecolor(200, 100); // Membuat gambar kosong 200x100 px
    
    // Mengisi latar belakang dengan warna putih
    $white = imagecolorallocate($image, 255, 255, 255); // Warna putih
    imagefill($image, 0, 0, $white);

    // Menambahkan teks ke gambar
    $black = imagecolorallocate($image, 0, 0, 0); // Warna hitam
    imagestring($image, 5, 50, 40, 'Test GD Berhasil!', $black);
    
    // Menyimpan gambar ke file
    header('Content-Type: image/png');
    imagepng($image); // Menampilkan gambar langsung di browser
    
    // Membersihkan memori
    imagedestroy($image);
} else {
    echo "Ekstensi GD tidak aktif.";
}
?>
