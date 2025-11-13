<?php

namespace App\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FonnteChannel
{
    /**
     * Kirim notifikasi via Fonnte.
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        // 1. Ambil data pesan dari notifikasi
        $messageData = $notification->toFonnte($notifiable);
        if (!$messageData) {
            Log::warning('Notifikasi Fonnte dibatalkan: tidak ada data pesan.');
            return;
        }

        // 2. Ambil nomor tujuan dari model (via routeNotificationForFonnte)
        $target = $notifiable->routeNotificationFor('fonnte', $notification);
        if (!$target) {
            Log::warning('Notifikasi Fonnte gagal: Nomor target tidak ditemukan atau format salah.', ['notifiable_id' => $notifiable->id]);
            return;
        }
        
        // 3. Ambil API Key dari .env
        $apiKey = config('services.fonnte.api_key');
        if (!$apiKey) {
            Log::error('Notifikasi Fonnte gagal: FONNTE_API_KEY tidak diset di .env');
            return;
        }

        // 4. Kirim ke Fonnte
        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $messageData['message'],
                'countryCode' => '62', // Pastikan kode negara benar
            ]);

            if ($response->successful() && $response->json('status') === true) {
                Log::info('Notifikasi Fonnte terkirim.', ['target' => $target, 'response' => $response->json()]);
            } else {
                Log::error('Notifikasi Fonnte gagal terkirim.', ['target' => $target, 'response' => $response->body()]);
            }

        } catch (\Exception $e) {
            Log::error('Exception saat mengirim notifikasi Fonnte: ' . $e->getMessage());
        }
    }
}