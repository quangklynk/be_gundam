<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Detail_Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function getAllOrder()
    {
        $data = Orders::with('detailOrder:idOrder,unit,price,idProduct', 'status:id,description', 'customer:id,name', 'rate:idOrder,vote', 'employee:id,name')->orderBy('created_at', 'desc')->get();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function confirmOrder($id, Request $request)
    {
        DB::beginTransaction();
        try {
        if ($od = Orders::where(['id' => $id, 
                            'idStatus' => 1])
                ->update(['idStatus' => 2,
                        'idEmployee' => $request->idEmployee])) 
            {
                DB::commit();
                return response()->json(['status' => 'lưu ok']);
            }
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => 'Không đổi được']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => $e]);
        }
    }

    public function completeOrder($id, Request $request)
    {
        DB::beginTransaction();
        try {
        if ($od = Orders::where(['id' => $id, 
                            'idStatus' => 2])
                ->update(['idStatus' => 3,
                'idEmployee' => $request->idEmployee])) 
            {
                DB::commit();
                return response()->json(['status' => 'lưu ok']);
            }
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => 'Không đổi được']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => $e]);
        }
    }

    public function cancelOrder($id, Request $request)
    {   
        DB::beginTransaction();
        try {

            if (!$od = Orders::where(['id' => $id, 
                            'idStatus' => 1])
                ->update(['idStatus' => 4,
                'idEmployee' => $request->idEmployee])) 
            {
            DB::rollBack();
             return response()->json(['status' => 'faile',
                                     'error' => 'sai òi']);
            }
            $d_od = Detail_Order::where('idOrder', $id)->get();

            foreach ($d_od as  $item) {
                $pro = Product::where('id', $item->idProduct)->first();
                $pro->unit = (int)$pro->unit + (int)$item->unit;
                $pro->save();
            }
            DB::commit();
            return response()->json(['status' => 'lưu ok']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => $e]);
        }
    }
}
