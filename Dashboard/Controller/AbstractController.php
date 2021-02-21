<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Controller;

/**
 * Abstract Controller
 */
abstract class AbstractController
{

    const NO_ROUTE = NoRoute::class;
    const NO_ROUTE_METHOD = 'execute';

    abstract public function execute();
}
