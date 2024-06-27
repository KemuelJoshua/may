<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\ClientController;
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

Route::redirect('/', '/about-us');

Route::get('/about-us', function() {
    $about = About::first();
    $carousel = Carousel::where('isActive', true)->get();
    $members = OrganizationMember::all();
    return view('user.about-us', compact('about', 'carousel', 'members'));
})->name('about-us');

Route::get('/solutions', function() {
    $about = About::first();
    $solutions = \App\Models\Solution::where('isActive', true)->get();
    return view('user.solutions', compact('solutions', 'about'));
})->name('solutions');

Route::get('/services', function() {
    $about = About::first();
    $services = \App\Models\Service::where('isActive', true)->get();
    return view('user.services', compact('services', 'about'));
})->name('services');

Route::get('/clients', function() {
    $about = About::first();
    $clients = \App\Models\Client::where('isActive', true)->get();
    return view('user.clients', compact('clients', 'about'));
})->name('clients');

Route::get('/partners', function() {
    $about = About::first();
    $partners = \App\Models\Partner::where('isActive', true)->get();
    return view('user.partners',  compact('partners', 'about'));
})->name('partners');

Route::get('/contact', function() {
    $about = About::first();
    return view('user.contact', compact('about'));
})->name('contact');

Route::post('/send-email', [MailController::class, 'sendMail']);

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::prefix('admin')->middleware(['auth'])->group( function() {
    Route::resource('/about-us', AboutController::class)->only('index', 'update');
    Route::resource('/organizational-chart', OrganizationMemberController::class)->except('show');
    Route::get('/get-members', [OrganizationMemberController::class, 'getMembers']);
    Route::resource('/carousels', CarouselController::class)->except('show');
    Route::resource('/solutions', SolutionController::class)->except('show');
    Route::resource('/services', ServiceController::class)->except('show');
    Route::resource('/partners', PartnerController::class)->except('show');
    Route::resource('/clients', ClientController::class)->except('show');
});