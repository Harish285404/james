<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
      return view('auth.login');
});

Route::get('not-found', function () {
      return view('notfound');
});



Auth::routes();

Route::get('user-quotataion/{id}', [App\Http\Controllers\HomeController::class, 'user_quotataion']);

Route::post('user-quotataion-check', [App\Http\Controllers\HomeController::class, 'user_quotataion_check']);

Route::get('reject-quotataion/{id}', [App\Http\Controllers\HomeController::class, 'user_R_quotataion']);

Route::post('users-quote-check', [App\Http\Controllers\HomeController::class, 'users_quote_check']);



Route::group(['middleware' => 'admin'], function () {
      Route::get('admin', [App\Http\Controllers\Admin\AdminController::class, 'admin']);
      Route::get('admin/add-category', [App\Http\Controllers\Admin\AdminController::class, 'addcategory']);
      Route::post('admin/add_category', [App\Http\Controllers\Admin\AdminController::class, 'add_category']);
      Route::get('admin/category-list', [App\Http\Controllers\Admin\AdminController::class, 'categorylist']);
      Route::get('admin/get-category', [App\Http\Controllers\Admin\AdminController::class, 'get_category']);
      Route::get('admin/edit-category/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editcategory']);
      Route::post('admin/update-category', [App\Http\Controllers\Admin\AdminController::class, 'update_category']);
      Route::get('admin/single-category/{id}', [App\Http\Controllers\Admin\AdminController::class, 'singlecategoryview']);
      Route::get('admin/delete-category/{id}', [App\Http\Controllers\Admin\AdminController::class, 'delete_Category']);
      Route::get('admin/add-product', [App\Http\Controllers\Admin\AdminController::class, 'addproduct']);
       Route::post('admin/add_product', [App\Http\Controllers\Admin\AdminController::class, 'add_product']);
      Route::get('admin/get-product', [App\Http\Controllers\Admin\AdminController::class, 'get_Product']);
      Route::get('admin/edit-product/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editproduct']);
       Route::post('admin/update-product', [App\Http\Controllers\Admin\AdminController::class, 'update_product']);
      Route::get('admin/product-list', [App\Http\Controllers\Admin\AdminController::class, 'productlist']);
      Route::get('admin/single-product/{id}', [App\Http\Controllers\Admin\AdminController::class, 'singleproduct']);
       Route::get('admin/delete-product/{id}', [App\Http\Controllers\Admin\AdminController::class, 'delete_product']);
         Route::get('admin/report-update/{id}', [App\Http\Controllers\Admin\AdminController::class, 'report_update']);
      Route::get('admin/stocklist', [App\Http\Controllers\Admin\AdminController::class, 'stocklist']);
      Route::get('admin/userlist', [App\Http\Controllers\Admin\AdminController::class, 'userlist']);
      Route::get('admin/getData', [App\Http\Controllers\Admin\AdminController::class, 'getData']);
      Route::get('admin/reports', [App\Http\Controllers\Admin\AdminController::class, 'reports']);
      Route::get('admin/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile']);
      Route::post('admin/profile-update', [App\Http\Controllers\Admin\AdminController::class, 'update']);
      Route::get('admin/change-password', [App\Http\Controllers\Admin\AdminController::class, 'changepassword']);
      Route::post('admin/forgot-password', [App\Http\Controllers\Admin\AdminController::class, 'forgotpassword']);
      Route::get('admin/generate-pdf', [App\Http\Controllers\Admin\AdminController::class, 'generatePDF']);
        Route::get('admin/reconciliation-pdf', [App\Http\Controllers\Admin\AdminController::class, 'ReconciliationPDF']);
          Route::get('admin/Inventory-pdf', [App\Http\Controllers\Admin\AdminController::class, 'InventoryPDF']);
 Route::get('admin/subcategory', [App\Http\Controllers\Admin\AdminController::class, 'subcategory']);
  Route::get('admin/Sales-pdf', [App\Http\Controllers\Admin\AdminController::class, 'SalesPDF']);
 Route::get('admin/orders', [App\Http\Controllers\Admin\AdminController::class, 'get_orders']);
 Route::get('admin/get-inventory', [App\Http\Controllers\Admin\AdminController::class, 'showproduct']);
  Route::post('admin/store-inventory', [App\Http\Controllers\Admin\AdminController::class, 'storeadjustment']);
  Route::get('admin/get-sales', [App\Http\Controllers\Admin\AdminController::class, 'get_sales']);
   Route::get('admin/overview-filerdata-expenses', [App\Http\Controllers\Admin\AdminController::class, 'overview_filerdata_expenses']);
  Route::get('admin/asubcategory', [App\Http\Controllers\Admin\AdminController::class, 'addsubcategory']);
   Route::get('admin/bundle-product/{id}', [App\Http\Controllers\Admin\AdminController::class, 'singlebundleproduct']);
   
    Route::get('admin/edit-bundle-product/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editbundleproduct']);
   
   
     Route::post('admin/add-product-data', [App\Http\Controllers\Admin\AdminController::class, 'add_group_product']);
     
      Route::get('admin/gsubcategory', [App\Http\Controllers\Admin\AdminController::class, 'addgroupsubcategory']);
      
       Route::get('admin/bsubcategory', [App\Http\Controllers\Admin\AdminController::class, 'editsubcategory']);
  
      Route::get('logout', [App\Http\Controllers\User\UserController::class, 'signout']);

 Route::get('admin/add-bundle-product', [App\Http\Controllers\Admin\AdminController::class, 'add_grouped_product']);
     Route::post('admin/update-bundle-product', [App\Http\Controllers\Admin\AdminController::class, 'update_bundle_product']);
Route::get('admin/orders', [App\Http\Controllers\Admin\AdminController::class, 'get_orders']);
Route::get('admin/orders2', [App\Http\Controllers\Admin\AdminController::class, 'get_orders2']);

Route::get('admin/quatation', [App\Http\Controllers\Admin\AdminController::class, 'quatation']);
Route::post('admin/add-quatation', [App\Http\Controllers\Admin\AdminController::class, 'add_quatation']);

Route::get('admin/invoice-list', [App\Http\Controllers\Admin\AdminController::class, 'pending_quatation_list']);

Route::get('admin/quatation-list', [App\Http\Controllers\Admin\AdminController::class, 'quatation_list']);
Route::get('admin/reject-quatation-list', [App\Http\Controllers\Admin\AdminController::class, 'reject_quatation_list']);
Route::get('admin/quatation-pdf/{id}', [App\Http\Controllers\Admin\AdminController::class, 'quatation_pdf']);

Route::get('admin/quatation-product', [App\Http\Controllers\Admin\AdminController::class, 'quatation_product']);

Route::get('admin/invoice-pdf/{id}', [App\Http\Controllers\Admin\AdminController::class, 'invoice_pdf']);

Route::get('admin/edit-quote/{id}', [App\Http\Controllers\Admin\AdminController::class, 'edit_quote']);
Route::post('admin/update-quote/', [App\Http\Controllers\Admin\AdminController::class, 'update_quote']);

Route::get('admin/invoice', [App\Http\Controllers\Admin\AdminController::class, 'invoice']);

Route::post('admin/add-invoice', [App\Http\Controllers\Admin\AdminController::class, 'add_invoice']);

Route::get('admin/purchase', [App\Http\Controllers\Admin\AdminController::class, 'purchase']);

Route::post('admin/add-purchase', [App\Http\Controllers\Admin\AdminController::class, 'add_purchase']);

Route::get('admin/purchase-product', [App\Http\Controllers\Admin\AdminController::class, 'purchase_product']);

Route::get('admin/purchase-list', [App\Http\Controllers\Admin\AdminController::class, 'purchase_list']);

Route::get('admin/edit-purchase/{id}', [App\Http\Controllers\Admin\AdminController::class, 'edit_purchase']);

Route::post('admin/update-purchase/', [App\Http\Controllers\Admin\AdminController::class, 'update_purchase']);

Route::get('admin/purchase-pdf/{id}', [App\Http\Controllers\Admin\AdminController::class, 'purchase_pdf']);

});






Route::group(['middleware' => 'user'], function () {
      Route::get('user', [App\Http\Controllers\User\UserController::class, 'user']);
      Route::get('logoutt', [App\Http\Controllers\User\UserController::class, 'signout']);

      Route::get('category-list', [App\Http\Controllers\User\UserController::class, 'categorylist']);

      Route::get('single-category', [App\Http\Controllers\User\UserController::class, 'singlecategoryview']);
     

      Route::get('product-list', [App\Http\Controllers\User\UserController::class, 'productlist']);
      Route::get('single-product', [App\Http\Controllers\User\UserController::class, 'singleproduct']);
      Route::get('stocklist', [App\Http\Controllers\User\UserController::class, 'stocklist']);

      Route::get('reports', [App\Http\Controllers\User\UserController::class, 'reports']);
      Route::get('profile-view', [App\Http\Controllers\User\UserController::class, 'profile']);
       Route::post('profile-update', [App\Http\Controllers\User\UserController::class, 'update']);
      Route::get('change-password', [App\Http\Controllers\User\UserController::class, 'changepassword']);
      Route::post('forgot-password', [App\Http\Controllers\User\UserController::class, 'forgotpassword']);

  
});
