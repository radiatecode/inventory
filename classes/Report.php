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

}