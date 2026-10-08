<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class SocialLoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return back()->with('error', 'Login Google belum dikonfigurasi. Tambahkan kredensial OAuth (Google Client ID/Secret) di .env untuk mengaktifkannya.');
    }
}
