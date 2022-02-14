<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Api')->name('api.')->group(function () {
    Route::get('unauthenticate', 'BasicController@unauthenticate')->name('unauthenticate');
    Route::get('languages', 'BasicController@languages');
    Route::get('language-data/{code}', 'BasicController@languageData');

    Route::namespace('Auth')->group(function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');
        Route::post('password/email', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode');
        Route::post('password/reset', 'ResetPasswordController@reset');
    });


    Route::middleware('auth.api:sanctum')->name('user.')->prefix('user')->group(function () {
        Route::get('logout', 'Auth\LoginController@logout');
        Route::get('authorization', 'AuthorizationController@authorization')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatusApi'])->group(function () {
            Route::get('dashboard', function () {
                return auth()->user();
            });
            Route::post('profile', 'UserController@submitProfile')->name('profile.submit');

        });
    });

    Route::middleware('auth.api:sanctum')->group(function () {

        Route::get('users', 'UserController@allUsers')->name('users.all');
        Route::get('users/active', 'UserController@activeUsers')->name('users.active');
        Route::get('users/banned', 'UserController@bannedUsers')->name('users.banned');
        Route::get('users/email-unverified', 'UserController@emailUnverifiedUsers')->name('users.emailUnverified');
        Route::get('users/sms-unverified', 'UserController@smsUnverifiedUsers')->name('users.smsUnverified');

        Route::get('users/{scope}/search', 'UserController@search')->name('users.search');
        Route::get('user/detail/{id}', 'UserController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'UserController@update')->name('users.update');
        Route::post('user/send-email/{id}', 'UserController@sendEmailSingle')->name('users.email.single');

        // Login History
        Route::get('users/login/history/{id}', 'UserController@userLoginHistory')->name('users.login.history.single');
        Route::get('users/login/history', 'UserController@loginHistory')->name('users.login.history');
        Route::get('users/login/ipHistory/{ip}', 'UserController@loginIpHistory')->name('users.login.ipHistory');

        Route::get('users/send-email', 'UserController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'UserController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'UserController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'UserController@emailDetails')->name('users.email.details');

        Route::prefix('ticket')->group(function () {
            Route::get('/', 'TicketController@supportTicket')->name('ticket');
            Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
            Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
            Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
            Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
        });

        Route::prefix('setting')->group(function () {
            Route::get('/', 'GeneralSettingController@index')->name('setting.index');
            Route::post('/update', 'GeneralSettingController@update')->name('setting.update');
        });

    });
});
