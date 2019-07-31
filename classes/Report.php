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
            ->orderBy('product_name','ASC')->get();
        $brands = $this->_db->select(['brands.*'])
            ->table('brands')
            ->orderBy('brand_name','ASC')->get();
        $categories = $this->_db->select(['categories.*'])
            ->table('categories')
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
            $query = $this->_db->select(['SUM(quantity) as purchase_qty','products.product_name'])
                ->table('purchase')
                ->join('purchase_items','purchase.id','purchase_items.purchase_id')
                ->join('products','products.id','purchase_items.product_id');
            if(!empty($post['products'])){
                $query->where('products.id','=',$post['products']);
            }
            if(!empty($post['categories'])){
                $query->where('products.category_id','=',$post['categories']);
            }
            if(!empty($post['brands'])){
                $query->where('products.brand_id','=',$post['brands']);
            }
            $data = $query->groupBy(['purchase_items.product_id'])->get();
            return $this->_db->fetchAll($data);
        }
    }

    public function getMessage(){
        return $this->messages;
    }

}