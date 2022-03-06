<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Employee;
use App\Models\Orders;

use Illuminate\Support\Facades\DB;

class StatisticalController extends Controller
{
   public function statistical()
   {
        $data = new Product;
        $data->blog = DB::table('blogs')->count();
        $data->product = DB::table('products')->count();
        $data->outOfStock = DB::table('products')->where('products.unit', '=' , '0')->count();
        $data->waitForConfirmation = DB::table('orders')->where('orders.idStatus', '=' , '1')->count();
        $data->cancelOrder = DB::table('orders')->where('orders.idStatus', '=' , '4')->count();
        $data->shippingOrder = DB::table('orders')->where('orders.idStatus', '=' , '2')->count();
        $data->completeOrder = DB::table('orders')->where('orders.idStatus', '=' , '3')->orWhere('orders.idStatus', '=', '5')->count();
        $data->customer = DB::table('customers')->join('users', 'customers.idUser', '=', 'users.id')->count();
        $data->employee = DB::table('employees')->join('users', 'employees.idUser', '=', 'users.id')->where('users.flag', '1')->count();
        $data->slide = DB::table('slides')->count();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        } 
        return  response()->json(['status' => 'failed',
        'messege' => 'Empty List']);
   }

   public function monthlyRevenue()
   {
      try {
         $data = DB::select('call statistical()');
         return response()->json(['status' => 'successful',
                                     'data' => $data]);
      } catch (Exception $th) {
         return response()->json(['status' => 'failed1',
                                     'error' => $e]);
      }
   }

   public function weeklyRevenue()
   {
      try {
         $data = DB::select('call statisticalWeekly()');
         return response()->json(['status' => 'successful',
                                     'data' => $data]);
      } catch (Exception $th) {
         return response()->json(['status' => 'failed1',
                                     'error' => $e]);
      }
   }

   public function revenue()
   {
      try {
         $data1 = DB::select('call statistical()');
         $data = DB::select('call statisticalWeekly()');
         return response()->json(['status' => 'successful',
                                     'data' => $data,
                                     'data1' => $data1]);
      } catch (Exception $th) {
         return response()->json(['status' => 'failed1',
                                     'error' => $e]);
      }
   }
}
