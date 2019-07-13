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
            'quantity'=>'is_array|minimum:2'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
           echo "ok";
        }
    }
}