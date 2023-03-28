<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
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
return view('index');
});


Route::get('/login', [AuthController::class,'loginCheck']);

Route::get('/signup', [AuthController::class,'signupCheck']);

Route::post('/login',[AuthController::class,'LogIn'])->name('login');
Route::post('/signup',[AuthController::class,'SignUp'])->name('signup');

Route::post('/verify',[AuthController::class,'VerifyMail']);
Route::get('/logout_now', [AuthController::class,'LogOut']);
Route::get('/dashboard', [AuthController::class,'dashboardCheck'])->name('dashboard');;
Route::get('dashboard/packages', [AuthController::class,'packagesCheck']);

Route::get('/displayAvailablePackages', [UserDashboardController::class,'displayAvailablePackages']);
Route::get('/displayActivePackages', [UserDashboardController::class,'displayActivePackages']);
Route::get('/displayRequestedPackages', [UserDashboardController::class,'displayRequestedPackages']);

