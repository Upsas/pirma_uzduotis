<?php

declare(strict_types=1);

use App\Container\DIContainer;

require_once "./vendor/autoload.php";

$DIconatienr = new DIContainer();
$DIconatienr->get('App');
