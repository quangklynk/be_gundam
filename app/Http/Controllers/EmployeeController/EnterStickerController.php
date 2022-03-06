<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnterSticker;
use App\Models\DetailEnterSticker;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class EnterStickerController extends Controller
{
    public function getEnterSticker (){
        $data = EnterSticker::with(['detailEnterSticker:idSticker,idProduct,unit,price', 'employee:id,name'])->orderBy('created_at', 'desc')->get();
        $product = DB::table('products')
        ->join('detail_enter_stickers', 'products.id', '=', 'detail_enter_stickers.idProduct')
        ->select('products.id', 'products.name')->distinct()
        ->get();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data,
                                    'product' => $product]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function enterSticker (Request $request) {
        DB::beginTransaction();
        try {
            $sticker = EnterSticker::updateOrCreate(
                ['id' => $request->id],
                [
                    'idEmployee' => $request->idEmployee,
                    'flag' => '0',
                ]
            );
          
            $list1 = $request->list;

            foreach ($list1 as $item) {
                DetailEnterSticker::updateOrCreate(
                    [
                        'idSticker' => $sticker->id,
                        'idProduct' => $item['idProduct'],
                    ],
                    [
                        'unit' => $item['unit'],
                        'price' => $item['price'],
                    ]
                );
            }

            DB::commit();
            return response()->json(['status' => $list1]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => $e]);
        }
    }

    public function moveDetailsToProduct ($id) {
        DB::beginTransaction();
        try {
            
            $enterSticker = EnterSticker::where('id', $id)->first();
            if ($enterSticker->flag == 1) {
                return response()->json(['status' => 'failed',
                'error' => 'saiiiii']);
            }

            $enterSticker = EnterSticker::where('id', $id)->update(['flag' => 1]);

            $listDetailsEnterSticker = DetailEnterSticker::where('idSticker', $id)->get();

            foreach ($listDetailsEnterSticker as $item) {
                $temp = Product::where('id', $item->idProduct)->first();
                Product::where('id', $item->idProduct)->update([
                    'unit' => $item->unit + $temp->unit,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'successful']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed',
                                     'error' => $e]);
        }
    }

    public function deleteEnterStickerByID($id){
        try {
            $check = DB::table('enter_stickers')->where('id', $id);
            if ($check->flag == 0) {
                $check->delete();
            } else {
                return response()->json(['status' => 'failed']);
            }
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }
}
