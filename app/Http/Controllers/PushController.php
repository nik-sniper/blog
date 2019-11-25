<?php

namespace App\Http\Controllers;

use App\Http\Requests\PushStoreRequest;
use Illuminate\Support\Facades\Auth;


class PushController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PushStoreRequest $request){
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true],200);
    }

}