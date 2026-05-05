<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
Artisan::command('inspire', fn() => (new self)->comment(Inspiring::quote()))->purpose('Inspiring quote');