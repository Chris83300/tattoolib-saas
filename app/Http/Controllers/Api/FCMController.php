<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFcmTokenRequest;
use Illuminate\Http\Request;

class FCMController extends Controller
{
    public function store(StoreFcmTokenRequest $request)
    {
        $user = $request->user();
        $user->fcm_token = $request->token;
        $user->save();

        return response()->json(['message' => 'Token enregistré avec succès']);
    }
}
