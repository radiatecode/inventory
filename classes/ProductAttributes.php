<?php
/**
 * Created by PhpStorm.
 * User: Radiate
 * Date: 6/18/2019
 * Time: 3:25 PM
 */
require '../vendor/autoload.php';
class ProductAttributes
{
    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function store($post){
        if (isset($post['attribute_name']) && isset($post['category'])){

            $insert = $this->_db->insert('attributes',[
                'category_id'=>$this->_db->escapeString($post['category']),
                'attribute_name'=>$this->_db->escapeString($post['attribute_name']),
                'enable'=>1,
                'created_at'=>date('Y-m-d h:i:s')
            ]);
            if ($insert) {
                Session::flush('success','Successfully Inserted Attribute Data');
            }else{
                Session::flush('failed','Attribute Data Insertion Error! '.$this->_db->sql_error());
            }
        }else{
            Session::flush('failed','All Filed Are Required');
        }
    }

    public function update($post){
        if (isset($post['attribute_name']) && isset($post['category'])){

            $update = $this->_db->update('attributes', [
                    'category_id'=>$this->_db->escapeString($post['category']),
                    'attribute_name'=>$this->_db->escapeString($post['attribute_name'])
                ])
                ->where('id','=',$post['edit_id'])
                ->get();
            if ($update) {
                Session::flush('success','Successfully Updated Attribute Data');
            }else{
                Session::flush('failed','Attribute Data Update Error! '.$this->_db->sql_error());
            }
        }
    }

    public function allAttributes(){
        $attributes = $this->_db->select(['attributes.id','categories.name','category_id',
            'attribute_name','enable','created_at'])
            ->table('attributes')
            ->join('categories','categories.id','attributes.category_id')
            ->orderBy('attributes.id','DESC')
            ->get();
        if (!$attributes){
            Session::flush('failed','Query Execution Error! '.$this->_db->sql_error());
        }
        return $attributes;
    }

    public function getAttributesByCategory($cat_id){
        $result = $this->_db->select(['id','attribute_name'])
            ->table('attributes')
            ->where('category_id','=',$cat_id)
            ->where('enable','=',1)
            ->orderBy('attributes.id','DESC')
            ->get();
        if (!$result){
            return false;
        }
        return $this->_db->fetchAll($result);
    }

    public function delete_attributes($id){
        $result = $this->_db->delete('products_attributes')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

    public function __destruct()
    {
        $this->_db->close();
    }

    public function delete_attribute($id){
        $result = $this->_db->delete('attributes')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

    public function enable_disable($id,$type){
        $result='';
        if ($type=='enable') {
            $result = $this->_db->update('attributes', [
                'enable' =>1
            ])->where('id', '=', $id)->get();
        }else{
            $result = $this->_db->update('attributes', [
                'enable' =>0
            ])->where('id', '=', $id)->get();
        }
        if (!$result){
            return false;
        }
        return true;
    }


}