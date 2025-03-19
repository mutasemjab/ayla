<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Api\v1\User\ReceivableController;
use App\Http\Controllers\Api\v1\User\SectionUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\OrderController;
use App\Http\Controllers\Api\v1\User\TransferBalanceController;
use App\Http\Controllers\Api\v1\User\FavouriteController;
use App\Http\Controllers\Api\v1\User\CategoryController;
use App\Http\Controllers\Api\v1\User\RequestBalanceController;
use App\Http\Controllers\Api\v1\User\PayInvoiceController;
use App\Http\Controllers\Api\v1\User\TransferBankController;
use App\Http\Controllers\Api\v1\User\WalletController;
use App\Http\Controllers\Api\v1\User\BannerController;
use App\Http\Controllers\Api\v1\User\CustomerController;
use App\Http\Controllers\Api\v1\User\TransferController;
use App\Http\Controllers\Api\v1\User\SettingController;
use App\Http\Controllers\Api\v1\User\ScanCardController;
use App\Http\Controllers\Api\v1\User\QuestionController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route unAuth
Route::group(['prefix' => 'v1/user'], function () {

    //---------------- Auth --------------------//
    Route::get('/sectionOfUser', [SectionUserController::class, 'index']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


    //Route unAuth
    Route::get('/offers', [ProductController::class, 'offers']); // Done

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);


    Route::get('/questions', [QuestionController::class, 'index']);
    
    //Wallet of admin
    Route::get('/walletOfAdmin', [WalletController::class, 'walletOfAdmin']);

   





    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {

        Route::post('/update_profile', [AuthController::class, 'updateProfile']);
        Route::post('/deleteAccount', [AuthController::class, 'deleteAccount']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']); // Done
        Route::get('/pages/{type}', [PageController::class,'index']);


          //----------------- Products ------------------------------//
        Route::get('/products', [ProductController::class, 'index']); // Done
        Route::get('/products/{id}', [ProductController::class, 'productDetails']); // Done

        //Category product
        Route::get('/categories/{id}/products',  [CategoryController::class, 'getProducts']); // Done
        Route::get('/categories', [CategoryController::class, 'index']); // Done

        // Define routes for orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/orders/notPrint', [OrderController::class, 'notPrint']);

        //Notification
        Route::get('/notifications', [NotificationController::class, 'notifications']);
        Route::get('/index', [NotificationController::class, 'index']);
        Route::get('/forAdmin', [NotificationController::class, 'forAdmin']);

        //--------------- Favourite ------------------------//
        Route::get('/favourites', [FavouriteController::class, 'index']); // Done
        Route::post('/favourites', [FavouriteController::class, 'store']); // Done

        //RequestBalance
        Route::post('/requestBalance', [RequestBalanceController::class, 'store']); // Done

        //TransferBalance
        Route::post('/initiateTransfer', [TransferBalanceController::class, 'initiateTransfer']); // Done
        Route::post('/transfer', [TransferBalanceController::class, 'transfer']); // Done

        //PayInvoice
        Route::get('/displayCategoriesSubscription', [PayInvoiceController::class, 'displayCategoriesSubscription']); // Done
        Route::post('/submitSubscription', [PayInvoiceController::class, 'submitSubscription']); // Done

        //TransferBank
        Route::post('/submitTransfer', [TransferBankController::class, 'submitTransfer']); // Done

        //Wallet
        Route::get('/wallet', [WalletController::class, 'index']);
        Route::get('/walletTransaction/{wallet_id}', [WalletController::class, 'walletTransaction']);
        Route::get('/banners', [BannerController::class, 'index']);

        Route::post('/note-vouchers', [ScanCardController::class, 'store']);

        //Api Dealer

        Route::get('/cardPackages',[CustomerController::class,'getCardPackages']);


        Route::get('/customer/search', [CustomerController::class, 'search']);
        Route::get('/customer/index',[CustomerController::class,'index']);
        Route::post('/customerStore',[CustomerController::class,'store']);
        Route::get('/customer/edit/{id}',[CustomerController::class,'edit']);
        Route::post('/customer/update/{id}',[CustomerController::class,'update']);


        Route::post('/transferToCustomer/getUsersOFThisDealer',[TransferController::class,'search']);
        Route::post('/transferToCustomer/store',[TransferController::class,'store']);
        Route::get('/requestBalanceFromUser', [RequestBalanceController::class, 'index']); // Done
        Route::get('/requestBalanceFromUser/{id}/approve', [RequestBalanceController::class, 'approve']);
        Route::get('/requestBalanceFromUser/{id}/reject', [RequestBalanceController::class, 'reject']);
        Route::post('/requestBalanceFromUser/report', [RequestBalanceController::class, 'reportForDelear']);


        Route::get('/receivable', [ReceivableController::class, 'index']);
        Route::post('/receivable/update/{id}', [ReceivableController::class, 'update']);

    });
});
