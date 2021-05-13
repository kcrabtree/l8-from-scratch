<?php

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
*/

Route::get('/', function () {
    return view('posts');
});

Route::get('posts/{post}', function ($uriSlug) {
    $path = __DIR__ . "/../resources/posts/{$uriSlug}.html";

    if(!file_exists($path)) {
        return redirect('/');
    }

    return view('post', [
        'post' => file_get_contents($path)
    ]);
});
