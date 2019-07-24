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
            'product'=>'required|is_array|minimum:1',
            'unit_price'=>'required|is_array',
            'quantity'=>'required|is_array',
            'total'=>'required|is_array',
            'sub_total'=>'required|number',
            'discount'=>'number',
            'total_amount'=>'required|number',
            'vat'=>'number',
            'vat_amount'=>'number',
            'grand_total'=>'required|number',
            'paid'=>'required|number',
            'due'=>'number',
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $oi_insert=null;$order_id=0;
            $created_at = date('Y-m-d h:i:s');
            $insert = $this->_db->insert('orders',[
                'sales_order'=>'SO-'.date('ydis'),
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
    public function update($post,$order_id){
        $validation = Validation::PostValidate($post,[
            'customer'=>'required',
            'contact'=>'required',
            'billing_address'=>'required',
            'order_date'=>'required',
            'delivery_date'=>'required',
            'total'=>'required_if:product',
            'sub_total'=>'required|number',
            'discount'=>'number',
            'total_amount'=>'required|number',
            'vat'=>'number',
            'vat_amount'=>'number',
            'grand_total'=>'required|number',
            'paid'=>'required|number',
            'due'=>'number'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $oi_insert=null;$oi_update=null;
            $created_at = date('Y-m-d h:i:s');
            $update_orders = $this->_db->update('orders',[
                'customer_id'=>$this->_db->escapeString($post['customer']),
                'contact'=>$this->_db->escapeString($post['contact']),
                'email'=>$this->_db->escapeString($post['email']),
                'order_date'=>$this->_db->escapeString($post['order_date']),
                'delivery_date'=>$this->_db->escapeString($post['delivery_date']),
                'billing_address'=>$this->_db->escapeString($post['billing_address']),
                'note'=>$this->_db->escapeString($post['note'])
            ])->where('id','=',$order_id)->get();

            if (!empty($post['order_items_id'])){
                $order_items_id = $post['order_items_id'];
                $edit_product = $post['edit_product'];
                $edit_unit_price = $post['edit_unit_price'];
                $edit_quantity = $post['edit_quantity'];
                $edit_total = $post['edit_total'];
                for ($i=0;$i<count($order_items_id);$i++){
                    $oi_update = $this->_db->update('order_items',[
                        'order_id'=>$order_id,
                        'product_id'=>$this->_db->escapeString($edit_product[$i]),
                        'quantity'=>$this->_db->escapeString($edit_quantity[$i]),
                        'unit_price'=>$this->_db->escapeString($edit_unit_price[$i]),
                        'total'=>$this->_db->escapeString($edit_total[$i]),
                    ])->where('id','=',$order_items_id[$i])->get();
                }
            }
            if (!empty($post['product'])){
                $product = $post['product'];
                $unit_price = $post['unit_price'];
                $quantity = $post['quantity'];
                $total = $post['total'];
                for ($i=0;$i<count($product);$i++){
                    $oi_insert = $this->_db->insert('order_items',[
                        'order_id'=>$order_id,
                        'product_id'=>$this->_db->escapeString($product[$i]),
                        'quantity'=>$this->_db->escapeString($quantity[$i]),
                        'unit_price'=>$this->_db->escapeString($unit_price[$i]),
                        'total'=>$this->_db->escapeString($total[$i]),
                        'created_at'=>$created_at,
                    ]);
                }
            }
            $payments = $this->_db->update('order_payment',[
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'paid_amount'=>$this->_db->escapeString($post['paid']),
                'due_amount'=>$this->_db->escapeString($post['due']),
            ])->where('order_id','=',$order_id)->get();

            if (!$update_orders || !$payments || !$oi_update) {
                Session::flush('failed', 'Data Update Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Updated');
            }
        }
    }

    public function getMessage(){
        return $this->messages;
    }

    public function allOrders(){
        $orders = $this->_db->select(['orders.id as order_id','sales_order','name','order_date',
            'order_status','sum(quantity) as total_qty','order_payment.*'])
            ->table('orders')
            ->join('customers','orders.customer_id','customers.id')
            ->join('order_payment','orders.id','order_payment.order_id')
            ->leftJoin('order_items','orders.id','order_items.order_id')
            ->groupBy(['order_items.order_id'])
            ->orderBy('orders.id','DESC')
            ->get();
        if ($orders){
            return $orders;
        }
        Session::flush('failed','Query Error!' .$this->_db->sql_error());
    }

    public function viewOrder($id){
       $order = $this->_db->select(['orders.*','name','order_payment.*'])
           ->table('orders')
           ->join('order_payment','orders.id','order_payment.order_id')
           ->join('customers','customers.id','orders.customer_id')
           ->where('orders.id','=',$id)
           ->get();
       return $this->_db->fetchAssoc($order);
    }

    public function viewOrderItems($id){
        $items = $this->_db->select(['order_items.*'])
            ->table('order_items')
            ->where('order_id','=',$id)
            ->get();
        return $this->_db->fetchAll($items);
    }

    public function delete_order($id){
        $result = $this->_db->delete('orders')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

}