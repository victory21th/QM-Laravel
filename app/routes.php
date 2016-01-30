<?php

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[a-zA-Z0-9-]+');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', function(){
    return Redirect::route('user.home');
});

Route::get('home/{id?}',         ['as' => 'user.home',               'uses' => 'User\StoreController@home']);
Route::get('status',             ['as' => 'user.queue.status',       'uses' => 'User\QueueController@status']);
Route::get('store/search',       ['as' => 'user.store.search',       'uses' => 'User\StoreController@search']);
Route::get('store/detail/{id}',  ['as' => 'user.store.detail',       'uses' => 'User\StoreController@detail']);
Route::post('queue/async/apply', ['as' => 'user.async.queue.apply',  'uses' => 'User\QueueController@apply']);


Route::get('login',         ['as' => 'user.auth.login',         'uses' => 'User\AuthController@login']);
Route::post('doLogin',      ['as' => 'user.auth.doLogin',       'uses' => 'User\AuthController@doLogin']);
Route::get('signup',        ['as' => 'user.auth.signup',        'uses' => 'User\AuthController@signup']);
Route::post('doSignup',     ['as' => 'user.auth.doSignup',      'uses' => 'User\AuthController@doSignup']);
Route::get('doLogout',      ['as' => 'user.auth.doLogout',      'uses' => 'User\AuthController@doLogout']);

Route::group(['prefix' => 'admin'], function () {
    
    Route::get('/',         ['as' => 'admin.auth',         'uses' => 'Admin\AuthController@index']);
    Route::get('login',     ['as' => 'admin.auth.login',   'uses' => 'Admin\AuthController@login']);
    Route::post('doLogin',  ['as' => 'admin.auth.doLogin', 'uses' => 'Admin\AuthController@doLogin']);
    Route::get('logout',    ['as' => 'admin.auth.logout',  'uses' => 'Admin\AuthController@logout']);
    
    Route::get('dashboard', ['as' => 'admin.dashboard',    'uses' => 'Admin\DashboardController@index']);
        
    Route::group(['prefix' => 'company'], function () {
        Route::get('/',           ['as' => 'admin.company',         'uses' => 'Admin\CompanyController@index']);
        Route::get('create',      ['as' => 'admin.company.create',  'uses' => 'Admin\CompanyController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.company.edit',    'uses' => 'Admin\CompanyController@edit']);
        Route::post('store',      ['as' => 'admin.company.store',   'uses' => 'Admin\CompanyController@store']);
        Route::get('delete/{id}', ['as' => 'admin.company.delete',  'uses' => 'Admin\CompanyController@delete']);
    });
    
    Route::group(['prefix' => 'category'], function () {        
        Route::get('/',           ['as' => 'admin.category',         'uses' => 'Admin\CategoryController@index']);
        Route::get('create',      ['as' => 'admin.category.create',  'uses' => 'Admin\CategoryController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.category.edit',    'uses' => 'Admin\CategoryController@edit']);
        Route::post('store',      ['as' => 'admin.category.store',   'uses' => 'Admin\CategoryController@store']);
        Route::get('delete/{id}', ['as' => 'admin.category.delete',  'uses' => 'Admin\CategoryController@delete']);        
    });

    Route::group(['prefix' => 'city'], function () {
        Route::get('/',           ['as' => 'admin.city',         'uses' => 'Admin\CityController@index']);
        Route::get('create',      ['as' => 'admin.city.create',  'uses' => 'Admin\CityController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.city.edit',    'uses' => 'Admin\CityController@edit']);
        Route::post('store',      ['as' => 'admin.city.store',   'uses' => 'Admin\CityController@store']);
        Route::get('delete/{id}', ['as' => 'admin.city.delete',  'uses' => 'Admin\CityController@delete']);        
    });
    
    Route::group(['prefix' => 'user'], function () {
        Route::get('/',           ['as' => 'admin.user',         'uses' => 'Admin\UserController@index']);
        Route::get('create',      ['as' => 'admin.user.create',  'uses' => 'Admin\UserController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.user.edit',    'uses' => 'Admin\UserController@edit']);
        Route::post('store',      ['as' => 'admin.user.store',   'uses' => 'Admin\UserController@store']);
        Route::get('delete/{id}', ['as' => 'admin.user.delete',  'uses' => 'Admin\UserController@delete']);
    });    
});

Route::group(['prefix' => 'company'], function () {
    Route::get('/',         ['as' => 'company.auth',         'uses' => 'Company\AuthController@index']);
    Route::get('login',     ['as' => 'company.auth.login',   'uses' => 'Company\AuthController@login']);
    Route::post('doLogin',  ['as' => 'company.auth.doLogin', 'uses' => 'Company\AuthController@doLogin']);
    Route::get('logout',    ['as' => 'company.auth.logout',  'uses' => 'Company\AuthController@logout']);
    
    Route::any('dashboard',         ['as' => 'company.dashboard',          'uses' => 'Company\DashboardController@index']);
    Route::any('dashboard/agent',   ['as' => 'company.dashboard.agent',    'uses' => 'Company\DashboardController@agent']);
    
    Route::group(['prefix' => 'store'], function () {
        Route::get('/',           ['as' => 'company.store',         'uses' => 'Company\StoreController@index']);
        Route::get('create',      ['as' => 'company.store.create',  'uses' => 'Company\StoreController@create']);
        Route::get('edit/{id}',   ['as' => 'company.store.edit',    'uses' => 'Company\StoreController@edit']);
        Route::post('store',      ['as' => 'company.store.store',   'uses' => 'Company\StoreController@store']);
        Route::get('delete/{id}', ['as' => 'company.store.delete',  'uses' => 'Company\StoreController@delete']);
    });
    
    Route::group(['prefix' => 'agent'], function () {
        Route::get('/',           ['as' => 'company.agent',         'uses' => 'Company\AgentController@index']);
        Route::get('create',      ['as' => 'company.agent.create',  'uses' => 'Company\AgentController@create']);
        Route::get('edit/{id}',   ['as' => 'company.agent.edit',    'uses' => 'Company\AgentController@edit']);
        Route::post('store',      ['as' => 'company.agent.store',   'uses' => 'Company\AgentController@store']);
        Route::get('delete/{id}', ['as' => 'company.agent.delete',  'uses' => 'Company\AgentController@delete']);
    });

    Route::group(['prefix' => 'video'], function () {
        Route::get('/',           ['as' => 'company.video',         'uses' => 'Company\VideoController@index']);
        Route::get('create',      ['as' => 'company.video.create',  'uses' => 'Company\VideoController@create']);
        Route::get('edit/{id}',   ['as' => 'company.video.edit',    'uses' => 'Company\VideoController@edit']);
        Route::post('store',      ['as' => 'company.video.store',   'uses' => 'Company\VideoController@store']);
        Route::get('delete/{id}', ['as' => 'company.video.delete',  'uses' => 'Company\VideoController@delete']);
    });
    
    Route::group(['prefix' => 'slider'], function () {
        Route::get('/',           ['as' => 'company.slider',         'uses' => 'Company\SliderController@index']);
        Route::get('create',      ['as' => 'company.slider.create',  'uses' => 'Company\SliderController@create']);
        Route::post('store',      ['as' => 'company.slider.store',   'uses' => 'Company\SliderController@store']);
        Route::get('delete/{id}', ['as' => 'company.slider.delete',  'uses' => 'Company\SliderController@delete']);
    });    

    Route::group(['prefix' => 'ticket-type'], function () {
        Route::get('/',           ['as' => 'company.ticket-type',         'uses' => 'Company\TicketTypeController@index']);
        Route::get('create',      ['as' => 'company.ticket-type.create',  'uses' => 'Company\TicketTypeController@create']);
        Route::get('edit/{id}',   ['as' => 'company.ticket-type.edit',    'uses' => 'Company\TicketTypeController@edit']);
        Route::post('store',      ['as' => 'company.ticket-type.store',   'uses' => 'Company\TicketTypeController@store']);
        Route::get('delete/{id}', ['as' => 'company.ticket-type.delete',  'uses' => 'Company\TicketTypeController@delete']);
    });
    
    Route::group(['prefix' => 'setting'], function () {
        Route::get('/',           ['as' => 'company.setting',         'uses' => 'Company\SettingController@index']);
        Route::post('store',      ['as' => 'company.setting.store',   'uses' => 'Company\SettingController@store']);
    });

    Route::group(['prefix' => 'account'], function () {
        Route::get('/',           ['as' => 'company.account',         'uses' => 'Company\AccountController@index']);
        Route::post('store',      ['as' => 'company.account.store',   'uses' => 'Company\AccountController@store']);
    });
});

Route::group(['prefix' => 'batch'], function () {
    Route::get('store/status/reset',     ['as' => 'batch.store.status.reset',   'uses' => 'Batch\StatusController@reset']);
    Route::get('agent/process/end',      ['as' => 'batch.agent.process.end',    'uses' => 'Batch\ProcessController@end']);
});

Route::group(['prefix' => 'store'], function () {
    Route::get('tv/{slug}',        ['as' => 'store.tv',             'uses' => 'Store\StatusController@tv']);

    Route::group(['prefix' => 'async'], function () {
        Route::post('status',      ['as' => 'store.async.status',   'uses' => 'Store\StatusController@asyncStatus']);
    });
});

Route::group(['prefix' => 'agent'], function () {
    Route::get('/',         ['as' => 'agent.auth',         'uses' => 'Agent\AuthController@index']);
    Route::get('login',     ['as' => 'agent.auth.login',   'uses' => 'Agent\AuthController@login']);
    Route::post('doLogin',  ['as' => 'agent.auth.doLogin', 'uses' => 'Agent\AuthController@doLogin']);
    Route::get('logout',    ['as' => 'agent.auth.logout',  'uses' => 'Agent\AuthController@logout']);

    Route::get('process',   ['as' => 'agent.process',      'uses' => 'Agent\ProcessController@index']);    

    Route::group(['prefix' => 'account'], function () {
        Route::get('/',           ['as' => 'agent.account',         'uses' => 'Agent\AccountController@index']);
        Route::post('store',      ['as' => 'agent.account.store',   'uses' => 'Agent\AccountController@store']);
    });

    Route::group(['prefix' => 'async'], function () {
        Route::post('next',     ['as' => 'agent.async.next',        'uses' => 'Agent\ProcessController@asyncNext']);
    });
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::any('auth/login',         ['as' => 'api.v1.auth.login',     'uses' => 'Api\AuthController@login']);
    Route::any('auth/signup',        ['as' => 'api.v1.auth.signup',    'uses' => 'Api\AuthController@signup']);
    Route::any('auth/profile',       ['as' => 'api.v1.auth.profile',   'uses' => 'Api\AuthController@profile']);
    Route::any('auth/update',        ['as' => 'api.v1.auth.update',    'uses' => 'Api\AuthController@update']);
    
    Route::any('cities',             ['as' => 'api.v1.cities',         'uses' => 'Api\CityController@index']);
    Route::any('categories',         ['as' => 'api.v1.categories',     'uses' => 'Api\CategoryController@index']);
    
    Route::any('store/search',       ['as' => 'api.v1.store.search',   'uses' => 'Api\StoreController@search']);
    Route::any('store/detail',       ['as' => 'api.v1.store.detail',   'uses' => 'Api\StoreController@detail']);
    
    Route::any('queue/apply',        ['as' => 'api.v1.queue.apply',    'uses' => 'Api\QueueController@apply']);
    Route::any('queue/status',       ['as' => 'api.v1.queue.status',   'uses' => 'Api\QueueController@status']);
    
    Route::any('tablet/login',       ['as' => 'api.v1.tablet.login',   'uses' => 'Api\TabletController@login']);
    Route::any('tablet/apply',       ['as' => 'api.v1.tablet.apply',   'uses' => 'Api\TabletController@apply']);
    
});

App::missing(function($exception) {
   return Redirect::route('user.home');
});