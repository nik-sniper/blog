<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionsSubscribeRequest;
use App\Mail\SubscribeEmail;
use App\Subscription;
use Illuminate\Support\Facades\Mail;

class SubscriptionsController extends Controller
{
    public function subscribe(SubscriptionsSubscribeRequest $request)
    {
        $subs = Subscription::add($request->get("email"));
        $subs->generateToken();

        Mail::to($subs)->send(new SubscribeEmail($subs));

        return redirect()->back()->with("status", "Подтвердите почту!");
    }

    public function verify($token)
    {
        $subs = Subscription::where("token", $token)->firstOrFail();
        $subs->token = null;
        $subs->save();

        return redirect("/")->with("status", "Ваша почта подтверждена!");
    }
}
