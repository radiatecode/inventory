<?php
/**
 * Created by PhpStorm.
 * User: Radiate Noor
 * Date: 6/25/2019
 * Time: 5:53 PM
 */
require '../vendor/autoload.php';
class Customers
{
    private $_db;
    private $messages=[];

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function allCustomers(){
        $customer = $this->_db->all('customers');
        if (!$customer){
            Session::flush('failed','Customer Query Error '.$this->_db->sql_error());
        }
        return $customer;
    }

    public function store($post,$files){
        $validation = Validation::PostValidate($post,[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'gender'=>'required',
            'present_address'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $rename=null;$permanent=null;
            if (!empty($files['photo']['name'])){
                $photo = $files['photo']['name'];
                $photo_tmp = $files['photo']['tmp_name'];
                $extension = pathinfo($photo,PATHINFO_EXTENSION);
                $rename = time().".".$extension;
                move_uploaded_file($photo_tmp, "../assets/images/" . $rename);
            }
            if (isset($post['same_as'])){
                $permanent = $this->_db->escapeString($post['present_address']);
            }else{
                $permanent = $this->_db->escapeString($post['permanent_address']);
            }
            $insert = $this->_db->insert('customers',[
                'name'=>$this->_db->escapeString($post['name']),
                'email'=>$this->_db->escapeString($post['email']),
                'phone'=>$this->_db->escapeString($post['phone']),
                'gender'=>$this->_db->escapeString($post['gender']),
                'present_address'=>$this->_db->escapeString($post['present_address']),
                'permanent_address'=>$permanent,
                'photo'=>$rename,
                'created_at'=>date('Y-m-d h:i:s')
            ]);
            if ($insert){
                Session::flush('success','You Have Successfully Saved The Customer');
            }else{
                Session::flush('failed','Customer Insertion Error. '.$this->_db->sql_error());
            }

        }
    }

    public function update($post,$files,$id){
        $validation = Validation::PostValidate($post,[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'gender'=>'required',
            'present_address'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $rename=null;$permanent=null;
            if (!empty($files['photo']['name'])){
                $photo = $files['photo']['name'];
                $photo_tmp = $files['photo']['tmp_name'];
                $extension = pathinfo($photo,PATHINFO_EXTENSION);
                $rename = time().".".$extension;
                move_uploaded_file($photo_tmp, "../assets/images/" . $rename);
            }else{
                $rename = $this->_db->escapeString($post['old_photo']);
            }
            if (isset($post['same_as'])){
                $permanent = $this->_db->escapeString($post['present_address']);
            }else{
                $permanent = $this->_db->escapeString($post['permanent_address']);
            }
            $update = $this->_db->update('customers',[
                'name'=>$this->_db->escapeString($post['name']),
                'email'=>$this->_db->escapeString($post['email']),
                'phone'=>$this->_db->escapeString($post['phone']),
                'gender'=>$this->_db->escapeString($post['gender']),
                'present_address'=>$this->_db->escapeString($post['present_address']),
                'permanent_address'=>$permanent,
                'photo'=>$rename
            ])
            ->where('id','=',$id)
            ->get();

            if ($update){
                Session::flush('success','You Have Successfully Updated The Customer');
            }else{
                Session::flush('failed','Customer Update Error. '.$this->_db->sql_error());
            }

        }
    }

    public function customer_info($id){
        $result = $this->_db->find('customers',$id);
        if (!$result){
            Session::flush('failed','Customer Insertion Error. '.$this->_db->sql_error());
            return false;
        }
        return $this->_db->fetchAssoc($result);
    }

    public function delete_customer($id){
        $result = $this->_db->delete('customers')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

    public function getMessage(){
        return count($this->messages)>0?$this->messages:[];
    }


}