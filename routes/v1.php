<?php

$router->post("/register", "UserController@register");

$router->group(['middleware'=>'auth:api'], function () use ($router) {
    $router->get("/me", "UserController@me");
});