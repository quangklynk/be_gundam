<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use Illuminate\Support\Facades\DB;

class SlideController extends Controller
{
    public function getSlide (){
        $data = Slide::with(['employee:id,name'])->orderBy('created_at', 'desc')->get();
        
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function createSlide(Request $request){
        $file = $request->file('image')->getClientOriginalName();
        $filename = date('Y_m_d_H_i_s');
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $filename = "SL_" . $request->idEmployee .  $filename . "." . $extension;
        $path = $request->file('image')->move(public_path("/image/slide"), $filename);

        try {
            Slide::updateOrCreate(
                ['id' => $request->id],
                [
                    'image' => $filename,
                    'idEmployee' => $request->idEmployee
                 ],
            );
            return response()->json(['status' => 'successful',
                                     'messege' => 'Add Slide Success']);
        } catch (\Throwable $th) {
            return  response()->json(['status' => 'failed',
                                    'messege' => 'Add Slide Failed']);
        }
    }

    public function getSlideByID($id){
        $data = Slide::where('id', $id)->first();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty Element']);
    }

    public function deleteSlideByID($id){
        try {
            DB::table('slides')->where('id', $id)->delete();
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }
}
