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
    // --- TAMBAHKAN ALIAS ANDA DI SINI ---
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        // -------------------------------------

    // Jika ada middleware alias atau global lain, biarkan di sini
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
