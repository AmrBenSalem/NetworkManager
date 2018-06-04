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

Auth::routes();
Route::post('/dashboard/user/modify','UserController@modify');
Route::get('/createnewuser', function() {
    return view('auth/customregister');
});

Route::get('/dashboard', 'HomeController@index');

Route::get('/dashboard/template','TemplateController@index');
Route::post('/dashboard/template', 'TemplateController@create');

Route::get('/dashboard/configure', 'ConfigureController@index');
Route::post('/dashboard/configure', 'ConfigureController@create');
Route::post('/dashboard/doscannow','ConfigureController@scan');

Route::post('/dashboard/configinputs','ConfigInputController@create');

Route::post('/dashboard/gettemplate','TemplateController@get');
Route::post('/dashboard/managetemplate/delete','TemplateController@delete');

Route::post('/dashboard/network/add','NetworkController@add');
Route::post('/dashboard/network/delete','NetworkController@delete');
Route::post('/dashboard/network/modify', 'NetworkController@modify');

Route::get('/dashboard/devices','DeviceController@index');
Route::post('/dashboard/devices/getinterfaces' , 'DeviceController@getinterfaces');

Route::get('/dashboard/backup','BackupController@index');
Route::post('/dashboard/backup/back','BackupController@back');
Route::post('/dashboard/backup/restore','BackupController@restore');
Route::post('/dashboard/backup/manage','BackupController@manage');

Route::post('/dashboard/ping/check','PingController@check');
Route::post('/dashboard/scan/check','ScanController@check');

Route::post('/dashboard/setting/addprofile','SettingController@addprofile');
Route::post('/dashboard/setting/manageprofile','SettingController@manageprofile');
Route::post('/dashboard/setting/getprofile','SettingController@getprofile');
Route::post('/dashboard/setting/tftp', 'SettingController@tftp');
Route::post('/dashboard/network/changeprofile','NetworkController@changeprofile');

Route::post('/dashboard/notifications/check','NotificationsCOntroller@check');
Route::post('/dashboard/notifications/seen','NotificationsCOntroller@seen');

Route::get('/dashboard/logs','LogsController@index');

Route::post('/dashboard/getuser','UserController@get');


Route::post('/dashboard/register','RegisterUserController@create');
Route::post('/dashboard/user/delete', 'RegisterUserController@delete');

Route::post('/dashboard/device/getconfig','DeviceController@getconfig');



Route::get('/dashboard/pingtest','NotificationsCOntroller@pings');

// to avoid letting url , make a get method to reroute 


