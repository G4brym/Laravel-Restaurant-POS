<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\User;

class VerificationControllerAPI extends Controller
{
    public function check(Request $request) {
        if ($request->id === null || $request->hash === null) {
            return response()->json(null, 400);
        }

        $user = User::findOrFail($request->id);
        if ($user->password != $request->hash || $user->email_verified_at !== null) {
            return response()->json(null, 401);
        }

        return response()->json('OK');
    }

    public function verify(Request $request) {
        if ($request->id === null || $request->hash === null) {
            return response()->json(null, 400);
        }

        $user = User::findOrFail($request->id);
        if ($user->password !== $request->hash || $user->email_verified_at !== null) {
            return response()->json(null, 400);
        }

        $user->password = Hash::make($request->password);
        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json('OK');
    }
}
