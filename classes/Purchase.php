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
            'product'=>'required|is_array|minimum:1',
            'unit_price'=>'required|is_array|number',
            'quantity'=>'is_array|minimum:1|number',
            'total'=>'required|is_array|number',
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
            $pi_insert=null;$purchase_id=0;
            $created_at = date('Y-m-d h:i:s');
            $insert = $this->_db->insert('purchase',[
                'purchase_order_no'=>'PO-'.date('ydis'),
                'supplier_id'=>$this->_db->escapeString($post['supplier']),
                'contact'=>$this->_db->escapeString($post['contact']),
                'email'=>$this->_db->escapeString($post['email']),
                'order_date'=>$this->_db->escapeString($post['order_date']),
                'billing_address'=>$this->_db->escapeString($post['billing_address']),
                'note'=>$this->_db->escapeString($post['note']),
                'created_at'=>$created_at
            ]);
            $purchase_id = $this->_db->lastInsertedID();

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
    public function update($post,$purchase_id){
        $validation = Validation::PostValidate($post,[
            'supplier'=>'required',
            'contact'=>'required',
            'payment_method'=>'required',
            'billing_address'=>'required',
            'order_date'=>'required',
            'total'=>'required_if:product',
            'sub_total'=>'required|number',
            'discount'=>'number',
            'total_amount'=>'required|number',
            'vat'=>'required|number',
            'vat_amount'=>'required|number',
            'grand_total'=>'required|number',
            'paid'=>'required|number',
            'due'=>'number',
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $pi_update=null;
            $created_at = date('Y-m-d h:i:s');
            $update = $this->_db->update('purchase',[
                'supplier_id'=>$this->_db->escapeString($post['supplier']),
                'contact'=>$this->_db->escapeString($post['contact']),
                'email'=>$this->_db->escapeString($post['email']),
                'order_date'=>$this->_db->escapeString($post['order_date']),
                'billing_address'=>$this->_db->escapeString($post['billing_address']),
                'note'=>$this->_db->escapeString($post['note'])
            ])->where('id','=',$purchase_id)->get();

            if (!empty($post['purchase_items_id'])){
                $purchase_items_id = $post['purchase_items_id'];
                $edit_product = $post['edit_product'];
                $edit_unit_price = $post['edit_unit_price'];
                $edit_quantity = $post['edit_quantity'];
                $edit_total = $post['edit_total'];
                for ($i=0;$i<count($purchase_items_id);$i++){
                    if (!empty($edit_quantity[$i])) {
                        $pi_update = $this->_db->update('purchase_items', [
                            'purchase_id' => $purchase_id,
                            'product_id' => $this->_db->escapeString($edit_product[$i]),
                            'quantity' => $this->_db->escapeString($edit_quantity[$i]),
                            'unit_price' => $this->_db->escapeString($edit_unit_price[$i]),
                            'total' => $this->_db->escapeString($edit_total[$i]),
                        ])->where('id', '=', $purchase_items_id[$i])->get();
                    }else{
                        $delete = $this->_db->delete('purchase_items')
                            ->where('id','=',$purchase_items_id[$i])->get();
                    }
                }
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
            $payments = $this->_db->update('purchase_payment',[
                'purchase_id'=>$purchase_id,
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'payment_date'=>$this->_db->escapeString($post['payment_date']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'paid_amount'=>$this->_db->escapeString($post['paid']),
                'due_amount'=>$this->_db->escapeString($post['due'])
            ])->where('purchase_id','=',$purchase_id)->get();

            if (!$update || !$pi_update || !$payments) {
                Session::flush('failed', 'Data Update Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Updated');
            }
        }
    }

    public function allPurchased(){
        $purchases = $this->_db->select(['purchase.id as purchase_id','purchase_order_no','name','order_date',
            'sum(quantity) as total_qty','purchase_payment.*'])
            ->table('purchase')
            ->join('suppliers','purchase.supplier_id','suppliers.id')
            ->join('purchase_payment','purchase.id','purchase_payment.purchase_id')
            ->leftJoin('purchase_items','purchase.id','purchase_items.purchase_id')
            ->groupBy(['purchase_items.purchase_id'])
            ->orderBy('purchase.id','DESC')
            ->get();

        if ($purchases){
            return $purchases;
        }
        Session::flush('failed','Query Error!' .$this->_db->sql_error());
    }

    public function viewPurchase($id){
        $order = $this->_db->select(['purchase.*','purchase_payment.*'])
            ->table('purchase')
            ->join('purchase_payment','purchase.id','purchase_payment.purchase_id')
            ->where('purchase.id','=',$id)
            ->get();
        return $this->_db->fetchAssoc($order);
    }

    public function viewPurchaseItems($id){
        $items = $this->_db->select(['purchase_items.*'])
            ->table('purchase_items')
            ->where('purchase_id','=',$id)
            ->get();

        return $this->_db->fetchAll($items);
    }

    public function getPurchaseOrder($supplier){
        $items = $this->_db->select(['purchase_order_no','id'])
            ->table('purchase')
            ->where('supplier_id','=',$supplier)
            ->get();
        if (!$items){
            return false;
        }
        return $this->_db->fetchAll($items);
    }

    public function delete_purchase($id){
        $result = $this->_db->delete('purchase')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

    public function getMessage(){
        return $this->messages;
    }
}