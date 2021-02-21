<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

declare(strict_types=1);
require_once dirname(__DIR__) . '/html/vendor/autoload.php';
include_once("Dashboard/config.php");

use Dashboard\Controller\Router;
use Dashboard\Model\Route;



$params = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : ['/'];

dispatch($params);

/**
 * Process routing of incoming urls
 * @param array|string $params
 * @return void
 */
function dispatch($params)
{
    $route = new Route($params);

    $router = new Router($route);

    $router->handleRequest();
}
