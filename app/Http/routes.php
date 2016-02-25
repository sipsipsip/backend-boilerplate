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


/* -------------------------------------------
    AUTHENTICATION ROUTE
    ------------------------------------------ */
Route::get('login', 'Auth\LDAPController@getLogin');
Route::post('login', 'Auth\LDAPController@postLogin');
Route::get('/logout', 'Auth\LDAPController@getLogout');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


/* -------------------------------------------
    PUBLIC ROUTE
    ------------------------------------------ */
Route::get('/', 'WelcomeController@index');
Route::get('home', 'HomeController@index');



/* -------------------------------------------
    ADMIN SPECIFIC FITURES
    ------------------------------------------ */




/* ---------------------------------
    APPLICATION API ENDPOINTS :: API
    -------------------------------- */
Route::group(['prefix'=>'api/v1'], function(){
    /** DATA API ENDPOINT **/
    /** usage for querying any models, or keyword, paginate, etc **/
    Route::get('data', 'ApiController@getData');

    /** API CRUD **/
    Route::controllers(['general'=>'CRUDController']);

    /** API USERS **/
    Route::get('users/current', 'ApiController@apiCurrentUser');

});



/* -----------------------------------
    SINGLE LOGIN ROUTING
    ---------------------------------- */
Route::get('check-auth', 'Auth/LDAPController@checkAuth');


