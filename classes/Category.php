<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 6/18/2019
 * Time: 1:01 PM
 */
require '../vendor/autoload.php';
class Category
{
    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function store($post){
        if (isset($post['name'])){
            $insert = $this->_db->insert('categories',[
                'name'=>$post['name'],
                'description'=>$post['description'],
                'display'=>1
            ]);
            if ($insert) {
                Session::flush('success','Successfully Inserted Category Data');
            }else{
                Session::flush('failed','Category Data Insertion Error! '.$this->_db->sql_error());
            }
        }
    }

    public function update($post){
        if (isset($post['name'])){

            $update = $this->_db->update('categories',
                [
                    'name'=>$post['name'],
                    'description'=>$post['description']
                ],
                [
                    'id'=>$post['edit_id']
                ]
            );
            if ($update) {
                Session::flush('success','Successfully Updated Category Data');
            }else{
                Session::flush('failed','Category Data Update Error! '.$this->_db->sql_error());
            }
        }
    }

    public function allCategories(){
        $cat = $this->_db->all('categories');
        return $cat;
    }
}