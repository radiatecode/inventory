<?php
/**
 * Created by PhpStorm.
 * User: optimus prime
 * Date: 7/19/2019
 * Time: 10:56 PM
 */
require '../vendor/autoload.php';
class Dashboard
{
    private $_db;
    private $messages=[];
    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function data(){
        $purchase = $this->_db->select(['sum(purchase_items.quantity) AS purchase_quantity'])
            ->table('purchase_items')
            ->groupBy(['purchase_items.product_id'])
            ->orderBy('id','DESC')
            ->get();
        $sales = $this->_db->select(['sum(order_items.quantity) AS order_quantity'])
            ->table('order_items')
            ->groupBy(['order_items.product_id'])
            ->orderBy('id','DESC')
            ->get();
        $purchase_return = $this->_db->select(['sum(purchase_return_items.quantity) AS purchase_return_quantity'])
            ->table('purchase_return_items')
            ->groupBy(['purchase_return_items.product_id'])
            ->orderBy('id','DESC')
            ->get();
        $sales_return = $this->_db->select(['sum(order_return_items.quantity) AS order_return_quantity'])
            ->table('order_return_items')
            ->groupBy(['order_return_items.product_id'])
            ->orderBy('id','DESC')
            ->get();
        $purchase_qty = $this->_db->fetchAssoc($purchase);
        $sales_qty = $this->_db->fetchAssoc($sales);
        $purchase_return_qty = $this->_db->fetchAssoc($purchase_return);
        $sales_return_qty = $this->_db->fetchAssoc($sales_return);
        return  [
                'purchase_qty'=>$purchase_qty['purchase_quantity'],
                'sales_qty'=>$sales_qty['order_quantity'],
                'purchase_return_qty'=>$purchase_return_qty['purchase_return_quantity'],
                'sales_return_qty'=>$sales_return_qty['order_return_quantity'],
            ];
    }

    public function purchase_chart(){
        $months = ['January','February','March','April','May','June','July',
            'August','September','October','November','December'];
        $monthly_chart = [];
        foreach ($months as $month){
            $start = date('Y-m-01',strtotime($month." ".date('Y')));
            $end = date('Y-m-t',strtotime($month." ".date('Y')));

            $purchase = $this->_db->select(['sum(purchase_items.quantity) AS purchase_quantity'])
                ->table('purchase_items')
                ->join('purchase','purchase.id','purchase_items.purchase_id')
                ->where('purchase.order_date','>=',$start)
                ->where('purchase.order_date','<=',$end)
                ->groupBy(['purchase_items.product_id'])
                ->get();
            $data = $this->_db->fetchAssoc($purchase);
            $monthly_chart[$month] = $data['purchase_quantity']==null?0:$data['purchase_quantity'];
        }
        return $monthly_chart;
    }

    public function sales_chart(){
        $months = ['January','February','March','April','May','June','July',
            'August','September','October','November','December'];
        $monthly_chart = [];
        foreach ($months as $month){
            $start = date('Y-m-01',strtotime($month." ".date('Y')));
            $end = date('Y-m-t',strtotime($month." ".date('Y')));

            $orders = $this->_db->select(['sum(order_items.quantity) AS sales_quantity'])
                ->table('orders')
                ->join('order_items','order_items.order_id','orders.id')
                ->where('order_date','>=',$start)
                ->where('order_date','<=',$end)
                ->orderBy('orders.id','DESC')
                ->get();
            $data = $this->_db->fetchAssoc($orders);
            $monthly_chart[$month] = $data['sales_quantity']==null?0:$data['sales_quantity'];
        }
        return $monthly_chart;
    }


}