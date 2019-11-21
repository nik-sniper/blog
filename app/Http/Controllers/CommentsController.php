<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentsStoreRequest;
use App\Notifications\MessageTelegram;
use App\Notifications\PushDemo;
use App\Notifications\NewMessage;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;



class CommentsController extends Controller
{
    public function store(CommentsStoreRequest $request)
    {
        //dd($request->keys('endpoint'));
        $comment = new Comment;
        $comment->text = $request->get("message");
        $comment->post_id = $request->get("post_id");
        $comment->user_id = Auth::user()->id;
        $comment->save();
/*
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];*/
        $user = Auth::user();
        $user->updatePushSubscription("endpoint", "content");


        /*$when = Carbon::now();*/

        Notification::send(User::all(), new PushDemo);
        //$request->user()->notify(new PushDemo());
        //Notification::send($user, new NewMessage($user));
        Notification::send(Auth::user(), new MessageTelegram);

        return redirect()->back()->with("status", "Ваш комментарий скоро будет добавлен!");
    }
}
