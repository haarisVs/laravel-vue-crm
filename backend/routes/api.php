<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FilesController;
use Illuminate\Support\Facades\Mail;



Route::get('analytics', fn() => Storage::disk('local')->get('analytics.json'))->middleware('auth:api');

Route::post('file/upload/{table}/{column}', [FilesController::class, 'uploadFile']);
Route::get('file/download', [FilesController::class, 'download']);

Route::get('/email/verify/{id}/{hash}', [UsersController::class, 'verifyEmail'])
    ->middleware(['signed'])->name('verification.verify');

Route::group([
    'middleware' => 'auth:api',
], function() {

    Route::get('users/autocomplete', [UsersController::class, 'findAllAutocomplete']);
    Route::get('users/count', [UsersController::class, 'count']);
    Route::resource('users', UsersController::class);


    Route::get('companies/autocomplete', [CompanyController::class, 'findAllAutocomplete']);
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);

    Route::get('test-email', function () {
        $recipient = 'test@example.com'; // Replace with your own email address

        $subject = 'Sample Mail Subject';
        $fromEmail = 'sender@example.com';
        $fromName = 'Sender Name';
        $emailContent = 'This is a sample email content.';

        Mail::raw($emailContent, function ($message) use ($recipient, $subject, $fromEmail, $fromName) {
            $message->to($recipient)
                ->subject($subject)
                ->from($fromEmail, $fromName);
        });

    });
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function () {
    Route::any('signin/local', [AuthController::class, 'login'])->name('login');
    Route::put('verify-email', [UsersController::class, 'sendVerifyEmail']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('signin/google', [UsersController::class, 'signinGoogle']);
    Route::get('google/callback', [UsersController::class, 'callbackGoogle']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::put('password-update', [AuthController::class, 'passwordUpdate']);
    Route::post('send-password-reset-email', [AuthController::class, 'sendPasswordResetEmail']);
});

