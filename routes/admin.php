<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

App::setLocale('ar');
date_default_timezone_set('Africa/Cairo');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:admin'
])->group(function () {
    Route::get('/admin/dashbaord',
        [AdminController::class,'dashboard'])->name('admin.dashboard');

    Route::get('/admin/users',
        [AdminController::class,'usersList'])->name('admin.users.list');

    Route::get('/admin/users/{role}',
        [AdminController::class,'usersRoleList'])->name('admin.users.list.role');

    Route::get('/admin/users/export/pdf/{data}',
        [AdminController::class, 'export_pdf'])->name('admin.users.export');

    /* Subject Link Routes */
    Route::get('/admin/subjects',
        [AdminController::class,'subjects_index'])->name('admin.subjects.index');

    /* Levels Link Routes */
    Route::get('/admin/levels',
        [AdminController::class,'levels_index'])->name('admin.levels.index');


    /* Materials Link Routes */
    Route::get('/admin/documents',
        [AdminController::class,'documents_index'])->name('admin.documents.index');

    /* Lectures Link Routes */
    Route::get('/admin/lectures/upload',
        [AdminController::class,'lectures_index'])->name('admin.lectures.index');
    Route::get('/admin/lectures',
        [AdminController::class,'lectures_table'])->name('admin.lectures');
    Route::get('/admin/subject/{id}/lectures',
        [AdminController::class,'lectures_table'])->name('admin.subject.lectures');

    Route::get('/admin/playlists',
        [AdminController::class,'playlists_table'])->name('admin.playlists');
    Route::get('/admin/playlistsfiles',
        [AdminController::class,'playlists_files'])->name('admin.playlistsfiles');

    Route::get('/admin/print',
        [AdminController::class, 'export_print'])->name('admin.print');


    Route::get('/admin/teams',
        [AdminController::class,'teams_manager'])->name('admin.teams');

    Route::get('/admin/teams2',
        [AdminController::class,'teams_manager2'])->name('admin.teams2');

    Route::get('/admin/teams/users',
        [AdminController::class,'teams_users_manager'])->name('admin.teams.users');

    Route::get('/admin/upload',[\App\Http\Controllers\MultipleFileUpload::class,'index'])->name('admin.upload');
    Route::post('/upload',[\App\Http\Controllers\MultipleFileUpload::class,'upload'])->name('upload');
});

