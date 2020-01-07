<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
   function () {

        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

           // Route::get('/', 'WelcomeController@index')->name('welcome');

            //category routes
          //  Route::resource('categories', 'CategoryController')->except(['show']);

            //product routes
          //  Route::resource('products', 'ProductController')->except(['show']);

            //client routes
           // Route::resource('clients', 'ClientController')->except(['show']);
           // Route::resource('clients.orders', 'Client\OrderController')->except(['show']);

            //order routes
            //     Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');


            //user routes
            Route::resource('users', 'UserController')->except(['show']);
            Route::resource('categories', 'CategoryController')->except(['show']);
            Route::resource('products', 'ProductController')->except(['show']);
            Route::resource('clients', 'ClientController')->except(['show']);
           // Route::resource('clients/orders', 'client\OrderController')->except(['show']);
           Route::resource('clients.orders', 'Client\OrderController')->except(['show']);
           Route::resource('orders', 'OrderConroller');
           Route::get('orders/products/{order}', 'OrderConroller@products')->name('orders.products');

          //  Route::resource('orders', 'client\OrderController');

            Route::get('welcome',"WelcomeController@index")->name('welcome');
            Route::get('/', 'WelcomeController@index')->name('welcome');


        });//end of dashboard routes
    });


