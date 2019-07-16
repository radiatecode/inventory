<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/16/2019
 * Time: 5:29 PM
 */
require '../vendor/autoload.php';
class SalesReturn
{
    private $_db;
    private $messages=[];

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function getMessage(){
        return $this->messages;
    }

    public function salesOrders(){
        $orders = $this->_db->select(['id','sales_order'])
            ->table('orders')
            ->orderBy('id','DESC')
            ->get();
        if (!$orders){
            return false;
        }
        return $this->_db->fetchAll($orders);
    }



}