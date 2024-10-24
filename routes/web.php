<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//pelanggan


// admin
Route:: get('login', function(){
    return view('admin.login');
});

Route:: get('register', function(){
    return view('admin.register');
});