<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileStoreRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view("pages.profile", compact("user"));
    }

    public function store(ProfileStoreRequest $request)
    {
        $user = Auth::user();

        $user->edit($request->all());
        $user->generateStatusUser($request->get("status"));
        $user->generatePassword($request->get("password"));
        $user->uploadAvatar($request->file("image"));

        return redirect()->back()->with("status", "Изменения успешно сохранены!");
    }
}
