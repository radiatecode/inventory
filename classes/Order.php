<?php
/**
 * Created by PhpStorm.
 * User: Radiate Noor
 * Date: 7/2/2019
 * Time: 2:36 PM
 */
require '../vendor/autoload.php';
class Order
{
    private $_db;
    private $messages=[];

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function store($post){
        $validation = Validation::PostValidate($post,[
            'customer'=>'required',
            'contact'=>'required',
            'billing_address'=>'required',
            'order_date'=>'required',
            'delivery_date'=>'required',
            'unit_price'=>'required|array',
            'quantity'=>'required|array',
            'total'=>'required|array',
            'sub_total'=>'required|number',
            'discount'=>'required|number',
            'total_amount'=>'required|number',
            'vat'=>'required|number',
            'vat_amount'=>'required|number',
            'grand_total'=>'required|number',
            'paid'=>'required|number',
            'due'=>'required|number',
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $oi_insert=null;$order_id=0;
            $created_at = date('Y-m-d h:i:s');
            $insert = $this->_db->insert('orders',[
                'customer_id'=>$this->_db->escapeString($post['customer']),
                'contact'=>$this->_db->escapeString($post['contact']),
                'email'=>$this->_db->escapeString($post['email']),
                'order_date'=>$this->_db->escapeString($post['order_date']),
                'delivery_date'=>$this->_db->escapeString($post['delivery_date']),
                'billing_address'=>$this->_db->escapeString($post['billing_address']),
                'note'=>$this->_db->escapeString($post['note']),
                'sales_person'=>Session::get('user','id'),
                'order_status'=>'pending',
                'created_at'=>$created_at
            ]);
            if ($insert) {
                $order_id = $this->_db->lastInsertedID();
            }
            if (!empty($post['product'])){
                $products = $post['product'];
                $unit_price = $post['unit_price'];
                $quantity = $post['quantity'];
                $total = $post['total'];
                for ($i=0;$i<count($products);$i++){
                    $oi_insert = $this->_db->insert('order_items',[
                        'order_id'=>$order_id,
                        'product_id'=>$this->_db->escapeString($products[$i]),
                        'quantity'=>$this->_db->escapeString($quantity[$i]),
                        'unit_price'=>$this->_db->escapeString($unit_price[$i]),
                        'total'=>$this->_db->escapeString($total[$i]),
                        'created_at'=>$created_at
                    ]);
                }
            }
            $payments = $this->_db->insert('order_payment',[
                'order_id'=>$order_id,
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'paid_amount'=>$this->_db->escapeString($post['paid']),
                'due_amount'=>$this->_db->escapeString($post['due']),
                'created_at'=>$created_at
            ]);
            if (!$insert || !$oi_insert || !$payments) {
                Session::flush('failed', 'Data Insertion Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Inserted');
            }
        }
    }

    public function getMessage(){
        return $this->messages;
    }

    public function allOrders(){
        $orders = $this->_db->select(['orders.id as order_id','name','order_date',
            'order_status','sum(quantity) as total_qty','order_payment.*'])
            ->table('orders')
            ->join('customers','orders.customer_id','customers.id')
            ->join('order_payment','orders.id','order_payment.order_id')
            ->leftJoin('order_items','orders.id','order_items.order_id')
            ->groupBy('order_items.order_id')
            ->orderBy('orders.id','DESC')
            ->get();
        if ($orders){
            return $orders;
        }
        Session::flush('failed','Query Error!' .$this->_db->sql_error());
    }

    public function viewOrder($id){
       $order = $this->_db->select('order.*','order_payment.*','order_items.*')
           ->table('order')
           ->join('order_payment','orders.id','order_payment.order_id')
           ->join('order_items','orders.id','order_items.order_id')
           ->where('order.id','=',$id)
           ->get();

    }

}