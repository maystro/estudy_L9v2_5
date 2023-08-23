<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
php composer.phar install --no-plugins --no-scripts ...
php composer.phar update --no-plugins --no-scripts ...

*/
App::setLocale('ar');
date_default_timezone_set('Africa/Cairo');

Route::get('/', [HomeController::class,'index'])->name('index');
Route::get('/home', [HomeController::class,'redirect'])->name('home');

//Route::get('/changepassword',[ProfileController::class,'hashPassword'])->name('changePassword');
//Route::get('/changeusername',[ProfileController::class,'EncryptNames']);

//Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified',
//    'role:admin'
//])->group(function () {
//
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('admin.dashboard');
//
//});

// ------------------ Facebook & Google Login
Route::get('/auth/{driver}',
    [SocialLoginController::class,'SocialRedirect'])
    ->name('auth.redirect');

Route::get('/auth/{driver}/callback',
    [SocialLoginController::class,'SocialLogin'])
    ->name('auth.callback');
// ------------------ Facebook & Google Login

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:student|subscriber|teacher',
])->group(function () {

// -----------+ Account Routes
    Route::get('/profile/account',
        [UserAccountController::class,'index']
    )->name('profile.account');

    Route::post('/profile/account',
        [UserAccountController::class,'update']
    )->name('profile.account');

    Route::get('/profile/account/security',
        [UserAccountController::class,'security']
    )->name('profile.account.security');

    Route::post('/profile/account/security',
        [UserAccountController::class,'security_update']
    )->name('profile.account.security');

    Route::get('/profile/account/notifications',
        [UserAccountController::class,'notifications']
    )->name('profile.account.notifications');

// -----------+ Account Routes

    Route::get('/profile', function () {
        return view('v2.profile.home');
    })->name('profile');

    Route::get('/profile/subjects/lecturesbyday', [ProfileController::class,'listLecturesByDay'])->name('profile.lecturesByDay');
    //get List of lectures of this subject
    Route::get('/profile/subject/{subject_id}/lectures', [ProfileController::class,'listLectures'])->name('profile.subject.lectures');
    Route::get('/profile/subjects',[ProfileController::class,'listSubjects'])->name('profile.subjects');
    //view mp3 file
    Route::get('/profile/fileview/{file_id}',[ProfileController::class,'fileView'])->name('profile.file.view');
    Route::get('/profile/download/{file_id}', [ProfileController::class,'fileDownload'])->name('profile.file.download');

    //view lecture file
    Route::get('/profile/open/{file_id}',[ProfileController::class,'fileOpen'])->name('profile.file.open');

    //get List of books & media mp3 or mp4
    Route::get('/profile/media/list/{playlist_id}', [ProfileController::class,'viewMediaList'])->name('profile.media.list');
    Route::get('/profile/media/open/{file_id}', [ProfileController::class,'openMedia'])->name('profile.media.open');

    Route::get('/profile/book/list/{playlist_id}', [ProfileController::class,'viewBookList'])->name('profile.book.list');
    Route::get('/profile/books/open/{book_id}', [ProfileController::class,'bookOpen'])->name('profile.book.open');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:teacher',
])->group(function () {

//List Leectures by level id
Route::get('/profile/level/{level_id}/lectures', [ProfileController::class,'listLecturesByLevel'])->name('profile.level.lectures');

});
