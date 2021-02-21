<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Model;

use Dashboard\Helper\Data;
use Exception;
use PDO;

/**
 * Dashboad Database Class
 */
class Dashboard implements DashboardInterface
{
    const DB_HOST = 'mysql';
    const DB_NAME = 'dashboard';
    const DB_USER = 'root';
    const DB_PASS = 'essorege3';
    //table names
    const TABLE_ORDER = 'Purchase_Order';
    const TABLE_CUSTOMER = 'Customer';
    const TABLE_ORDER_ITEM = 'Order_Item';

    const SELECT_GENERAL = 'SELECT * FROM ';

    /** 
     * @var PDO
     */
    protected static $connection;

    /** 
     * @var string
     */
    protected $tableName;

    /** 
     * @var Data
     */
    protected $helper;

    /**  
     * @param string|null $tableName //optional for insert use only            
     * @return void
     */
    public function __construct($tableName = null)
    {
        try {
            if (self::$connection === null) {
                self::$connection = new PDO('mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASS);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            $this->tableName = $tableName;
            if (!$this->helper) {
                $this->helper = new Data();
            }
        } catch (\PDOException $e) {
            $helper = new Data();
            $helper->logException($e);
            echo "Mysql Connection failed \n" . $e->getMessage();
            exit;
        }
    }

    /**
     * @inheritdoc
     */
    protected function getConnection()
    {
        return self::$connection;
    }

    /**
     * @inheritdoc
     */
    public function getCustomers()
    {
        return $this->getConnection()->query(
            self::SELECT_GENERAL . self::TABLE_CUSTOMER
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritdoc
     */
    public function getCustomerByEmail($email)
    {
        $stm = $this->getConnection()->prepare(
            self::SELECT_GENERAL . self::TABLE_CUSTOMER . ' WHERE `email` LIKE :email'
        );
        $stm->bindParam(':email', $email);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        return empty($data) ? [] : $data[0];
    }

    /**
     * @inheritdoc
     */
    public function getOrders()
    {
        return $this->getConnection()->query(
            self::SELECT_GENERAL . self::TABLE_ORDER
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritdoc
     */
    public function getOrdersItems()
    {
        return $this->getConnection()->query(
            self::SELECT_GENERAL . self::TABLE_ORDER_ITEM
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    ///////////// Chart Specific queries ///////////////

    /**
     * @inheritdoc
     */
    public function getCustomersCount()
    {
        return count($this->getCustomers());
    }

    /**
     * @inheritdoc
     */
    public function getOrderCountByMonth()
    {
        return $this->getConnection()->query(
            'SELECT DATE_FORMAT(purchase_date,"%Y-%m-01") AS "month", count(*) AS "order_count" FROM Purchase_Order GROUP BY DATE_FORMAT (purchase_date, "%Y-%m-01") ORDER BY `month` ASC'
        )->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * @inheritdoc
     */
    public function getOrderCountByDay()
    {
        return $this->getConnection()->query(
            'SELECT DATE_FORMAT(purchase_date,"%Y-%m-%d") AS "day", count(*) AS "order_count" FROM Purchase_Order GROUP BY DATE_FORMAT (purchase_date, "%Y-%m-%d") ORDER BY `day` ASC'
        )->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * @inheritdoc
     */
    public function getRevenueByMonth()
    {
        return $this->getConnection()->query(
            'SELECT DATE_FORMAT(purchase_date,"%Y-%m-01") AS "month", SUM(qty * price) AS "revenue" FROM Purchase_Order JOIN Order_Item ON Purchase_Order.id=Order_Item.order_id GROUP BY DATE_FORMAT (purchase_date, "%Y-%m-01") ORDER BY `month` ASC'
        )->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * @inheritdoc
     */
    public function getRevenueByDay()
    {
        return $this->getConnection()->query(
            'SELECT DATE_FORMAT(purchase_date,"%Y-%m-%d") AS "day", SUM(qty * price) AS "revenue" FROM Purchase_Order JOIN Order_Item ON Purchase_Order.id=Order_Item.order_id GROUP BY DATE_FORMAT (purchase_date,"%Y-%m-%d") ORDER BY `day` ASC'
        )->fetchAll(PDO::FETCH_KEY_PAIR);
    }


    //////////// FOR TESTING PURPOSES ////////////////////
    /**
     * @inheritdoc
     */
    public function insert($data)
    {
        $this->helper->log(__METHOD__);

        if ($this->tableName === null) {
            throw new Exception("Table name not defined");
        }

        if (empty($data)) {
            throw new Exception("Invalid Data provided");
        }

        // Question marks
        $marks = array_fill(0, count($data), '?');
        // Fields to be added.
        $fields = array_keys($data);
        // Fields values
        $values = array_values($data);

        // Prepare statement        
        $stmt = $this->getConnection()->prepare('
            INSERT INTO ' . $this->tableName . '(' . implode(',', $fields) . ')
            VALUES(' . implode(',', $marks) . ')
        ');
        // Execute statement with values
        $stmt->execute($values);

        // Return last inserted ID.
        return $this->getConnection()->lastInsertId();
    }
}
