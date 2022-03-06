<?php

namespace App\Http\Controllers\_AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForgotController extends Controller
{
    public function forgot (Request $request) {
        $email = $request->email;
        $length = 10;

        if (User::where('email', $email)->doesntExist()) {
            return response()->json(['mess' => 'Account doesnt exists!']);
        }

        $tokenFARP = Str::random($length);

        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $tokenFARP
            ]);

            $details = [
                'title' => 'Change password!',
                'body' => 'Link'
            ];
            \Mail::to($email)->send(new \App\Mail\SendMail($details));

            return response()->json(['mess' => 'Check your email!',
                                     'token' => $tokenFARP]);
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

    }

    public function reset (Request $request) {
        $token = $request->token;

        if (!$passwordResets = DB::table('password_resets')->where('token', $token)->first()) {
            return response()->json(['mess' => 'Invalid token!']);
        }

        if(!$user = User::where('email', $passwordResets->email)->first()) {
            return response()->json(['mess' => 'User dont exists!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        
        return response()->json(['mess' => 'Change password successful!']);
    }

}
