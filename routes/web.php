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
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::get('/products/edit', 'ProductController@edit')->name('products.edit');
    Route::get('/products/show', 'ProductController@show')->name('products.show');
    Route::get('/products/subcats', 'ProductController@subcats')->name('products.subcats');
    Route::get('/products/delete', 'ProductController@delete')->name('products.delete');
    Route::post('/products/store', 'ProductController@store')->name('products.store');
    Route::post('/products/update', 'ProductController@update')->name('products.update');

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
    Route::post('/employees/update', 'EmployeeController@update')->name('employees.update');

    //Transfers
    Route::get('/transfers', 'HomeController@transfers')->name('transfers');

    //Suppliers
    Route::get('/suppliers', 'SupplierController@index')->name('suppliers');
    Route::get('/suppliers/create', 'SupplierController@create')->name('suppliers.create');
    Route::get('/suppliers/edit', 'SupplierController@edit')->name('suppliers.edit');
    Route::get('/suppliers/delete', 'SupplierController@delete')->name('suppliers.delete');
    Route::post('/suppliers/store', 'SupplierController@store')->name('suppliers.store');
    Route::post('/suppliers/update', 'SupplierController@update')->name('suppliers.update');



    //Clients
    Route::get('/clients', 'ClientController@index')->name('clients');
    Route::get('/clients/create', 'ClientController@create')->name('clients.create');
    Route::get('/clients/edit', 'ClientController@edit')->name('clients.edit');
    Route::get('/clients/delete', 'ClientController@delete')->name('clients.delete');
    Route::post('/clients/store', 'ClientController@store')->name('clients.store');
    Route::post('/clients/update', 'ClientController@update')->name('clients.update');

    //Categories
    Route::get('/categories', 'CategoryController@index')->name('categories');
    Route::get('/categories/create', 'CategoryController@create')->name('categories.create');
    Route::post('/categories/store', 'CategoryController@store')->name('categories.store');
    Route::get('/categories/edit', 'CategoryController@edit')->name('categories.edit');
    Route::get('/categories/delete', 'CategoryController@delete')->name('categories.delete');
    Route::post('/categories/update', 'CategoryController@update')->name('categories.update');


    //Subcategories
    Route::get('/subcategories/create', 'SubcategoryController@create')->name('subcategories.create');
    Route::post('/subcategories/store', 'SubcategoryController@store')->name('subcategories.store');
    Route::get('/subcategories/edit', 'SubcategoryController@edit')->name('subcategories.edit');
    Route::get('/subcategories/delete', 'SubcategoryController@delete')->name('subcategories.delete');
    Route::post('/subcategories/update', 'SubcategoryController@update')->name('subcategories.update');


});


