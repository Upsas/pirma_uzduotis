<?php

use Router;

class Api
{
    public function __construct()
    {
        $this->api();
    }
    
    public function api()
    {
        $router = new Router();
        $router->routes();
    }
}
