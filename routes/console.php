<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('tickets:manage --type=generate')
    ->cron('0 0 */8 * *') // Jalan setiap tanggal kelipatan 8
    ->timezone('Asia/Jakarta');

// 2. Jadwal Update Harian (Stok & Hapus)
Schedule::command('tickets:manage --type=daily')
    ->dailyAt('00:01') // Jalan setiap jam 00:01
    ->timezone('Asia/Jakarta');
