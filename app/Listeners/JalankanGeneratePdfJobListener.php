<?php

namespace App\Listeners;

use App\Events\ApprovalSelesaiEvent;
use App\Jobs\GeneratePdfJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class JalankanGeneratePdfJobListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApprovalSelesaiEvent $event): void
    {
        // Kirim $pengajuan ke Job untuk diproses di background
        GeneratePdfJob::dispatch($event->pengajuan);
    }
}
