<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('peminjaman:batalkan-kadaluarsa')->dailyAt('23:59');

Schedule::command('sanksi:cek-keterlambatan')->dailyAt('00:01');