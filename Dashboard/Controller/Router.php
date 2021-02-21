<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Controller;

use Dashboard\Helper\Data;
use Dashboard\Model\Route;
use ReflectionClass;
use RuntimeException;

/**
 * Router class handling all HTTP requests
 */
class Router extends AbstractController
{
    /** 
     * @var Route
     */
    private $route;

    /**
     * @param Route $route
     * @return void
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function execute()
    {
    }

    /**
     * Handle HTTP requests
     *      
     * @return void
     */
    public function handleRequest()
    {
        try {
            if (!$this->route->method || !$this->route->controller) {
                throw new RuntimeException("This request did not match any route.");
            }

            //Init the controller class, method
            $controller = new ReflectionClass($this->route->controller);
            $method = $controller->getMethod($this->route->method);
            $instance = $controller->newInstance();
            $method->invoke($instance);
        } catch (\Exception $e) {
            $helper = new Data();
            $helper->logException($e);
            $controller = new ReflectionClass(AbstractController::NO_ROUTE);
            $method = $controller->getMethod(AbstractController::NO_ROUTE_METHOD);
            $instance = $controller->newInstance();
            $method->invoke($instance);
        }

        return;
    }
}
