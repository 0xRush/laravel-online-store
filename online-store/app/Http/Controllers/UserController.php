<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    function register (Request $request) {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        Cart::create([
            'user_id' =>  $user->id,
            'is_paid' => false,
            'total' => 0
        ]);

        return response()->json([ 'data' => 'user created successfully'], 200);
    }

    function login (Request $request) {

        validator($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ])->validate();

        $user = User::where('email', $request->input('email'))->first();
        if (!$user)
        {
            return response()->json([ 'data' => 'user not found'], 404);
        }
        if (!Hash::check($request->input('password'), $user->getAuthPassword()))
        {
            return response()->json([ 'data' => 'password not matched'], 404);
        }
        $token = $user->createToken(time())->plainTextToken;
        return response()->json(['token' => $token], 200);
    }

    function logout () {
        auth()->user()->currentAccessToken()->delete();
    }
}
