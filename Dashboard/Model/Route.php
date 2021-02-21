<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Model;

use Model\Index;

/**
 * HTTP route object
 */
class Route
{
    const CONTROLLER_PATH = 'Dashboard\Controller\\';
    /** 
     * @var array
     */
    public $params;
    /** 
     * @var string
     */
    public $controller;
    public $method;


    /**
     * @param array $params
     * @param string $controller
     * @param string $method
     * 
     * @return void
     */
    public function __construct(
        array $params
    ) {
        $this->params = $params;
        $this->execute();
    }

    /**
     * Create url method from url path params
     *      
     * @return void
     */
    private function execute()
    {
        //If only "/" or no url found go to home page
        if (empty($this->params) || count($this->params) == 1) {
            $this->controller = \Dashboard\Controller\Index::class;
            $this->method = 'execute';
            return;
        }

        //Create controller path
        $this->controller = self::CONTROLLER_PATH . ucfirst($this->params[0]);
        //hardcode method to execute since there are no user input expected
        $this->method = 'execute';
    }
}
