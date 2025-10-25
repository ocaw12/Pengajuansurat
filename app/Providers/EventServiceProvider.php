<?php

namespace App\Providers;

// Impor Event dan Listener Anda di atas
use App\Events\PengajuanDivalidasiEvent;
use App\Listeners\BuatAlurApprovalListener;
use App\Events\ApprovalSelesaiEvent;
use App\Listeners\JalankanGeneratePdfJobListener;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // --- TAMBAHKAN MAPPING ANDA DI SINI ---
        PengajuanDivalidasiEvent::class => [
            BuatAlurApprovalListener::class,
        ],
        ApprovalSelesaiEvent::class => [
            JalankanGeneratePdfJobListener::class,
        ],
        // ------------------------------------
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
