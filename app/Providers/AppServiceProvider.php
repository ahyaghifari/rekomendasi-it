<?php

namespace App\Providers;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportRedirects\Redirector;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Setelah login Filament, arahkan ke homeUrl panel bila diset. Panel Surat Rekomendasi
        // berbagi prefix /surat-rekomendasi dengan halaman publik, jadi root panel bukan halaman admin.
        $this->app->bind(LoginResponse::class, fn () => new class implements LoginResponse
        {
            public function toResponse($request): RedirectResponse|Redirector
            {
                return redirect()->intended(Filament::getHomeUrl());
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
