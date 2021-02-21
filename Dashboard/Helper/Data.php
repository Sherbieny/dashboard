<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Helper;

/**
 * General helper class
 */
class Data
{
    const LOG_FOLDER_PATH = RD . '/var/log/';
    const LOG_FILE_PATH = RD . '/var/log/info.log';
    const ERROR_FILE_PATH = RD . '/var/log/error.log';
    const EXCEPTION_FILE_PATH = RD . '/var/log/exception.log';
    const CHART_ID_KEY = 'dashboard_chart';

    /**
     * @return void
     */
    public function __construct()
    {
        if (!file_exists(self::LOG_FOLDER_PATH)) {
            mkdir(self::LOG_FOLDER_PATH, 0777, true);
        }
    }


    /**
     * Log info
     * 
     * @param string|array $msg
     * @return void
     */
    public function log($msg)
    {
        file_put_contents(self::LOG_FILE_PATH, print_r($msg, true) . "\n", FILE_APPEND);
    }

    /**
     * Log Exceptions
     * 
     * @param \Exception $e
     * @return void
     */
    public function logException($e)
    {
        file_put_contents(self::EXCEPTION_FILE_PATH, $e->getMessage() . ' ' . $e->getTraceAsString() . "\n", FILE_APPEND);
    }

    /**
     * Log Error
     * 
     * @param \Error $e
     * @return void
     */
    public function logError($e)
    {
        file_put_contents(self::ERROR_FILE_PATH, $e->getMessage() . ' ' . $e->getTraceAsString() . "\n", FILE_APPEND);
    }

    /**
     * Renderer
     * 
     * @param string $view
     * @param array $params
     * @return string|false
     */
    public function render($view, $params)
    {
        $file = RD . '/Dashboard/View/' . strtolower($view) . '.php';
        if (!file_exists($file)) {
            return "View $view not found";
        };

        extract($params);
        ob_start();
        include $file;
        $renderedView = ob_get_clean();
        return $renderedView;
    }
}
