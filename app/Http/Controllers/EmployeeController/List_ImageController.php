<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\List_Image;
use Illuminate\Support\Facades\DB;

class List_ImageController extends Controller
{
    public function getList_Image (){
        $data = List_Image::all();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function createList_Image(Request $request){
        try {
            DB::table('list__images')->updateOrInsert(
                ['id' => $request->id],
                [
                    'image' => $request->image,
                    'idProduct' => $request->idProduct,
                 ],
            );
            return response()->json(['status' => 'successful',
                                     'messege' => 'Add Status Success']);
        } catch (\Throwable $th) {
            return  response()->json(['status' => 'failed',
                                    'messege' => 'Add Status Failed']);
        }
    }

    public function getList_ImageByID($id){
        $data = List_Image::where('id', $id)->first();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                'messege' => 'Empty Element']);
    }

    public function deleteList_ImageByID($id){
        try {
            DB::table('list__images')->where('id', $id)->delete();
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

}
