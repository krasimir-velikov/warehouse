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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {


    //Products
    Route::get('/products', 'ProductController@index')->name('products');

    //Finances
    Route::get('/finances', 'HomeController@finances')->name('finances');

    //Employees
    Route::get('/employees', 'EmployeeController@index')->name('employees');
    Route::get('/employees/create', 'EmployeeController@create')->name('employees.create');
    Route::get('/employees/edit', 'EmployeeController@edit')->name('employees.edit');
    Route::get('/employees/activate', 'EmployeeController@activate')->name('employees.activate');
    Route::get('/employees/block', 'EmployeeController@block')->name('employees.block');
    Route::get('/employees/delete', 'EmployeeController@delete')->name('employees.delete');
    Route::post('/employees/store', 'EmployeeController@store')->name('employees.store');
    Route::post('/employees/store_edit', 'EmployeeController@storeEdit')->name('employees.storeEdit');

    //Transfers
    Route::get('/transfers', 'HomeController@transfers')->name('transfers');

    //Clients
    Route::get('/clients', 'ClientController@index')->name('clients');

    //Suppliers
    Route::get('/suppliers', 'SupplierController@index')->name('suppliers');


});


