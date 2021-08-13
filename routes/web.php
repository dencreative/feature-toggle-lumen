<?php

use Illuminate\Support\Facades\Route;
use CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController;
use CharlGottschalk\FeatureToggleLumen\Http\Middleware\SanitizeInput;

$this->app->router->get('/', [
    'as' => 'features.toggle.index',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@index'
]);
$this->app->router->get('/{id}', [
    'as' => 'features.toggle.show',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@show'
]);
$this->app->router->get('/{id}/disable', [
    'as' => 'features.toggle.disable',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@disable'
]);
$this->app->router->get('/{id}/enable', [
    'as' => 'features.toggle.enable',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@enable'
]);
$this->app->router->get('/{id}/delete', [
    'as' => 'features.toggle.delete',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@delete'
]);
$this->app->router->post('/', [
    'as' => 'features.toggle.store',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@store'
]);
$this->app->router->post('/{id}/update', [
    'as' => 'features.toggle.update',
    'uses' => 'CharlGottschalk\FeatureToggleLumen\Http\Controllers\FeaturesController@update'
]);
