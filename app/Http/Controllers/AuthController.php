<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view("pages.register");
    }

    public function register(Request $request)
    {
        $user = User::add($request->all());

        $user->generatePassword($request->get("password"));

        return redirect("/login");
    }

    public function loginForm()
    {
        return view("pages.login");
    }

    public function login(AuthLoginRequest $request)
    {
        if(Auth::attempt([
            "email" => $request->get("email"),
            "password" => $request->get("password")
        ])) {
            return redirect("/");
        }

        return redirect()->back()->with("status", "Неправельный email или пароль !");
    }

    public function logout()
    {
        Auth::logout();

        return redirect("/login");
    }
}
