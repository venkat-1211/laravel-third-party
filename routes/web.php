<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationUserManagement\SocialLoginController;
use App\Http\Controllers\SearchController;


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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [SocialLoginController::class, 'index'])->name('social.index');
Route::get('/home', [SocialLoginController::class, 'home'])->name('home');
Route::get('login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');


// Twilio otp
use App\Http\Controllers\Auth\OtpController;

// Route::get('/otp', [OtpController::class, 'index']);
// Route::post('/otp/send', [OtpController::class, 'send']);
// Route::post('/otp/verify', [OtpController::class, 'verify']);
// Route::post('/otp/resend', [OtpController::class, 'resend']);

// Msg91Otp
use App\Http\Controllers\MsgOtpController;

// Route::get('/otp', function () {
//     return view('msg1.otp');
// });

// Route::post('/send-otp', [MsgOtpController::class, 'sendOtp']);
// Route::post('/verify-otp', [MsgOtpController::class, 'verifyOtp']);

// 2Factor.in
// use App\Http\Controllers\TwoFactorController;

// Route::get('/otp', [TwoFactorController::class, 'index']);
// Route::post('/otp/send', [TwoFactorController::class, 'sendOtp']);   // this api.php inside move
// Route::post('/otp/verify', [TwoFactorController::class, 'verifyOtp']); // this api.php inside move

// Fast 2sms
// use App\Http\Controllers\Fast2SmsController;

// Route::get('/send-otp', [Fast2SmsController::class, 'showSendForm'])->name('otp.send.form');
// Route::post('/send-otp', [Fast2SmsController::class, 'sendOtp'])->name('otp.send');

// Route::get('/verify-otp', [Fast2SmsController::class, 'showVerifyForm'])->name('otp.verify.form');
// Route::post('/verify-otp', [Fast2SmsController::class, 'verifyOtp'])->name('otp.verify');


// // Send Grid Sms
// use App\Mail\SendGridMail;
// use Illuminate\Support\Facades\Mail;

// Route::get('/send-test-email', function () {
//     Mail::to('20venkatesh001@gmail.com')->send(new SendGridMail());
//     return "Email Sent!";
// });

// FCM PUSH NOTIFICATION
use App\Http\Controllers\FcmController;

Route::get('/send-notification', [FcmController::class, 'index']);
Route::post('/send-notification', [FcmController::class, 'send']);


// SETU Government File verification
use App\Http\Controllers\SetuDigilockerController;

Route::get('/', function() {
    return view('setu-login');
});

Route::get('/setu/login', [SetuDigilockerController::class, 'initiateDigiLocker'])->name('setu.login');
Route::get('/setu/callback', [SetuDigilockerController::class, 'handleCallback'])->name('setu.callback');
Route::post('/verify-aadhaar', [SetuDigilockerController::class, 'verifyAadhaar'])->name('aadhaar.verify');

// Type Form
use App\Http\Controllers\TypeformController;


Route::get('/typeform1', function() {
    return view('typeform2');
});
Route::get('/typeform', [TypeformController::class, 'index']);
Route::get('/typeform/responses', [TypeformController::class, 'getResponses']);
Route::post('/typeform/submit', [TypeformController::class, 'submitResponse']);

// Github automation
use App\Http\Controllers\GithubController;

Route::get('/github/create/{name}', [GithubController::class, 'createRepo']);

Route::get('/github/list', [GithubController::class, 'listRepos']);

Route::get('/github/delete/{repo}', [GithubController::class, 'deleteRepo']);

Route::get('/github/readme/{repo}', [GithubController::class, 'createReadme']);

Route::get('/github/fork/{owner}/{repo}', [GithubController::class, 'fork']);

Route::get('/github/branch/{repo}/{branch}', [GithubController::class, 'createBranch']);

Route::get('/github/pr/{repo}', [GithubController::class, 'pullRequest']);

Route::get('/github/commit-push/{repo}', [GithubController::class, 'commitPush']);  // add commit push => itha run pannathum intha moonume run aakidum.

// Elasticsearch
Route::get('/search', [SearchController::class, 'search'])->name('search');







