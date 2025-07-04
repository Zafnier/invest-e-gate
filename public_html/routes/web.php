<?php

use App\Http\Controllers\Api\V1\AddMoneyController as AddMoneyControllerApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\User\AddMoneyController;
use App\Http\Controllers\Api\V1\DonationController as DonationControllerApi;
use App\Http\Controllers\User\UserRewardController;
use App\Http\Controllers\Admin\AdminRewardController;
use App\Http\Controllers\Admin\VendorController;


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

//landing page
Route::controller(SiteController::class)->group(function(){
    Route::get('/','home')->name('index');
    Route::get('about','about')->name('about');
    Route::get('campaign','campaign')->name('campaign');
    Route::get('campaign/details/{id}/{slug}','campaignDetails')->name('campaign.details');
    Route::get('gallery','gallery')->name('gallery');
    Route::get('events','events')->name('events');
    Route::get('events/details/{id}/{slug}','eventsDetails')->name('events.details');
    Route::get('contact','contact')->name('contact');
    Route::get('download-app','downloadApp')->name('download.app');
    Route::get('page/{slug}','pageView')->name('page.view');
    Route::get('faq','faq')->name('faq');
    Route::post('subscriber','subscriber')->name('subscriber');
    Route::post('contact/store','contactStore')->name('contact.store');
    Route::get('cookie/accept', 'cookieAccept');
    Route::get('cookie/decline', 'cookieDecline');
    Route::post('languages/switch','languageSwitch')->name('languages.switch');
    Route::get('/user/success','pagaditoSuccess')->name('success');
});

// Campaign donation
Route::controller(DonationController::class)->prefix('donation')->name('donation.')->group(function(){
    Route::post('submit','submit')->name('submit');
    // callback
    Route::post("/callback/response/{gateway}",'callback')->name('payment.callback')->withoutMiddleware('web');

    Route::get('success/response/{gateway}','success')->name('payment.success');
    Route::get("cancel/response/{gateway}",'cancel')->name('payment.cancel');
    Route::get('payment/{gateway}','payment')->name('payment');
    Route::post('stripe/payment/confirm','paymentConfirmed')->name('stripe.payment.confirmed');
    // Flutterwave
    Route::get('/flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');
    Route::get('/flutterwave/info', 'flutterwaveInfo')->name('flutterwave.info');
    Route::post('/flutterwave/confirm', 'flutterwaveConfirm')->name('flutterwave.confirm');
    

    // Stripe
    Route::get('stripe/payment/success/{trx}','stripePaymentSuccess')->name('stripe.payment.success');

    // Razor pay
    // Route::get('razor/payment/{trx_id}', 'razorPayment')->name('razor.payment');
    Route::get('razor/payment/btn/pay/{trx_id}', 'redirectBtnPay')->name('razor.payment.btn.pay');
    Route::post('razor-pay/success/{trx_id}','razorSuccess')->name('razor.success');
    Route::post('razor-pay/cancel/{trx_id}', 'razorCancel')->name('razor.cancel');


    // SSL
    Route::post('sslcommerz/success','sllCommerzSuccess')->name('ssl.success');
    Route::post('sslcommerz/fail','sllCommerzFails')->name('ssl.fail');
    Route::post('sslcommerz/cancel','sllCommerzCancel')->name('ssl.cancel');

    // Qrpay gateway
    Route::get('qrpay/callback', 'qrpayCallback')->name('qrpay.callback');
    Route::get('qrpay/cancel/{trx_id}', 'qrpayCancel')->name('qrpay.cancel');

    //coingate
    Route::match(['get','post'],'coingate/success/response/{gateway}','coinGateSuccess')->name('coingate.payment.success');
    Route::match(['get','post'],"coingate/cancel/response/{gateway}",'coinGateCancel')->name('coingate.payment.cancel');


    // Perfect Money
    Route::get('redirect/form/{gateway}', 'redirectUsingHTMLForm')->name('payment.redirect.form')->withoutMiddleware(['auth','verification.guard','user.google.two.factor']);
    Route::get('perfect.success/response/{gateway}','perfectSuccess')->name('perfect.payment.success');
    Route::get("perfect.cancel/response/{gateway}",'perfectCancel')->name('perfect.payment.cancel');

    // Manual
    Route::get('manual/payment','manualPayment')->name('manual.payment');
    Route::post('manual/payment/confirmed','manualPaymentConfirmed')->name('manual.payment.confirmed');

    Route::prefix('payment')->name('payment.')->group(function() {
        Route::get('crypto/address/{trx_id}','cryptoPaymentAddress')->name('crypto.address');
        Route::post('crypto/confirm/{trx_id}','cryptoPaymentConfirm')->name('crypto.confirm');
    });
});

//  Razorpay
Route::controller(DonationControllerApi::class)->prefix('api/donation')->name('api.donation.')->group(function(){
    Route::get('razor-payment/api-link/{trx_id}','razorPaymentLink')->name('razor.payment.link');
});


//for sslcommerz callback urls(web)
Route::controller(AddMoneyController::class)->prefix("add-money")->name("add.money.")->group(function(){
    //sslcommerz
    Route::post('sslcommerz/success','sllCommerzSuccess')->name('ssl.success');
    Route::post('sslcommerz/fail','sllCommerzFails')->name('ssl.fail');
    Route::post('sslcommerz/cancel','sllCommerzCancel')->name('ssl.cancel');

    Route::post("/callback/response/{gateway}",'callback')->name('payment.callback')->withoutMiddleware('web');
});

Route::controller(AddMoneyControllerApi::class)->prefix('api/add-money')->name('api.add.money.')->group(function(){
    Route::get('razor-payment/api-link/{trx_id}','razorPaymentLink')->name('razor.payment.link');
});

// User rewards
  Route::middleware(['auth'])->group(function () {
    Route::get('my-rewards', [UserRewardController::class, 'index'])->name('user.rewards.index'); // Show user rewards
    Route::post('/user/rewards/claim/{id}', [UserRewardController::class, 'claim'])->name('user.rewards.claim');
    Route::post('my-rewards/assign', [UserRewardController::class, 'assignRewards'])->name('user.rewards.assign'); // Assign eligible rewards
    Route::get('/user/rewards/details/{id}', [UserRewardController::class, 'showRewardDetails'])->name('user.rewards.details');

});

// Admin rewards
Route::middleware(['auth', 'admin'])->prefix('admin/rewards')->name('admin.rewards.')->group(function () {
    Route::get('/', [AdminRewardController::class, 'index'])->name('index'); // Show all rewards
    Route::get('/create', [AdminRewardController::class, 'create'])->name('create'); // Show create reward form
    Route::post('/', [AdminRewardController::class, 'store'])->name('store'); // Store new reward
    Route::get('/{id}/edit', [AdminRewardController::class, 'edit'])->name('edit'); // Show edit form
    Route::put('/{id}', [AdminRewardController::class, 'update'])->name('update'); // Update reward
    Route::delete('/{id}', [AdminRewardController::class, 'destroy'])->name('destroy'); // Delete reward
});

//Vendor Registration
Route::middleware(['auth', 'admin'])->prefix('admin/vendors')->name('admin.vendors.')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('index');  // Show all vendors
    Route::get('/create', [VendorController::class, 'create'])->name('create'); // Show create vendor form
    Route::post('/', [VendorController::class, 'store'])->name('store'); //Store new vendor
    Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit'); // Show edit form
    Route::put('/{id}', [VendorController::class, 'update'])->name('update'); // Update reward
    Route::delete('/{id}', [VendorController::class, 'destroy'])->name('destroy'); // Delete reward
});

