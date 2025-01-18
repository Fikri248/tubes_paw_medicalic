<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    protected $redirectTo = '/dashboard';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Selamat Datang!',
            'text' => "Anda berhasil login sebagai {$user->email}.",
        ]);

        return redirect()->intended($this->redirectPath());
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [];

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            $errors['email'] = 'Email yang Anda masukkan salah.';
        }

        if ($user && !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            $errors['password'] = 'Password yang Anda masukkan salah.';
        }

        if (!$user && $request->filled('password')) {
            $errors['password'] = 'Password yang Anda masukkan salah.';
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors($errors);
    }
}
