<?php
declare(strict_types = 1);

use Router;

class Api
{
    public function __construct()
    {
        $this->api();
    }
        
    /**
     *
     * @return void
     */

    public function api(): void
    {
        $router = new Router();
        $router->routes();
    }
}
