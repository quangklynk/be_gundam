<?php

namespace App\Http\Controllers\_AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function getRole (){
        $data = Role::all();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function createRole(Request $request){
        try {
            DB::table('roles')->updateOrInsert(
                ['id' => $request->id],
                [
                    'role_name' => $request->role_name,
                 ],
            );
            return response()->json(['status' => 'successful',
                                     'messege' => 'Add Role Success']);
        } catch (\Throwable $th) {
            return  response()->json(['status' => 'failed',
                                    'messege' => 'Add Role Failed']);
        }
    }

    public function getRoleByID($id){
        $data = Role::where('id', $id)->first();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                'messege' => 'Empty Element']);
    }

    public function deleteRoleByID($id){
        try {
            if (DB::table('users')->where('idRole', $id)->exists()) {
                return response()->json(['status' => 'failed',
                'mess' => 'Trạng thái này đã được sử dụng']);
           }

                DB::table('roles')->where('id', $id)->delete();
                return response()->json(['status' => 'successful']);

        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

}
