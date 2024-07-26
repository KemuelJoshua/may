<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SolutionController;
use App\Models\About;
use App\Models\Carousel;
use App\Models\Client;
use App\Models\OrganizationMember;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    return 'This is for user level';
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::prefix('admin')->middleware(['auth'])->group( function() {
    Route::resource('/students', EmployeeController::class);
});