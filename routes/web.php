<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Login');
})->name('login');

Route::get('/home', function () {
    return view('Homepage');
});
Route::get('/signup', function () {
    return view('Signup');
});

