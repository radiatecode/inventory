<?php
/**
 * Created by PhpStorm.
 * User: Radiate
 * Date: 6/18/2019
 * Time: 3:25 PM
 */
require '../vendor/autoload.php';
class Suppliers
{
    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }


    public function store($post){
        if (isset($post['name']) && isset($post['address']) &&
            isset($post['email']) && isset($post['phone'])){

            $insert = $this->_db->insert('suppliers',[
                'name'=>$this->_db->escapeString($post['name']),
                'address'=>$this->_db->escapeString($post['address']),
                'email'=>$this->_db->escapeString($post['email']),
                'phone'=>$this->_db->escapeString($post['phone']),
                'created_at'=>date('Y-m-d h:i:s')
            ]);

            if ($insert) {
                Session::flush('success','Successfully Inserted Supplier Data');
            }else{
                Session::flush('failed','Supplier Data Insertion Error! '.$this->_db->sql_error());
            }
        }else{
            Session::flush('failed','All Filed Are Required');
        }
    }

    public function update($post){
        if (isset($post['name']) && isset($post['address']) &&
            isset($post['email']) && isset($post['phone'])){

            $update = $this->_db->update('suppliers', [
                    'name'=>$this->_db->escapeString($post['name']),
                    'address'=>$this->_db->escapeString($post['address']),
                    'email'=>$this->_db->escapeString($post['email']),
                    'phone'=>$this->_db->escapeString($post['phone']),
                ])
                ->where('id','=',$post['edit_id'])
                ->get();
            if ($update) {
                Session::flush('success','Successfully Updated Supplier Data');
            }else{
                Session::flush('failed','Supplier Data Update Error! '.$this->_db->sql_error());
            }
        }
    }

    public function allSuppliers(){
        $suppliers = $this->_db->all('suppliers');
        return $suppliers;
    }

    public function delete_supplier($id){
        $result = $this->_db->delete('suppliers')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

}