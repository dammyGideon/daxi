<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RiderAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Rider\OnlineController;
use App\Http\Controllers\Rider\RiderController;
use App\Http\Controllers\Auth\Admin;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\WalletController;
use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Rider\DocumentController;
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

Route::group(["middleware" => "auth:api"], function() {

    //rider activesties
    Route::post('/online',       [OnlineController::class,'getOnline']);
    Route::post('/profileImages',[ProfileController::class,'profileImages']);
    Route::post('/updateProfie', [ProfileController::class,'updateProfie']);
    Route::post('/idCard',       [DocumentController::class,'indentityCard']);
    Route::post('/licence',      [DocumentController::class,'licence']);

    //searchRider
    Route::get('/searchRide',    [UsersController::class,'searchRider']);

    //make order by user
    Route::post('/makeOrder',    [OnlineController::class,'makeOrder']);
    Route::post('/viewOrder',    [OnlineController::class,'viewOrder']);


    Route::post('/cancelRider',  [UsersController::class,'cancelRider']);
    //notification
    Route::get('/notification',  [UsersController::class,'notification']);

    // my endpoints
    Route::post('/location',     [RiderController::class,'getlocation']);
    Route::post('/otp',          [OtpController::class,'otp']);
    Route::post('/validate',     [RiderAuthController::class,'validateBank']);

    //payment with card
    Route::post('/pay',          [PaymentController::class,'redirectToGateway'])->name('pay');
    Route::post('/payment',      [PaymentController::class,'handleGatewayCallback']);
    //fun wallet
    Route::post('/fund',         [WalletController::class,'fundWallet']);
    Route::post('/response',     [WalletController::class,'response']);
    Route::post('/payWithWallet',[WalletController::class,'payWithWallet']);
    Route::post('/payWithCash',  [WalletController::class,'payWithCash']);
    //pay with cash


    //accept rider from the driver
    Route::post('/acceptOrder',   [RiderController::class,'acceptOrder']);
    Route::post('declineOrder',   [RiderController::class,'declineOrder']);
    //add vehicles
    Route::post('/addVehicle',    [RiderController::class,'addVehicle']);
    Route::get('/vehicle',        [RiderController::class,'vehicle']);
    Route::post('/updateVechicle',[RiderController::class,'updateVechicle']);

    //getuserbyid
    Route::get('/getUserById ',   [UsersController::class, 'getUserById']);
    Route::get('/wallet',         [UsersController::class,'getWallet']);

});


   Route::get('/getbank',         [OnlineController::class, 'bankList']);



//user Authentication
  Route::post('/login',           [LoginController::class, 'login'])->name('api.login');
  Route::post('/userRegister',    [UserAuthController::class,'userRegister']);
//rider Authentication

  Route::post('/login_rider',     [LoginController::class, 'login_rider'])->name('api.login');
  Route::post('/register',        [RiderAuthController::class, 'register'])->name('api.register');

  Route::post('/adminLogin',      [Admin::class,'login']);
  Route::post('/admin',           [RiderAuthController::class,'registerAdmin']);

//Socail Auth
//Route::get('/auth/{google}', 'Api\SocialAuthController@redirectToGoogle')->name('api.social.redirect');
//Route::get('/auth/{google}/callback', 'Api\SocialAuthController@handleSocialCallback')->name('api.google-callback');
//Route::post('/pay', [PaymentController::class, 'redirectToGateway'])->name('api.pay');

Route::get('/login/facebook/callback', [LoginController::class, 'facebookCallback']);
Route::get('/login/google/callback', [LoginController::class, 'googleCallback']);
