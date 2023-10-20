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
    return view('auth.login');
});


Route::get('/welcome', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'BoardController@index')->name('boards.index');
    Route::get('boards', 'BoardController@index')->name('boards.index');
    Route::post('boards', 'BoardController@store')->name('boards.store');
    Route::get('boards/{id}', 'BoardController@show')->name('boards.show');
    Route::put('boards/{id}', 'BoardController@update')->name('boards.update');
    Route::delete('boards/{id}', 'BoardController@destroy')->name('boards.destroy');

    Route::post('board_users', 'BoardUserController@store')->name('board_users.store');
    Route::post('board_users/{id}', 'BoardUserController@update')->name('board_users.update');

    Route::get('tasks', 'TaskController@index')->name('tasks.index');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::get('tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
    Route::put('updatetask/{id}', 'TaskController@updatetask')->name('tasks.updatetask');
    Route::put('tasks/sync', 'TaskController@sync')->name('tasks.sync');
    Route::put('tasks/{task}', 'TaskController@update')->name('tasks.update');
    Route::delete('tasks/{id}', 'TaskController@destroy')->name('tasks.destroy');

    // Route::get('statuses', 'StatusController@index')->name('statuses.index');
    Route::post('statuses', 'StatusController@store')->name('statuses.store');
    // Route::get('statuses/{id}', 'StatusController@show')->name('statuses.show');
    Route::put('statuses/{id}', 'StatusController@update')->name('statuses.update');
    Route::delete('statuses/{id}', 'StatusController@destroy')->name('statuses.destroy');

    // Route::get('tags', 'TagController@index')->name('tags.index');
    Route::post('tags', 'TagController@store')->name('tags.store');
    // Route::get('tags/{id}', 'TagController@show')->name('tags.show');
    Route::put('tags/{id}', 'TagController@update')->name('tags.update');
    Route::delete('tags/{id}', 'TagController@destroy')->name('tags.destroy');

    Route::post('tag_tasks', 'TagTaskController@store')->name('tag_tasks.store');
    Route::post('tag_tasks/{id}', 'TagTaskController@destroy')->name('tag_tasks.destroy');

    Route::post('task_board_users', 'TaskBoardUserController@store')->name('task_board_users.store');
    Route::post('task_board_users/{id}', 'TaskBoardUserController@destroy')->name('task_board_users.destroy');

    Route::get('/mark-as-read', 'TaskBoardUserController@markAsRead')->name('mark-as-read');
});

// Route::group(['middleware' => 'auth'], function () {
//     Route::post('statuses', 'StatusController@store')->name('statuses.store');
//     Route::put('statuses', 'StatusController@update')->name('statuses.update');
// });

// Route::resource('tag', App\Http\Controllers\TagController::class);
