<?php

$router->group(["prefix" => "api/transaction"], function () use ($router) {
    $router->group(
        ["middleware" => ["json.response", "auth"]],
        function () use ($router) {
            $router->post("/", "TransactionController@store");
        }
    );
});
