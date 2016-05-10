<?php

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/customers', ['middleware' => 'specter:10', function(){
    return response()->json(getFixture('customer'));
}]);

$app->get('/customers/{id}', ['middleware' => 'specter', function(){
    return response()->json(getFixture('customer'));
}]);
