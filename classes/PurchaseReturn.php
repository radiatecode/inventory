<?php
/**
 * Created by PhpStorm.
 * User: Radiate
 * Date: 7/13/2019
 * Time: 2:19 PM
 */
require '../vendor/autoload.php';
class PurchaseReturn
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

    public function store($post){
        $validation = Validation::PostValidate($post,[
            'supplier'=>'required',
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
            'receipt_amount'=>'required|number'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $pri_insert=null;
            $created_at = date('Y-m-d h:i:s');
            $insert = $this->_db->insert('purchase_return',[
                'purchase_id'=>$this->_db->escapeString($post['order_no']),
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
                        $pri_insert = $this->_db->insert('purchase_return_items', [
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

            $payments = $this->_db->insert('purchase_return_payment',[
                'return_id'=>$return_id,
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount_given'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'receipt_amount'=>$this->_db->escapeString($post['receipt_amount']),
                'adjust_amount'=>$this->_db->escapeString($post['adjust_amount']),
                'created_at'=>$created_at
            ]);
            if (!$insert || !$pri_insert || !$payments) {
                Session::flush('failed', 'Data Insertion Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Inserted');
            }
        }
    }
    public function update($post,$id){
        $validation = Validation::PostValidate($post,[
            'payment_method'=>'required',
            'return_date'=>'required',
            'unit_price'=>'required|array',
            'quantity'=>'is_array|minimum:1',
            'total'=>'required_if:quantity|is_array',
            'sub_total'=>'required|number',
            'vat'=>'required|number',
            'vat_amount'=>'required|number',
            'grand_total'=>'required|number',
            'receipt_amount'=>'required|number'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $pri_update=null;
            var_dump($post['note']);
            $insert = $this->_db->update('purchase_return',[
                'return_date'=>$this->_db->escapeString($post['return_date']),
                'note'=>$this->_db->escapeString($post['note']),
            ])->where('id','=',$id)->get();

            if (!empty($post['purchase_return_items'])){
                $return_items_id = $post['purchase_return_items'];
                $unit_price = $post['unit_price'];
                $quantity = $post['quantity'];
                $total = $post['total'];
                for ($i=0;$i<count($return_items_id);$i++){
                    if (!empty($quantity[$i])) {
                        $pri_update = $this->_db->update('purchase_return_items', [
                            'quantity' => $this->_db->escapeString($quantity[$i]),
                            'unit_price' => $this->_db->escapeString($unit_price[$i]),
                            'total' => $this->_db->escapeString($total[$i]),
                        ])->where('id','=',$return_items_id[$i])->get();
                    }else{
                        $delete = $this->_db->delete('purchase_return_items')
                            ->where('id','=',$return_items_id[$i])->get();
                    }
                }
            }

            $payments = $this->_db->update('purchase_return_payment',[
                'payment_method'=>$this->_db->escapeString($post['payment_method']),
                'sub_total'=>$this->_db->escapeString($post['sub_total']),
                'discount_given'=>$this->_db->escapeString($post['discount']),
                'vat'=>$this->_db->escapeString($post['vat']),
                'receipt_amount'=>$this->_db->escapeString($post['receipt_amount']),
                'adjust_amount'=>$this->_db->escapeString($post['adjust_amount']),
            ])->where('return_id','=',$id)->get();

            if (!$insert || !$pri_update || !$payments) {
                Session::flush('failed', 'Data Update Error! ' . $this->_db->sql_error());
            }else{
                Session::flush('success','Successfully Data Updated');
            }
        }
    }

    public function allPurchaseReturn(){
        $purchaseReturns = $this->_db->select(['purchase_return.id as return_id','purchase_order_no','return_date',
            'sum(quantity) as total_qty','purchase_return_payment.*'])
            ->table('purchase_return')
            ->join('purchase','purchase_return.purchase_id','purchase.id')
            ->join('purchase_return_payment','purchase_return.id','purchase_return_payment.return_id')
            ->leftJoin('purchase_return_items','purchase_return.id','purchase_return_items.return_id')
            ->groupBy(['purchase_return_items.return_id'])
            ->orderBy('purchase_return.id','DESC')
            ->get();

        if ($purchaseReturns){
            return $purchaseReturns;
        }
        Session::flush('failed','Query Error!' .$this->_db->sql_error());
    }

    public function viewPurchaseReturn($id){
        $order = $this->_db->select(['purchase_return.*','purchase.*','name','purchase_return_payment.*'])
            ->table('purchase_return')
            ->join('purchase','purchase.id','purchase_return.purchase_id')
            ->join('suppliers','suppliers.id','purchase.supplier_id')
            ->join('purchase_return_payment','purchase_return.id','purchase_return_payment.return_id')
            ->where('purchase_return.id','=',$id)
            ->get();
        return $this->_db->fetchAssoc($order);
    }

    public function viewPurchaseReturnItems($id){
        $items = $this->_db->select(['purchase_id','purchase_return_items.*'])
            ->table('purchase_return')
            ->join('purchase_return_items','purchase_return.id','purchase_return_items.return_id')
            ->where('return_id','=',$id)
            ->get();
        $custom=[];
        foreach ($items as $item){
            $purchased = $this->_db->select(['quantity'])
                ->table('purchase_items')
                ->where('purchase_id','=',$item['purchase_id'])
                ->where('product_id','=',$item['product_id'])
                ->get();
            $purchased_qty = $this->_db->fetchAssoc($purchased);
            $custom[] =[
                 'id'=>$item['id'],
                 'product_id'=>$item['product_id'],
                 'purchase_qty'=>$purchased_qty['quantity'],
                 'unit_price'=>$item['unit_price'],
                 'quantity'=>$item['quantity'],
                 'total'=>$item['total'],
            ];
        }
        return $custom;
    }


    public function delete_return($id){
        $delete = $this->_db->delete('purchase_return')
            ->where('id','=',$id)->get();
        if (!$delete){
            return false;
        }
        return true;
    }
}