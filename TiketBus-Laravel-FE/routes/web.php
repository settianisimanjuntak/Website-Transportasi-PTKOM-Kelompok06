<?php
use Illuminate\Support\Facades\Route;
Route::view('/', 'pages.index');
Route::view('/search', 'pages.search');
Route::view('/profile', 'pages.profile');
