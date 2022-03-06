<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function getCart (){
        $data = Cart::all();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function createCart(Request $request){
        try {
            DB::table('carts')->updateOrInsert(
                ['id' => $request->id],
                [
                    'idCustomer' => $request->idCustomer,
                    'idProduct' => $request->idProduct,
                    'unit' => $request->unit,
                 ],
            );
            return response()->json(['status' => 'successful',
                                     'messege' => 'Add Cart Success']);
        } catch (\Throwable $th) {
            return  response()->json(['status' => 'failed',
                                    'messege' => 'Add Cart Failed']);
        }
    }

    public function getCartByID($id){
        $data = Cart::where('id', $id)->first();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty Element']);
    }

    public function deleteCartByID($id){
        try {
            DB::table('carts')->where('id', $id)->delete();
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }
}
