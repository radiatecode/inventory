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

    public function store($post){
        $validation = Validation::PostValidate($post,[
            'order_no'=>'required',
            'payment_method'=>'required',
            'return_date'=>'required',
            'unit_price'=>'required|array',
            'quantity'=>'is_array|minimum:1',
            'total'=>'required_if:quantity|is_array',
            'sub_total'=>'required|number',
            'vat'=>'number',
            'vat_amount'=>'number',
            'grand_total'=>'required|number',
            'cash_return'=>'required|number'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $ori_insert=null;
            $created_at = date('Y-m-d h:i:s');
            $insert = $this->_db->insert('order_return',[
                'order_id'=>$this->_db->escapeString($post['order_no']),
                'return_date'=>$this->_db->escapeString($post['return_date']),
                'note'=>$this->_db->escapeString($post['note']),
                'created_at'=>$created_at
            ]);
            $return_id = $this->_db->lastInsertedID();

            if (!empty($post['product'])){
                $products = $post['product'];
                $unit_price = $post['unit_price'];
                $quantity = $post['quantity'];
                $total = $post['total'];
                for ($i=0;$i<count($products);$i++){
                    if (!empty($quantity[$i])) {
                        $ori_insert = $this->_db->insert('order_return_items', [
                            'return_id' => $return_id,
                            'product_id' => $this->_db->escapeString($products[$i]),
                            'quantity' => $this->_db->escapeString($quantity[$i]),
                            'unit_price' => $this->_db->escapeString($unit_price[$i]),
                            'total' => $this->_db->escapeString($total[$i]),
                            'created_at' => $created_at
                        ]);
                    }
                }
            }
            $payments = $this->_db->insert('order_return_payment',[
                'return_id'=>$return_id,
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount_given'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'cash_return'=>$this->_db->escapeString($post['cash_return']),
                'adjust_amount'=>$this->_db->escapeString($post['adjust_amount']),
                'created_at'=>$created_at
            ]);
            if (!$insert || !$ori_insert || !$payments) {
                Session::flush('failed', 'Data Insertion Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Inserted');
            }
        }
    }
    public function allSalesReturn(){
        $salesReturns = $this->_db->select(['order_return.id as return_id','sales_order','return_date',
            'sum(quantity) as total_qty','order_return_payment.*'])
            ->table('order_return')
            ->join('orders','order_return.order_id','orders.id')
            ->join('order_return_payment','order_return.id','order_return_payment.return_id')
            ->leftJoin('order_return_items','order_return.id','order_return_items.return_id')
            ->groupBy(['order_return_items.return_id'])
            ->orderBy('order_return.id','DESC')
            ->get();
        if ($salesReturns){
            return $salesReturns;
        }
        Session::flush('failed','Query Error!' .$this->_db->sql_error());
    }

    public function viewSalesReturn($id){
        $order = $this->_db->select(['order_return.*','orders.*','name','order_return_payment.*'])
            ->table('order_return')
            ->join('orders','orders.id','order_return.order_id')
            ->join('customers','customers.id','orders.customer_id')
            ->join('order_return_payment','order_return.id','order_return_payment.return_id')
            ->where('order_return.id','=',$id)
            ->get();
        return $this->_db->fetchAssoc($order);
    }

    public function viewSalesReturnItems($id){
        $items = $this->_db->select(['order_return_items.*'])
            ->table('order_return_items')
            ->where('return_id','=',$id)
            ->get();
        return $this->_db->fetchAll($items);
    }

}