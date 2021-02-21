<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Controller;

use Dashboard\Helper\Data;
use Dashboard\Model\Dashboard;

/**
 * 
 */
class Index extends \Dashboard\Controller\AbstractController
{

    /**     
     * @return void
     */
    public function execute()
    {

        $helper = new Data();
        //$helper->log(__METHOD__);

        $dashboard = new Dashboard();
        $customerData = $dashboard->getCustomersCount();
        $monthlyOrders = $dashboard->getOrderCountByMonth();
        $dailyOrders = $dashboard->getOrderCountByDay();
        $monthlyRev = $dashboard->getRevenueByMonth();
        $dailyRev = $dashboard->getRevenueByDay();

        $data = json_encode([
            'customers' => $customerData,
            'ordersm' => $monthlyOrders,
            'ordersd' => $dailyOrders,
            'revenuem' => $monthlyRev,
            'revenued' => $dailyRev
        ]);

        echo $helper->render('index', [
            'content' => $data
        ]);
    }

    /**
     * For testing purposes
     * Generating customers
     *      
     * @return void
     */
    private function generateCustomers()
    {
        // create customers
        /** @var Dashboard $customer */
        $customer = new Dashboard(Dashboard::TABLE_CUSTOMER);
        for ($i = 0; $i < 1; $i++) {
            if (empty($customer->getCustomerByEmail('karam@gnail.com'))) {
                $customer->insert(
                    [
                        'email' => 'karam@gnail.com',
                        'firstname' => 'Karam',
                        'lastname' => 'Gaber'
                    ]
                );
            }
            if (empty($customer->getCustomerByEmail('zack@mac.com'))) {
                $customer->insert(
                    [
                        'email' => 'zac@mac.com',
                        'firstname' => 'Zack',
                        'lastname' => 'Mack'
                    ]
                );
            }
            if (empty($customer->getCustomerByEmail('sondos@gnail.com'))) {
                $customer->insert(
                    [
                        'email' => 'sondos@gnail.com',
                        'firstname' => 'Sondos',
                        'lastname' => 'El Weskha'
                    ]
                );
            }
        }
    }
    /**
     * For testing purposes
     * generating orders
     * @return void 
     */
    function generateOrders()
    {
        $ids = [1, 3, 4, 5, 6];
        $countries = ['Egypt', 'Tanzania', 'China', 'Canada', 'Italy'];
        $devices = ['iPhone SE', 'iPhone X', 'iPhone 12', 'iPhone 8', 'iPhone 9'];
        $order = new Dashboard(Dashboard::TABLE_ORDER);
        for ($k = 0; $k < count($ids); $k++) {
            for ($i = 0; $i < 2; $i++) {
                $orderId = $order->insert([
                    'customer_id' => $ids[$k],
                    'purchase_date' => $this->randomDate('01.01.2021', '31.12.2021'),
                    'country' => $countries[$k],
                    'device' => $devices[$k]
                ]);

                for ($j = 0; $j < 5; $j++) {
                    $ean = rand(pow(10, 13 - 1), pow(10, 13) - 1);
                    $qty = rand(pow(10, 2 - 1), pow(10, 2) - 1);
                    $price = rand(pow(10, 5 - 1), pow(10, 5) - 1) / 23;
                    $item = new Dashboard(Dashboard::TABLE_ORDER_ITEM);
                    $item->insert([
                        'order_id' => $orderId,
                        'ean' => $ean,
                        'qty' => $qty,
                        'price' => $price
                    ]);
                }
            }
        }
    }

    // Find a randomDate between $start_date and $end_date
    function randomDate($start_date, $end_date)
    {
        // Convert to timetamps
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date('Y-m-d H:i:s', $val);
    }
}
