<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\User;

class EmployeeController extends Controller
{
    public function getEmployee (){
        $data = DB::table('employees')
            ->join('users', 'users.id', '=', 'employees.idUser')
            ->join('roles', 'roles.id', '=', 'users.idRole')        
            ->select('employees.id', 'users.email' ,'users.flag', 'employees.name', 'roles.role_name', 'employees.address', 'employees.image')
            ->get();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function getEmployeeByID($id){
        $data =  DB::table('employees')
                ->join('users', 'users.id', '=', 'employees.idUser')
                ->select('employees.id', 'users.email' , 'employees.name','employees.gender', 'employees.address', 'employees.image')
                ->where('employees.id', $id)->first();
        if($data){
            return response()->json(['status' => 'nhân viên nè',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty Element']);
    }

    public function updateEmployeeWithNotImage(Request $request) {
        try {
            Employee::where('id', $request->id)
                ->update(['name' => $request->name,
                          'address' => $request->address,
                          'gender' => $request->gender]);
             return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

    public function updateEmployeeWithImage(Request $request) {
        $file = $request->file('image')->getClientOriginalName();
        $filename = date('Y_m_d_H_i_s');
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $filename = "NV_" . $request->name .  $filename . "." . $extension;
        try {
            Employee::where('id', $request->id)
                ->update(['image' => $filename]);
            $path = $request->file('image')->move(public_path("/image/employee"), $filename);
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

    public function deleteEmployeeByID($id){
        try {
            DB::table('users')
              ->where('email', $id)
              ->update(['flag' => 0]);
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

}
