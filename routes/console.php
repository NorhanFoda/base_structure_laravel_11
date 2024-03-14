<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//delete files that has no relation
Schedule::command('untracked_files:delete')->daily();
//telescope entries delete
Schedule::command('telescope:clear')->daily();
