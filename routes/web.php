<?php

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
    return view('welcome');
});

Route::group(['middleware' => ['web']], function() {
    Route::get('/manageusers', 'UsersController@manage');
    Route::get('/userslist', 'UsersController@usersList');
    Route::resource('/users','UsersController');
});

Route::group(['middleware' => ['web']], function() {
    Route::get('/managegroups', 'GroupsController@manage');
    Route::get('/groupslist', 'GroupsController@groupsList');
    Route::resource('/groups','GroupsController');
});