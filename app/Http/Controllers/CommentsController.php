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
        $comment = new Comment;
        $comment->text = $request->get("message");
        $comment->post_id = $request->get("post_id");
        $comment->user_id = Auth::user()->id;
        $comment->save();

        $user = Auth::user();
        $user->updatePushSubscription("endpoint", "content");


        Notification::send(User::all(), new PushDemo);

        Notification::send(Auth::user(), new MessageTelegram);

        return redirect()->back()->with("status", "Ваш комментарий скоро будет добавлен!");
    }
}
