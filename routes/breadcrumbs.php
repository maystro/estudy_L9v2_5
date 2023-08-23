<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->push('الرئيسية', route('profile'));
});

// Account
Breadcrumbs::for('profile.account', function (BreadcrumbTrail $trail) {
    $trail->parent('profile');
    $trail->push('الملف الشخصي', route('profile.account'));
});
Breadcrumbs::for('profile.account.security', function (BreadcrumbTrail $trail) {
    $trail->parent('profile');
    $trail->push('الأمان', route('profile.account.security'));
});

// ! Account

// Home > Blog
Breadcrumbs::for('profile.lecturesByDay', function (BreadcrumbTrail $trail) {
    $trail->parent('profile');
    $trail->push('جدول المواد', route('profile.lecturesByDay'));
});

Breadcrumbs::for('profile.subjects', function (BreadcrumbTrail $trail) {
    $trail->parent('profile');
    $trail->push('قائمة المواد', route('profile.subjects'));
});

Breadcrumbs::for('profile.lectures', function (BreadcrumbTrail $trail,$subject_name,$subject_id) {
    $trail->parent('profile.subjects');
    $trail->push('محاضرات مادة '.$subject_name, route('profile.subjects'));
});

Breadcrumbs::for('profile.book.list', function (BreadcrumbTrail $trail,$playlist_title,$playlist_id) {
    $trail->parent('profile');
    $trail->push($playlist_title, route('profile.book.list', $playlist_id));
});

Breadcrumbs::for('profile.file.view', function (BreadcrumbTrail $trail,$subject,$document) {
    $trail->parent('profile.lecturesByDay');
    $trail->push($subject->title, route('profile.subject.lectures',['subject_id'=>$subject->id]));
    //$trail->push('محاضرة رقم '.$document->lecture_number, route('profile.file.view',['file_id'=>$document->id]));
    $trail->push($document->lecture_number, route('profile.file.view',['file_id'=>$document->id]));
});

Breadcrumbs::for('profile.playlist', function (BreadcrumbTrail $trail,$playlist_title,$playlist_id) {
    $trail->parent('profile');
    $trail->push($playlist_title, route('profile.media.list',$playlist_id));
});

Breadcrumbs::for('profile.media.open', function (BreadcrumbTrail $trail,$media_file) {
    $trail->parent('profile.playlist',$media_file->playlist_title,$media_file->playlist_id);
    $trail->push($media_file->file_title, route('profile.media.open',$media_file->file_id));
});


// Home > Blog > [Category]
//Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
//    $trail->parent('blog');
//    $trail->push($category->title, route('category', $category));
//});
