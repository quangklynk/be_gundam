<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\List_Image;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function updateProductImage(Request $request){
        //request: idPro, 
        DB::beginTransaction();
        try {

             if ($request->imgAvatar) {
                $file = $request->file('imgAvatar')->getClientOriginalName();
                // $filename = pathinfo($file, PATHINFO_FILENAME); đuôi file
                $filenameA = date('Y_m_d_H_i_s');
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = "SP_A"  .  $filenameA . "." . $extension;

                Product::updateOrCreate(
                    ['id' => $request->idPro],
                    [
                        'avatar' => $filename,
                     ],
                );
                $path = $request->file('imgAvatar')->move(public_path("/image/product"), $filename);
            }
        
            if ($request->img1) {
                $file = $request->file('img1')->getClientOriginalName();
                $filename = date('Y_m_d_H_i_s');
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = "SP_1" . $request->idProduct . $filename . "." . $extension;
                List_Image::updateOrCreate(
                            ['id' => $request->idImg1],
                            [
                                'image' => $filename,
                                'idProduct' => $request->idPro,
                            ],
                );
                $request->file('img1')->move(public_path("/image/product"), $filename);
            }

            if ($request->img2) {
                $file = $request->file('img2')->getClientOriginalName();
                $filename = date('Y_m_d_H_i_s');
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = "SP_2" . $request->idProduct . $filename . "." . $extension;
                List_Image::updateOrCreate(
                    ['id' => $request->idImg2],
                    [
                        'image' => $filename,
                        'idProduct' => $request->idPro,
                    ],
                );
                $request->file('img2')->move(public_path("/image/product"), $filename);
            }
            if ($request->img3) {
                $file = $request->file('img3')->getClientOriginalName();
                $filename = date('Y_m_d_H_i_s');
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = "SP_3" . $request->idProduct . $filename . "." . $extension;
                List_Image::updateOrCreate(
                    ['id' => $request->idImg3],
                    [
                        'image' => $filename,
                        'idProduct' => $request->idPro,
                    ],
                );
                $request->file('img3')->move(public_path("/image/product"), $filename);
            }
            if ($request->img4) {
                $file = $request->file('img4')->getClientOriginalName();
                $filename = date('Y_m_d_H_i_s');
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = "SP_4" . $request->idProduct . $filename . "." . $extension;
                List_Image::updateOrCreate(
                    ['id' => $request->idImg4],
                    [
                        'image' => $filename,
                        'idProduct' => $request->idPro,
                    ],
                );
                $request->file('img4')->move(public_path("/image/product"), $filename);
            }
            
            DB::commit();
            return response()->json(['status' => 'successful1',
                                     'messege' => 'ok']);
        } catch (Exception $th) {
            DB::rollBack();
            return  response()->json(['status' => 'failed',
                                    'messege' => $th]);
        }
    }
    public function enterSticker (Request $request) {
        DB::beginTransaction();
        try {
            $date = date("Y/m/d");
            $sticker = EnterSticker::updateOrCreate([
                ['id' => $request->id],
                [
                    'dateAdd' => $date,
                    'idEmployee' => $request->idEmployee,
                ]
            ]);

            $list1 = $request->list;

            foreach ($list1 as $item) {
                DetailEnterSticker::updateOrCreate([
                    [
                        'idSticker' => $sticker->id,
                        'idProduct' => $item->idProduct,
                    ],
                    [
                        'unit' => $item->unit,
                        'price' => $item->price,
                    ]
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
}
