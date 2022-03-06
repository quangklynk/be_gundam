<?php

namespace App\Http\Controllers\CustomerController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Detail_Order;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function getCustomer (){
        // $data = Customer::all();
        $data = DB::table('customers')
            ->join('users', 'users.id', '=', 'customers.idUser')   
            ->select('customers.id', 'users.email' ,'users.flag', 'customers.name', 'customers.address', 'customers.phone', 'customers.image')
            ->get();
        if($data){
            return response()->json(['status' => 'successful',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty List']);
    }

    public function getCustomerByID($id){
        $data =  DB::table('customers')
                ->join('users', 'users.id', '=', 'customers.idUser')
                ->select('customers.id', 'users.email' , 'customers.name','customers.phone', 'customers.address', 'customers.image')
                ->where('customers.id', $id)->first();
        if($data){
            return response()->json(['status' => 'khách hàng nè',
                                    'data' => $data]);
        }
        return  response()->json(['status' => 'failed',
                                    'messege' => 'Empty Element']);
    }

    public function updateCustomerWithNotImage(Request $request) {
        try {
            Customer::where('id', $request->id)
                ->update(['name' => $request->name,
                          'address' => $request->address,
                          'phone' => $request->phone]);
             return response()->json(['status' => 'successful']);
        } catch (Exception $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

    public function updateCustomerWithImage(Request $request) {
        $file = $request->file('image')->getClientOriginalName();
        // $filename = pathinfo($file, PATHINFO_FILENAME); đuôi file
        $filename = date('Y_m_d_H_i_s');
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $filename = "KH_" . $request->name .  $filename . "." . $extension;
        try {
            Customer::where('id', $request->id)
                ->update(['image' => $filename]);
            $path = $request->file('image')->move(public_path("/image/customer"), $filename);
            return response()->json(['status' => 'successful']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed',
                                     'error' => $e]);
        }
    }

    public function deleteCustomerByID($id){
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

    // ---Cart

    public function addToCart(Request $request) {
        DB::beginTransaction();
        try {
            $data = DB::table('carts')
            ->where('idCustomer', $request->idCustomer)
            ->where('idProduct', $request->idProduct)
            ->first();

            if ($data) {
                Cart::where('idCustomer', $request->idCustomer)
                     ->where('idProduct', $request->idProduct)
                     ->update([
                            'unit' => $data->unit + $request->unit,
                            ]);

                $product = Product::where('id', $request->idProduct)->first();
                $check1 = Cart::where('idCustomer', $request->idCustomer)
                                ->where('idProduct', $request->idProduct)->first();

                if ($product->unit - $check1->unit < 0) {
                    return response()->json(['status' => 'failed',
                                     'error' => 'Không đủ số lượng']);
                }
            } else {    
                $cart = new Cart;
                $cart->idCustomer = $request->idCustomer;
                $cart->idProduct = $request->idProduct;
                $cart->unit = $request->unit;
                $cart->save();
            }
            $cart = Cart::where('idCustomer',  $request->idCustomer)->count();
            DB::commit();
            return response()->json(['status' => 'successful',
                                    'cart' => $cart]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed',
                                     'error' => $e]);
        }
    }

    public function showCart($id) {
        try {
            $data = DB::table('carts')
                ->join('products', 'carts.idProduct', '=', 'products.id')
                ->select('carts.idProduct','products.name', 'products.price', 'carts.unit', 'products.discount', 'products.avatar', 'products.unit as max')
                ->where('carts.idCustomer', $id)
                ->get();
            return response()->json(['status' => 'successful11',
                                    'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed',
                                     'error' => $e]);
        }
    }

    public function updateCart (Request $request, $id) {
        DB::beginTransaction();
        try {
            
            $list1 = $request->list_cart;

            foreach ($list1 as $item) {
                Cart::where('carts.idCustomer', $id)
                     ->where('carts.idProduct', $item['idProduct'])
                     ->update([
                            'unit' => $item['unit'],
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

    public function deleteCartCustomerByID($id){
        try {
            DB::table('carts')
              ->where('idCustomer', $id)
              ->delete();
            return response()->json(['status' => 'successful']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

    public function deleteCartByID(Request $request){
        try {
            DB::table('carts')
              ->where('idCustomer', $request->idCustomer)
              ->where('idProduct', $request->idProduct)
              ->delete();
            return response()->json(['status' => 'successful']);
        } catch (Exception $th) {
            return response()->json(['status' => 'failed',
                                     'error' => $th]);
        }
    }

    // --Order
    public function orderCustomer (Request $request) {
        DB::beginTransaction();
        try {
            $date = date("Y-m-d");
            $order = Orders::updateOrCreate(
                ['id' => $request->id],
                [
                    'idUser' => $request->idUser,
                    'idStatus' => '1',
                    'address' => $request->address,
                    'note' => ' ',
                ]
            );
          
            $list1 = $request->list_cart;

            foreach ($list1 as $item) {
                $temp = Product::where('id', $item['idProduct'])->first();
                if ($temp->unit - $item['unit'] > 0) {
                    Product::where('id', $item['idProduct'])->update([
                        'unit' =>  $temp->unit - $item['unit'],
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json(['status' => 'failed1',
                                            'error' => 'Không đủ số lượng']);
                }
                
                Detail_Order::updateOrCreate(
                    [
                        'idOrder' => $order->id,
                        'idProduct' => $item['idProduct'],
                    ],
                    [
                        'unit' => $item['unit'],
                        'price' => $item['price'],
                    ]
                );

                DB::table('carts')
                ->where('idCustomer', $request->idCustomer)
                ->where('idProduct', $item['idProduct'])
                ->delete();
            }

            DB::commit();
            return response()->json(['status' => 'lưu ok']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => $e]);
        }
    }

    // --Rating
    public function ratingCustomer (Request $request)
    {
        DB::beginTransaction();
        try {
            Orders::where(['id' => $request->idOrder, 
                           'idStatus' => 3])
                ->update(['idStatus' => 5]);

            $d_order = Detail_Order::where('idOrder', $request->idOrder)->get();
            foreach ($d_order as $item) {
                Rate::create([
                    'idOrder' => $request->idOrder,
                    'idProduct' => $item->idProduct,
                    'vote' => $request->vote,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'successful']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed1',
                                     'error' => $e]);
        }
    }
}
