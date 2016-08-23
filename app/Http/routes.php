<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/','IndexController@index');

//图片转字符画
Route::get('/img2txt',['as'=>'image2txt','uses'=>'ImageToTextController@index']);
Route::post('/img2txtByUpload',['as'=>'image2txtByUpload','uses'=>'ImageToTextController@toANSIIByUpload']);
Route::post('/img2txtByURL',['as'=>'image2txtByURL','uses'=>'ImageToTextController@toANSIIByURL']);
