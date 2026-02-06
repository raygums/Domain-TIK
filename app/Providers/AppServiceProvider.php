<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |----------------------------------------------------------------------
        | Komponen Blade — Penamaan Bahasa Indonesia
        |----------------------------------------------------------------------
        |
        | Komponen custom disimpan di resources/views/components/komponen/
        | sehingga otomatis terdeteksi oleh Laravel menggunakan sintaks:
        |
        |   <x-komponen.ui.kartu-statistik />    → components/komponen/ui/kartu-statistik.blade.php
        |   <x-komponen.formulir.input />         → components/komponen/formulir/input.blade.php
        |   <x-komponen.navigasi.sidebar-item />  → components/komponen/navigasi/sidebar-item.blade.php
        |
        | Tidak perlu registrasi manual karena folder komponen/ berada di
        | dalam folder components/ standar milik Laravel.
        |
        */
    }
}
