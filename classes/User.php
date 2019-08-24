<?php
/**
 * Created by PhpStorm.
 * User: optimus prime
 * Date: 8/24/2019
 * Time: 9:56 AM
 */
require '../vendor/autoload.php';
class User
{
    private $_db;
    private $messages=[];

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function get_user(){
        $id = Session::get('user','id');
        $user = $this->_db->select(['users.*'])
            ->table('users')
            ->where('id','=',$id)
            ->get();
        return $this->_db->fetchAssoc($user);
    }

    public function update_profile($post,$files){
        $validation = Validation::PostValidate($post,[
            'name'=>'required',
            'email'=>'required',
            'username'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $rename=null;
            $user_id = Session::get('user','id');
            if (!empty($files['user_photo']['name'])){
                $photo = $files['user_photo']['name'];
                $photo_tmp = $files['user_photo']['tmp_name'];
                $extension = pathinfo($photo,PATHINFO_EXTENSION);
                $rename = time().".".$extension;
                move_uploaded_file($photo_tmp, "../assets/images/" . $rename);
                $this->_db->update('users',[
                    'photo'=>$rename
                ])->where('id','=',$user_id)->get();
            }
            $update = $this->_db->update('users',[
                'name'=>$this->_db->escapeString($post['name']),
                'email'=>$this->_db->escapeString($post['email']),
                'username'=>$this->_db->escapeString($post['username'])
            ])->where('id','=',$user_id)->get();
            if ($update){
                Session::flush('success','Successfully Updated Profile Information');
            }else{
                Session::flush('failed','Update Error! '.$this->_db->sql_error());
            }
        }
    }

    public function password_update($post){
        $validation = Validation::PostValidate($post,[
            'password'=>'required',
            'password_confirmation'=>'required'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $user_id = Session::get('user','id');
            $update = $this->_db->update('users',[
                'password'=>md5($this->_db->escapeString($post['password'])),
            ])->where('id','=',$user_id)->get();
            if ($update){
                Session::flush('success','Successfully Updated Password');
            }else{
                Session::flush('failed','Update Error! '.$this->_db->sql_error());
            }
            if ($post['logout_confirmation']=='yes'){
                header('location:logout.php');
            }
        }
    }

}