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
            'products'=>$this->_db->fetchAll($products),
            'brands'=>$this->_db->fetchAll($brands),
            'categories'=>$this->_db->fetchAll($categories)
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
            $result = $this->_db->fetchAll($data);

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

    public function getMessage(){
        return $this->messages;
    }

}