<?php
declare(strict_types = 1);

namespace App;

use App\Router;

class Api
{
    public function __construct(Router $router)
    {
        $router->routes();
    }
}
