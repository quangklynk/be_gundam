<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function() {

    //---Blog
    Route::get('/blog', 'EmployeeController\BlogController@getBlog')->middleware('scope:employee,admin');
    Route::post('/blog', 'EmployeeController\BlogController@createBlog')->middleware('scope:admin,employee');
    Route::post('/blog/update/info', 'EmployeeController\BlogController@updateBlogWithNotImage')->middleware('scope:admin,employee');
    Route::post('/blog/update/image', 'EmployeeController\BlogController@updateBlogWithImage')->middleware('scope:admin,employee');
    Route::get('/blog/{id}', 'EmployeeController\BlogController@getBlogByID')->middleware('scope:admin,employee');
    Route::delete('/blog/{id}', 'EmployeeController\BlogController@deleteBlogByID')->middleware('scope:admin,employee');

    // ---Cart
    Route::get('/cart/v1', 'CustomerController\CartController@getCart')->middleware('scope:customer');
    Route::post('/cart/v1', 'CustomerController\CustomerController@addToCart')->middleware('scope:customer');
    Route::get('/cart/v1/{id}', 'CustomerController\CustomerController@showCart')->middleware('scope:customer');
    Route::delete('/cart/v1/{id}', 'CustomerController\CustomerController@deleteCartCustomerByID')->middleware('scope:customer');
    Route::post('/cart/v1/row', 'CustomerController\CustomerController@deleteCartByID')->middleware('scope:customer');
    Route::patch('/cart/v1/update/{id}', 'CustomerController\CustomerController@updateCart')->middleware('scope:customer');

    //---Slide
    Route::get('/slide', 'EmployeeController\SlideController@getSlide')->middleware('scope:admin,employee');
    Route::post('/slide', 'EmployeeController\SlideController@createSlide')->middleware('scope:admin,employee');
    Route::get('/slide/{id}', 'EmployeeController\SlideController@getSlideByID')->middleware('scope:admin,employee');
    Route::delete('/slide/{id}', 'EmployeeController\SlideController@deleteSlideByID')->middleware('scope:admin,employee');

    //---Category 
    Route::get('/category', 'EmployeeController\CategoryController@getCategory')->middleware('scope:admin,employee');
    Route::post('/category', 'EmployeeController\CategoryController@createCategory')->middleware('scope:admin');
    Route::get('/category/{id}', 'EmployeeController\CategoryController@getCategoryByID')->middleware('scope:admin,employee');
    Route::delete('/category/{id}', 'EmployeeController\CategoryController@deleteCategoryByID')->middleware('scope:admin');

    //---Distributor 
    Route::get('/distributor', 'EmployeeController\DistributorController@getDistributor')->middleware('scope:admin');
    Route::post('/distributor', 'EmployeeController\DistributorController@createDistributor')->middleware('scope:admin');
    Route::get('/distributor/{id}', 'EmployeeController\DistributorController@getDistributorByID')->middleware('scope:admin');
    Route::delete('/distributor/{id}', 'EmployeeController\DistributorController@deleteDistributorByID')->middleware('scope:admin');

    //---List_Image 
    Route::get('/list_image', 'EmployeeController\List_ImageController@getList_Image');
    Route::post('/list_image', 'EmployeeController\List_ImageController@createList_Image');
    Route::get('/list_image/{id}', 'EmployeeController\List_ImageController@getList_ImageByID');
    Route::delete('/list_image/{id}', 'EmployeeController\List_ImageController@deleteList_ImageByID');

    //---Product 
    Route::get('/product', 'EmployeeController\ProductController@getProduct');
    Route::post('/product', 'EmployeeController\ProductController@createProduct');
    Route::get('/product/{id}', 'EmployeeController\ProductController@getProductByID');
    Route::delete('/product/{id}', 'EmployeeController\ProductController@deleteProductByID');
    Route::get('/product/back/{id}', 'EmployeeController\ProductController@backProductByID');
    Route::post('/product/updateimage/v1', 'TestController@updateProductImage');
    Route::patch('/product/updateinfo/{id}', 'EmployeeController\ProductController@updateProductWithNotImage');    

    // ---Order
    Route::get('/order', 'EmployeeController\OrderController@getAllOrder')->middleware('scope:admin,employee');
    Route::patch('/order/confirm/{id}', 'EmployeeController\OrderController@confirmOrder')->middleware('scope:admin,employee');
    Route::patch('/order/complete/{id}', 'EmployeeController\OrderController@completeOrder')->middleware('scope:admin,employee');
    Route::patch('/order/cancel/{id}', 'EmployeeController\OrderController@cancelOrder')->middleware('scope:admin,employee');

    // ---EnterSticker
    Route::post('/entersticker', 'EmployeeController\EnterStickerController@enterSticker');
    Route::get('/entersticker', 'EmployeeController\EnterStickerController@getEnterSticker');
    Route::get('/entersticker/product', 'EmployeeController\ProductController@getProductImport');
    Route::patch('/entersticker/{id}', 'EmployeeController\EnterStickerController@moveDetailsToProduct');
    Route::delete('/entersticker/{id}', 'EmployeeController\EnterStickerController@deleteEnterStickerByID');
    
    //---Status 
    Route::get('/status', 'EmployeeController\StatusController@getStatus')->middleware('scope:admin');
    Route::post('/status', 'EmployeeController\StatusController@createStatus')->middleware('scope:admin');
    Route::get('/status/{id}', 'EmployeeController\StatusController@getStatusByID')->middleware('scope:admin');
    Route::delete('/status/{id}', 'EmployeeController\StatusController@deleteStatusByID')->middleware('scope:admin');

    //---Role 
    Route::get('/role', '_AuthController\RoleController@getRole')->middleware('scope:admin');
    Route::post('/role', '_AuthController\RoleController@createRole')->middleware('scope:admin');
    Route::delete('/role/{id}', '_AuthController\RoleController@deleteRoleByID')->middleware('scope:admin');
  

    //---Employee 
    Route::get('/employee', 'EmployeeController\EmployeeController@getEmployee')->middleware('scope:admin');
    Route::post('/employee', 'EmployeeController\EmployeeController@updateEmployeeWithNotImage')->middleware('scope:admin');
    Route::post('/register', '_AuthController\RegisterController@register')->middleware('scope:admin');
    Route::delete('/employee/{id}', 'EmployeeController\EmployeeController@deleteEmployeeByID')->middleware('scope:admin');
    Route::get('/employee/back/{id}', '_AuthController\RegisterController@backAccountByID')->middleware('scope:admin');
    Route::get('/employee/{id}', 'EmployeeController\EmployeeController@getEmployeeByID')->middleware('scope:admin,employee');
    Route::post('/employee/updateinfo', 'EmployeeController\EmployeeController@updateEmployeeWithNotImage')->middleware('scope:admin');
    Route::post('/employee/updateimg', 'EmployeeController\EmployeeController@updateEmployeeWithImage')->middleware('scope:admin');

    // ---Statistical
    Route::get('/statistical', 'EmployeeController\StatisticalController@statistical')->middleware('scope:admin,employee');
    Route::get('/monthlyrevenue', 'EmployeeController\StatisticalController@monthlyRevenue')->middleware('scope:admin,employee');
    Route::get('/weeklyrevenue', 'EmployeeController\StatisticalController@weeklyRevenue')->middleware('scope:admin,employee');
    Route::get('/revenue', 'EmployeeController\StatisticalController@revenue')->middleware('scope:admin,employee');

    // ---Customer for Admin
    Route::get('/customer', 'CustomerController\CustomerController@getCustomer')->middleware('scope:admin');
    Route::delete('/customer/v1/delete/{id}', 'CustomerController\CustomerController@deleteCustomerByID')->middleware('scope:admin');
    Route::post('/customer/v1/back/{id}', '_AuthController\RegisterController@backAccountByID')->middleware('scope:admin');


    //---ChangePass
    Route::post('/change', '_AuthController\RegisterController@changepass')->middleware('scope:admin,employee');
    Route::post('/change/customer', '_AuthController\RegisterController@changepassCusomter')->middleware('scope:customer');

    //---Logout
    Route::post('/logout', '_AuthController\LoginController@logout');


    // ---------------------------------------------------------------------------------------------------------

    // -------API Customer

    Route::get('/customer/{id}', 'CustomerController\CustomerController@getCustomerByID')->middleware('scope:customer');
    Route::post('/customer/updateinfo', 'CustomerController\CustomerController@updateCustomerWithNotImage')->middleware('scope:customer');
    Route::post('/customer/updateimg', 'CustomerController\CustomerController@updateCustomerWithImage')->middleware('scope:customer');
    Route::post('/customer/v1/order', 'CustomerController\OrderController@orderCustomer')->middleware('scope:customer');
    Route::get('/customer/v1/order/{id}', 'CustomerController\OrderController@getOrder')->middleware('scope:customer');
    Route::get('/customer/v1/order/cancel/{id}', 'CustomerController\OrderController@cancelOrder')->middleware('scope:customer');
    Route::post('/customer/v1/rating/', 'CustomerController\CustomerController@ratingCustomer')->middleware('scope:customer');
});

Route::get('/slide/v1/customer', 'EmployeeController\SlideController@getSlide');
Route::get('/blog/v1/customer', 'EmployeeController\BlogController@getBlog');
Route::get('/product/v1/home', 'EmployeeController\ProductController@getProductHomePage');
Route::get('/category/v1/customer', 'EmployeeController\DistributorController@getDistributorCustomer');
Route::get('/product/v1/customer/{id}', 'EmployeeController\ProductController@getProductByIDForCustomer');
//------Search
Route::get('/customer/search/{id}', 'EmployeeController\ProductController@sreachByCate');
Route::post('/customer/searchprice', 'EmployeeController\ProductController@sreachByPrice');
Route::post('/customer/searchname', 'EmployeeController\ProductController@sreachByName');

//---Login Employee
Route::post('/login', '_AuthController\LoginController@login');

//---Login Customer
Route::post('/login/customer', '_AuthController\LoginController@loginCustomer');
Route::post('/register/customer', '_AuthController\RegisterController@registerCustomer');

//--- Forgot and reset
Route::post('/forgot', '_AuthController\ForgotController@forgot');
Route::post('/reset', '_AuthController\ForgotController@reset');
//---Mail test
Route::post('/mail', function (Request $request) {
    try {
        $details = [
            'name' => $request->name,
            'email' =>  $request->email,
            'content' => $request->content
        ];
        \Mail::to('quangklynh@gmail.com')->send(new \App\Mail\SendMailContract($details));
        return response()->json(['status' => 'successful']);
    } catch (\Exception $e) {
        return response($e->getMessage(), 422);
    }
});
