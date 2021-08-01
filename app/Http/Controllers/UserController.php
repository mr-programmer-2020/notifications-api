<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Jobs\MailJob;
use App\Jobs\TelegramJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function create(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telegram_user_id' => $request->telegram_user_id,
            'password' => bcrypt($request->password)
        ]);

        //send telegram notification
        $job = (new TelegramJob())->delay(Carbon::now()->addSeconds(5));
        dispatch($job);

        //send mail notification
        $job = (new MailJob())->delay(Carbon::now()->addSeconds(5));
        dispatch($job);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return new UserResource($response);
    }

    public function login(UserRequest $request) {
       

        // Check email
        $user = User::where('email', $request->email)->first();

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return new UserResource($response);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }


}
