<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

if (app()->environment('local')) {
    Route::get('/telescope', function () {
        return redirect('/telescope/requests');
    });
}
