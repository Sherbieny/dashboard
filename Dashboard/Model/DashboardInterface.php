<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Model;

/**
 * Dashboard interface
 */
interface DashboardInterface
{
    /**
     * Get All customers
     *      
     * @return array
     */
    public function getCustomers();

    /**
     * Get Customer by ID
     *      
     * @param string $email
     * @return array
     */
    public function getCustomerByEmail($email);

    /**
     * Get All orders
     *      
     * @return array
     */
    public function getOrders();

    /**
     * Get All orders items
     *      
     * @return array
     */
    public function getOrdersItems();

    /**
     * Get Total number of customers
     *      
     * @return int
     */
    public function getCustomersCount();

    /**
     * Get Total number of orders per month
     *      
     * @return array
     */
    public function getOrderCountByMonth();

    /**
     * Get Total number of orders per day
     *      
     * @return array
     */
    public function getOrderCountByDay();

    /**
     * Get revenue per month
     *      
     * @return array
     */
    public function getRevenueByMonth();

    /**
     * Get revenue per month
     *      
     * @return array
     */
    public function getRevenueByDay();


    /**
     * Insert Data in DB
     * 
     * @param array $data
     * @return int
     */
    public function insert($data);
}
