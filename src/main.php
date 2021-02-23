<?php

declare(strict_types=1);

use App\Container\DIContainer;

require __DIR__ . "/../vendor/autoload.php";

$DIconatienr = new DIContainer();
$DIconatienr->get('app');
