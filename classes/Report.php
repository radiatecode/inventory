<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2019
 * Time: 5:24 PM
 */

class Report
{
    private $_db;
    private $messages=[];

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function search_options(){
        $products = $this->_db->select(['products.*'])
            ->table('products')
            ->where('enable','=',1)
            ->orderBy('product_name','ASC')->get();
        $brands = $this->_db->select(['brands.*'])
            ->table('brands')
            ->where('enable','=',1)
            ->orderBy('brand_name','ASC')->get();
        $categories = $this->_db->select(['categories.*'])
            ->table('categories')
            ->where('display','=',1)
            ->orderBy('name','ASC')->get();
        return [
            'products'=>$products,
            'brands'=>$brands,
            'categories'=>$categories
        ];
    }

    public function search($post){
        $validation = Validation::PostValidate($post,[
            'start_date'=>'required',
            'end_date'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $query = $this->_db->select(['SUM(quantity) as purchase_qty','products.id','products.product_name','brands.brand_name',
                'categories.name'])
                ->table('purchase')
                ->join('purchase_items','purchase.id','purchase_items.purchase_id')
                ->join('products','products.id','purchase_items.product_id')
                ->join('brands','brands.id','products.brand_id')
                ->join('categories','categories.id','products.category_id');

            if(!empty($post['products'])){
                $query->where('products.id','=',$post['products']);
            }
            if(!empty($post['categories'])){
                $query->where('products.category_id','=',$post['categories']);
            }
            if(!empty($post['brands'])){
                $query->where('products.brand_id','=',$post['brands']);
            }
            if(!empty($post['start_date']) && !empty($post['end_date'])){
                $query->where('purchase.order_date','>=',$post['start_date'])
                    ->where('purchase.order_date','<=',$post['end_date']);
            }
            $data = $query->groupBy(['purchase_items.product_id'])->get();
            $result = $data;

            $sales_result = null;$purchase_return_result = null;
            $custom = [];
            foreach ($result as $item){
                $sales_query = $this->_db->select(['sum(order_items.quantity) AS sale_quantity'])
                    ->table('order_items')
                    ->join('orders','orders.id','order_items.order_id')
                    ->where('product_id','=',$item['id']);
                if(!empty($post['start_date']) && !empty($post['end_date'])){
                    $sales_query->where('orders.order_date','>=',$post['start_date'])
                        ->where('orders.order_date','<=',$post['end_date']);
                }
                $sales =  $sales_query->groupBy(['order_items.product_id'])->get();
                $sales_result = $this->_db->fetchAssoc($sales);

               $purchase_return_query = $this->_db->select(['sum(purchase_return_items.quantity) AS purchase_return_quantity'])
                    ->table('purchase_return_items')
                    ->join('purchase_return','purchase_return.id','purchase_return_items.return_id')
                    ->where('product_id','=',$item['id']);
                   if(!empty($post['start_date']) && !empty($post['end_date'])){
                       $purchase_return_query->where('purchase_return.return_date','>=',$post['start_date'])
                           ->where('purchase_return.return_date','<=',$post['end_date']);
                   }
                $purchase_return = $purchase_return_query->groupBy(['purchase_return_items.product_id'])->get();
                $purchase_return_result = $this->_db->fetchAssoc($purchase_return);
                $custom[] =[
                    'product_name'=>$item['product_name'],
                    'brand_name'=>$item['brand_name'],
                    'category_name'=>$item['name'],
                    'purchase_qty'=>$item['purchase_qty'],
                    'sales_qty'=>$sales_result['sale_quantity'],
                    'purchase_return_qty'=>$purchase_return_result['purchase_return_quantity'],
                    'available'=>($item['purchase_qty']-($sales_result['sale_quantity']+$purchase_return_result['purchase_return_quantity']))
                ];
            }
            return [
                'data_result'=>$custom,
                'search'=>[
                    'start_date'=>$post['start_date'],
                    'end_date'=>$post['end_date'],
                    'products'=>$post['products'],
                    'brands'=>$post['brands'],
                    'categories'=>$post['categories'],
                ]
            ];
        }
    }

    public function purchase_search($post){
        $validation = Validation::PostValidate($post,[
            'start_date'=>'required',
            'end_date'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $query = $this->_db->select(['SUM(quantity) as purchase_qty','products.id','products.product_name','brands.brand_name',
                'categories.name'])
                ->table('purchase')
                ->join('purchase_items','purchase.id','purchase_items.purchase_id')
                ->join('products','products.id','purchase_items.product_id')
                ->join('brands','brands.id','products.brand_id')
                ->join('categories','categories.id','products.category_id');
            if(!empty($post['categories'])){
                $query->where('products.category_id','=',$post['categories']);
            }
            if(!empty($post['brands'])){
                $query->where('products.brand_id','=',$post['brands']);
            }
            if(!empty($post['start_date']) && !empty($post['end_date'])){
                $query->where('purchase.order_date','>=',$post['start_date'])
                    ->where('purchase.order_date','<=',$post['end_date']);
            }
            $data = $query->groupBy(['purchase_items.product_id'])->get();
            $result = $data;

            $custom = [];
            foreach ($result as $item){
                $custom[] =[
                    'product_name'=>$item['product_name'],
                    'brand_name'=>$item['brand_name'],
                    'category_name'=>$item['name'],
                    'purchase_qty'=>$item['purchase_qty'],
                ];
            }
            return [
                'data_result'=>$custom,
                'search'=>[
                    'start_date'=>$post['start_date'],
                    'end_date'=>$post['end_date'],
                    'brands'=>$post['brands'],
                    'categories'=>$post['categories'],
                ]
            ];
        }
    }

    public function sales_search($post){
        $validation = Validation::PostValidate($post,[
            'start_date'=>'required',
            'end_date'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $query = $this->_db->select(['SUM(quantity) as sales_qty','products.id','products.product_name','brands.brand_name',
                'categories.name'])
                ->table('orders')
                ->join('order_items','orders.id','order_items.order_id')
                ->join('products','products.id','order_items.product_id')
                ->join('brands','brands.id','products.brand_id')
                ->join('categories','categories.id','products.category_id');
            if(!empty($post['categories'])){
                $query->where('products.category_id','=',$post['categories']);
            }
            if(!empty($post['brands'])){
                $query->where('products.brand_id','=',$post['brands']);
            }
            if(!empty($post['start_date']) && !empty($post['end_date'])){
                $query->where('orders.order_date','>=',$post['start_date'])
                    ->where('orders.order_date','<=',$post['end_date']);
            }
            $data = $query->groupBy(['order_items.product_id'])->get();
            $result = $data;

            $custom = [];
            foreach ($result as $item){
                $custom[] =[
                    'product_name'=>$item['product_name'],
                    'brand_name'=>$item['brand_name'],
                    'category_name'=>$item['name'],
                    'sales_qty'=>$item['sales_qty'],
                ];
            }
            return [
                'data_result'=>$custom,
                'search'=>[
                    'start_date'=>$post['start_date'],
                    'end_date'=>$post['end_date'],
                    'brands'=>$post['brands'],
                    'categories'=>$post['categories'],
                ]
            ];
        }
    }

    public function getOrders($type){
        if ($type=="purchase"){
            $purchase = $this->_db->select(['purchase_order_no'])
                ->table('purchase')
                ->orderBy('purchase_order_no','DESC')
                ->get();
            return $this->_db->fetchAll($purchase);
        }elseif ($type=="sales"){
            $sales = $this->_db->select(['sales_order'])
                ->table('orders')
                ->orderBy('sales_order','DESC')
                ->get();
            return $this->_db->fetchAll($sales);

        }
        return false;
    }

    public function generateInvoice($post){
        $custom=[];
        $validation = Validation::PostValidate($post,[
            'invoice_type'=>'required',
            'orders'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            if ($post['invoice_type']=="purchase"){
                $purchases = $this->_db->select(['purchase.id as purchase_id','purchase_order_no','order_date','purchase_payment.*','suppliers.*'])
                    ->table('purchase')
                    ->join('suppliers','purchase.supplier_id','suppliers.id')
                    ->join('purchase_payment','purchase.id','purchase_payment.purchase_id')
                    ->where('purchase_order_no','=',$post['orders'])
                    ->orderBy('purchase.id','DESC')
                    ->get();
                $data = $this->_db->fetchAssoc($purchases);
                $items = $this->_db->select(['product_name','model','purchase_items.*'])
                    ->table('purchase_items')
                    ->join('products','products.id','purchase_items.product_id')
                    ->where('purchase_id','=',$data['purchase_id'])
                    ->get();
                $items_data = $this->_db->fetchAll($items);
                $custom = [
                    'from_name'=>$data['name'],
                    'from_address'=>$data['address'],
                    'from_phone'=>$data['phone'],
                    'from_email'=>$data['email'],
                    'to_name'=>'PGDIT07 Ju Inc',
                    'to_address'=>'Savar 1287, Jahangir Nagar, Dhaka',
                    'to_phone'=>'018XXXXXXX',
                    'to_email'=>'pgd@email.com',
                    'order'=>$data['purchase_order_no'],
                    'payment_date'=>$data['payment_date'],
                    'paid'=>$data['paid_amount'],
                    'due'=>$data['due_amount'],
                    'vat'=>$data['vat'],
                    'discount'=>$data['discount'],
                    'sub_total'=>$data['sub_total'],
                    'payment_method'=>$data['payment_method'],
                    'note'=>$data['note'],
                    'items_data'=>$items_data,
                    'search'=>[
                        'invoice_type'=>$post['invoice_type'],
                        'orders'=>$post['orders']
                    ]
                ];
            }if ($post['invoice_type']=="sales"){
                $orders = $this->_db->select(['orders.id as order_id','sales_order','order_date','order_payment.*','customers.*'])
                    ->table('orders')
                    ->join('customers','orders.customer_id','customers.id')
                    ->join('order_payment','orders.id','order_payment.order_id')
                    ->where('sales_order','=',$post['orders'])
                    ->orderBy('orders.id','DESC')
                    ->get();
                $data = $this->_db->fetchAssoc($orders);
                $items = $this->_db->select(['product_name','model','order_items.*'])
                    ->table('order_items')
                    ->join('products','products.id','order_items.product_id')
                    ->where('order_id','=',$data['order_id'])
                    ->get();
                $items_data = $this->_db->fetchAll($items);
                $custom = [
                    'from_name'=>'PGDIT07 Ju Inc',
                    'from_address'=>'Savar 1287, Jahangir Nagar, Dhaka',
                    'from_phone'=>'018XXXXXXX',
                    'from_email'=>'pgd@email.com',
                    'to_name'=>$data['name'],
                    'to_address'=>$data['present_address'],
                    'to_phone'=>$data['phone'],
                    'to_email'=>$data['email'],
                    'order'=>$data['sales_order'],
                    'payment_date'=>$data['order_date'],
                    'paid'=>$data['paid_amount'],
                    'due'=>$data['due_amount'],
                    'vat'=>$data['vat'],
                    'discount'=>$data['discount'],
                    'sub_total'=>$data['sub_total'],
                    'payment_method'=>$data['payment_method'],
                    'note'=>$data['note'],
                    'items_data'=>$items_data,
                    'search'=>[
                        'invoice_type'=>$post['invoice_type'],
                        'orders'=>$post['orders']
                    ]
                ];
            }
            return $custom;
        }
    }

    public function getMessage(){
        return $this->messages;
    }

}