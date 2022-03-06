<?php

namespace App\Http\Controllers\_AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\User;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register (Request $request) {

        $file = $request->file('image')->getClientOriginalName();
        // $filename = pathinfo($file, PATHINFO_FILENAME); đuôi file
        $filename = date('Y_m_d_H_i_s');
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $filename = "NV_" . $request->name .  $filename . "." . $extension;

        $user = new User;
        DB::beginTransaction();
        try { 
            $user->email = $request->email;
            $user->flag = 1;
            $user->idRole = $request->idRole;
            $user->password = Hash::make($request->password);
            $user->save();
    
            $user_temp = User::where('email', $request->email)->first();
    
            $employee = new Employee;
            $employee->name = $request->name;
            $employee->address = $request->address;
            $employee->gender = $request->gender;
            $employee->image = $filename;
            $employee->idUser = $user_temp->id;
            $employee->save();
            
            DB::commit();
            $path = $request->file('image')->move(public_path("/image/employee"), $filename);
            return response()->json(['status' => 'successful']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed',
                                     'error' => $e]);
        }
    }

    public function registerCustomer (Request $request) {

        $user = new User;
        DB::beginTransaction();
        try { 
            $user->email = $request->email;
            $user->flag = 1;
            $user->idRole = 3;
            $user->password = Hash::make($request->password);
            $user->save();
    
            $user_temp = User::where('email', $request->email)->first();
    
            // $customer = new Customer;
            // $customer->name = $request->name;
            // $customer->address = $request->address;
            // $customer->phone = $request->phone;
            // $customer->idUser = $user_temp->id;
            // $customer->save();

            Customer::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'idUser' => $user_temp->id,
                ]
            );
            
            DB::commit();
            return response()->json(['status' => 'successful']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed',
                                     'error' => $e]);
        }
    }

    public function changepass (Request $request) {
       
        $data =  DB::table('employees')
        ->join('users', 'users.id', '=', 'employees.idUser')
        ->select('users.id', 'users.password')
        ->where('employees.id', $request->id)->first();

        if (!$data) {
            return response()->json(['mess' => 'Unfind Account']);
        }

        $user = User::where('id', $data->id)->first();
        if (Hash::check($request->passold, $data->password)) {
            $user->password = Hash::make($request->passnew);
            $user->save();
            return response()->json(['mess' => 'OK']);
        } else {
            return response()->json(['mess' =>  'Sai rồi']);
        }
    }

    public function changepassCusomter (Request $request) {
       
        $data =  DB::table('customers')
        ->join('users', 'users.id', '=', 'customers.idUser')
        ->select('users.id', 'users.password')
        ->where('customers.id', $request->id)->first();

        if (!$data) {
            return response()->json(['mess' => 'Unfind Account']);
        }

        $user = User::where('id', $data->id)->first();
        if (Hash::check($request->passold, $data->password)) {
            $user->password = Hash::make($request->passnew);
            $user->save();
            return response()->json(['mess' => 'OK']);
        } else {
            return response()->json(['mess' =>  'Sai rồi']);
        }
    }

    public function backAccountByID($id){
        try {
            DB::table('users')
            ->where('email', $id)
            ->update(['flag' => 1]);
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

}
