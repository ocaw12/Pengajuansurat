<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        // Pastikan middleware ini ada dalam urutan yang benar
        // Laravel biasanya sudah menyertakan ini secara default,
        // tapi kita tambahkan secara eksplisit untuk testing.
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class, // Atau VerifyCsrfToken::class
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]);

    // Jika ada middleware alias atau global lain, biarkan di sini
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
