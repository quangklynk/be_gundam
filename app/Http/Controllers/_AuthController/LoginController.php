<?php

namespace App\Http\Controllers\_AuthController;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Employee;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    public function login (Request $request) {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = User::where('email', $request->email)->first();
            $data = DB::table('users')
            ->join('employees', 'users.id', '=', 'employees.idUser')
            ->join('roles', 'roles.id', '=', 'users.idRole')        
            ->select('employees.id', 'users.flag', 'employees.name', 'roles.role_name')
            ->where('users.email', $request->email)
            ->first();

            $tokenData = $user->createToken($user->email.'-'.now(), [$data->role_name]);
            $user->accessToken = $tokenData->accessToken;

            return response()->json(['data' => $data, 'token' => $user->accessToken]);
        }
        return response()->json(['email' => 'Sai ten truy cap hoac mat khau!']);
    }

    public function loginCustomer (Request $request) {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = User::where('email', $request->email)->first();
            $data = DB::table('users')
            ->join('customers', 'users.id', '=', 'customers.idUser')  
            ->join('roles', 'roles.id', '=', 'users.idRole')   
            ->select('customers.id', 'users.flag', 'customers.name', 'roles.role_name')
            ->where('users.email', $request->email)
            ->first();
            
            $cart = Cart::where('idCustomer', $data->id)->count();

            $tokenData = $user->createToken($user->email.'-'.now(), [$data->role_name]);
            $user->accessToken = $tokenData->accessToken;

            return response()->json([
                                    'cart' => $cart,
                                    'data' => $data, 
                                    'token' => $user->accessToken
                                    ]);
        }
        return response()->json(['email' => 'Sai ten truy cap hoac mat khau!']);
    }

    public function logout (Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}