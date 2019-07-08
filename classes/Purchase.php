<?php
/**
 * Created by PhpStorm.
 * User: Radiate
 * Date: 7/8/2019
 * Time: 4:09 PM
 */
require '../vendor/autoload.php';
class Purchase
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
            'supplier'=>'required',
            'contact'=>'required',
            'payment_method'=>'required',
            'billing_address'=>'required',
            'order_date'=>'required',
            'product'=>'required|array',
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
            $pi_insert=null;$purchase_id=0;
            $created_at = date('Y-m-d h:i:s');
            $insert = $this->_db->insert('purchase',[
                'supplier_id'=>$this->_db->escapeString($post['supplier']),
                'contact'=>$this->_db->escapeString($post['contact']),
                'email'=>$this->_db->escapeString($post['email']),
                'order_date'=>$this->_db->escapeString($post['order_date']),
                'billing_address'=>$this->_db->escapeString($post['billing_address']),
                'note'=>$this->_db->escapeString($post['note']),
                'created_at'=>$created_at
            ]);
            if ($insert) {
                $purchase_id = $this->_db->lastInsertedID();
            }
            if (!empty($post['product'])){
                $products = $post['product'];
                $unit_price = $post['unit_price'];
                $quantity = $post['quantity'];
                $total = $post['total'];
                for ($i=0;$i<count($products);$i++){
                    $pi_insert = $this->_db->insert('purchase_items',[
                        'purchase_id'=>$purchase_id,
                        'product_id'=>$this->_db->escapeString($products[$i]),
                        'quantity'=>$this->_db->escapeString($quantity[$i]),
                        'unit_price'=>$this->_db->escapeString($unit_price[$i]),
                        'total'=>$this->_db->escapeString($total[$i]),
                        'created_at'=>$created_at
                    ]);
                }
            }
            $payments = $this->_db->insert('purchase_payment',[
                'purchase_id'=>$purchase_id,
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'payment_date'=>$this->_db->escapeString($post['payment_date']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'paid_amount'=>$this->_db->escapeString($post['paid']),
                'due_amount'=>$this->_db->escapeString($post['due']),
                'created_at'=>$created_at
            ]);
            if (!$insert || !$pi_insert || !$payments) {
                Session::flush('failed', 'Data Insertion Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Inserted');
            }
        }
    }

    public function getMessage(){
        return $this->messages;
    }
}