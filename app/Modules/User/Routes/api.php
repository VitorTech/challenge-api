<?php

$router->group(["prefix" => "api/user"], function () use ($router) {
    $router->group(
        ["middleware" => ["json.response", "auth"]],
        function () use ($router) {
            $router->get("/", "UserController@index");
            $router->post("/", "UserController@store");
            $router->get("/{id}", "UserController@edit");
            $router->put("/{id}", "UserController@update");
            $router->delete("/{id}", "UserController@delete");
        }
    );
});
