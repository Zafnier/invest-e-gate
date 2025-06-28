<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\AddMoneyController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\AppSettingsController;
use App\Http\Controllers\Api\V1\Auth\AuthorizationController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\DonationController;

Route::name('api.v1.')->group(function () {
    
    // App Settings
    Route::get('languages', [AppSettingsController::class, 'languages']);
    Route::get('basic/settings', [AppSettingsController::class, 'basicSettings']);
    
    // User Authentication
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        Route::get('campaigns', [DashboardController::class, 'campaigns']);
        Route::get('campaign/details', [DashboardController::class, 'campaignDetails']);
        Route::get('about-us', [DashboardController::class, 'aboutUs']);

        // Donation Routes
        Route::controller(DonationController::class)->group(function () {
            // History and Gateways
            Route::get('campaign/donation/history', 'donationHistory');
            Route::get('campaign/donation/gateway', 'donationGateway');
            Route::post('campaign/donation/submit', 'donationSubmit');

            // Success and Cancel Responses
            Route::get('donation/success/response/{gateway}', 'success')->name('donation.payment.success');
            Route::get('donation/cancel/response/{gateway}', 'cancel')->name('donation.payment.cancel');

            // Payment Gateway Integrations
            Route::prefix('donation')->name('donation.')->group(function () {
                // Flutterwave
                Route::get('flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');
                Route::post('flutterwave/confirm', 'flutterwaveConfirmed')->name('flutterwave.confirmed');

                // Stripe
                Route::get('stripe/payment/success/{trx}', 'stripePaymentSuccess')->name('stripe.payment.success');

                // Razorpay
                Route::get('razor/payment/btn/pay/{trx_id}', 'redirectBtnPay')->name('razor.payment.btn.pay');
                Route::post('razor/success/response/{gateway}', 'razorSuccess')->name('razor.payment.success');
                Route::post('razor/cancel/response/{gateway}', 'razorCancel')->name('razor.payment.cancel');
                Route::get('razor/callback', 'razorCallback')->name('razor.callback');

                // SSLCommerz
                Route::post('sslcommerz/success', 'sllCommerzSuccess')->name('ssl.success');
                Route::post('sslcommerz/fail', 'sllCommerzFails')->name('ssl.fail');
                Route::post('sslcommerz/cancel', 'sllCommerzCancel')->name('ssl.cancel');

                // QRPay
                Route::get('qrpay/callback', 'qrpayCallback')->name('qrpay.callback');
                Route::get('qrpay/cancel/{trx_id}', 'qrpayCancel')->name('qrpay.cancel');

                // CoinGate
                Route::match(['get', 'post'], 'coingate/success/response/{gateway}', 'coinGateSuccess')->name('coingate.payment.success');
                Route::match(['get', 'post'], 'coingate/cancel/response/{gateway}', 'coinGateCancel')->name('coingate.payment.cancel');

                // Perfect Money
                Route::get('perfect/success/response/{gateway}', 'perfectSuccess')->name('perfect.success');
                Route::get('perfect/cancel/response/{gateway}', 'perfectCancel')->name('perfect.cancel');

                // Crypto Payments
                Route::prefix('payment')->name('payment.')->group(function () {
                    Route::post('crypto/confirm/{trx_id}', 'cryptoPaymentConfirm')->name('crypto.confirm');
                });
            });

            // Manual Payments
            Route::post('manual/confirmed', 'manualPaymentConfirmedApi')->name('manual.payment.confirmed');
        });

        // Events
        Route::get('events', [DashboardController::class, 'events']);
        Route::get('event/details', [DashboardController::class, 'eventDetails']);

        // Password Management
        Route::prefix('forgot/password')->group(function () {
            Route::post('send/otp', [ForgotPasswordController::class, 'sendCode']);
            Route::post('verify', [ForgotPasswordController::class, 'verifyCode']);
            Route::post('reset', [ForgotPasswordController::class, 'resetPassword']);
        });

        // Add Money
        Route::controller(AddMoneyController::class)->prefix('add-money')->name('add-money.')->group(function () {
            Route::get('success/response/{gateway}', 'success')->name('payment.success');
            Route::get('cancel/response/{gateway}', 'cancel')->name('payment.cancel');
            Route::get('flutterwave/callback', 'flutterwaveCallback')->name('flutterwave.callback');
            Route::get('stripe/payment/success/{trx}', 'stripePaymentSuccess')->name('stripe.payment.success');

            // SSLCommerz
            Route::post('sslcommerz/success', 'sllCommerzSuccess')->name('ssl.success');
            Route::post('sslcommerz/fail', 'sllCommerzFails')->name('ssl.fail');
            Route::post('sslcommerz/cancel', 'sllCommerzCancel')->name('ssl.cancel');

            // QRPay
            Route::get('qrpay/callback', 'qrpayCallback')->name('qrpay.callback');
            Route::get('qrpay/cancel/{trx_id}', 'qrpayCancel')->name('qrpay.cancel');

            // CoinGate
            Route::match(['get', 'post'], 'coingate/success/response/{gateway}', 'coinGateSuccess')->name('coingate.payment.success');
            Route::match(['get', 'post'], 'coingate/cancel/response/{gateway}', 'coinGateCancel')->name('coingate.payment.cancel');

            // Razorpay
            Route::get('razor/payment/btn/pay/{trx_id}', 'redirectBtnPay')->name('razor.payment.btn.pay');
            Route::post('razor/success/response/{gateway}', 'razorSuccess')->name('razor.payment.success');
            Route::post('razor/cancel/response/{gateway}', 'razorCancel')->name('razor.payment.cancel');

            // Perfect Money
            Route::get('perfect/success/response/{gateway}', 'perfectSuccess')->name('perfect.success');
            Route::get('perfect/cancel/response/{gateway}', 'perfectCancel')->name('perfect.cancel');

            // Crypto Payments
            Route::prefix('payment')->name('payment.')->group(function () {
                Route::post('crypto/confirm/{trx_id}', 'cryptoPaymentConfirm')->name('crypto.confirm');
            });
        });

        // Middleware Protected Routes
        Route::middleware(['auth.api'])->group(function () {
            Route::get('logout', [AuthorizationController::class, 'logout']);
            Route::post('otp/verify', [AuthorizationController::class, 'verifyCode']);
            Route::post('resend/code', [AuthorizationController::class, 'resendCode']);

            Route::middleware(['checkStatusApiUser'])->group(function () {
                // Profile
                Route::controller(ProfileController::class)->prefix('profile')->group(function () {
                    Route::get('/', 'profile');
                    Route::post('update', 'profileUpdate')->middleware('app.mode');
                    Route::post('password/update', 'passwordUpdate')->middleware('app.mode');
                    Route::post('delete/account', 'deleteAccount')->middleware('app.mode');
                });

                // Add Money
                Route::controller(AddMoneyController::class)->prefix('add-money')->group(function () {
                    Route::get('information', 'addMoneyInformation');
                    Route::post('submit-data', 'submitData');
                    Route::post('stripe/payment/confirm', 'paymentConfirmedApi')->name('stripe.payment.confirmed');
                    Route::post('manual/payment/confirmed', 'manualPaymentConfirmedApi')->name('manual.payment.confirmed');
                });
            });
        });
    });
});
