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

Auth::routes();

Route::get('/', 'HomeController@index')->name('job');
Route::get('/addJob', 'HomeController@add')->name('add-job');
Route::get('/delete/{job_id}', 'HomeController@delete')->name('delete-job');
Route::get('/result/{job_id}', 'HomeController@show')->name('result-job');
Route::post('/save', 'HomeController@save')->name('save-job');

Route::get('/api/worker/registration', 'ApiController@workerRegistration')->name('api.worker.registration');
Route::post('/api/worker/online', 'ApiController@workerOnline')->name('api.worker.online');
Route::post('/api/worker/offline', 'ApiController@workerOffline')->name('api.worker.offline');
Route::get('/api/getTask', 'ApiController@getTask')->name('api.gettask');
