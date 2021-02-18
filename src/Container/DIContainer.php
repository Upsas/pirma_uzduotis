<?php

declare(strict_types = 1);

namespace App\Container;

use App\Api;
use App\App;
use App\Container\ContainerInterface;
use App\Router;

class DIContainer implements ContainerInterface
{
    public function get(string $id)
    {
        return $this->classes($id);
    }
    public function has(string $id)
    {
        if (empty($this->classes($id))) {
            echo 'false';
            return false;
        } else {
            echo 'true';
            return true;
        }
    }

    public function classes(string $id)
    {
        $this->id = $id . 'Class';
        switch ($this->id) {
            case 'AppClass':
            return $this->getClass();
        }
    }

    public function RouterClass():object
    {
        return new Router();
    }
    public function ApiClass():object
    {
        return new Api($this->routerClass());
    }

    public function AppClass():object
    {
        return new App($this->routerClass());
    }

    public function getClass():object
    {
        if (method_exists($this, $this->id)) {
            $method = $this->id;
            return $this->$method();
        }
    }
}
