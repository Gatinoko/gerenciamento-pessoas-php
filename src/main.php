<?php

use App\Http\Route;

Route::get('/', 'IndexController@index');
Route::get('/api/pessoas', 'ApiController@getAll');
Route::post('/api/pessoas', 'ApiController@create');
Route::put('/api/pessoas/{id}', 'ApiController@update');
Route::delete('/api/pessoas/{id}', 'ApiController@delete');
