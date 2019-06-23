<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 6/17/2019
 * Time: 11:00 AM
 */
require '../vendor/autoload.php';
class Auth
{
    private $password, $email,
        $db_pass, $active,
        $attempt =0, $timestamp =0,
        $_db,$messages=[],$user=[];

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();

    }

    public function credentials($email,$pass){
        if (isset($email) && isset($pass)){
            $this->email = $email;
            $this->password = $pass;
            $this->login();
        }
    }

    private function login(){
        // check user exist or not
        $SQL = "SELECT * FROM `users` WHERE email='$this->email'";
        // query execute
        $result = $this->_db->query($SQL);
        // count database row
        $row = $this->_db->numRows($result);
        // mysqli_fetch_assoc or mysqli_fetch_array for data
        // retrieve from mysqli_query result
        while ($data = $this->_db->fetchArray($result)){
            $this->db_pass= $data['password'];
            $this->active = $data['active'];
            $this->attempt = $data['attempt'];
            $this->timestamp = $data['timstamp'];

            $this->user['id']=$data['id'];
            $this->user['email']=$data['email'];
            $this->user['username']=$data['username'];
            $this->user['name']=$data['name'];
            $this->user['role']=$data['role'];
            $this->user['photo']=$data['photo'];
        }
        // check whether user exist or not
        if ($row==0){
            $this->messages[] = "USER NOT EXIST, PLEASE REGISTER";
        }else{
            // check user active or inactive
            if ($this->active==0){
                $this->messages[] = "Your are not active yet, try to contact with admin";
            }else{
                // check pass match or not, if not increment
                // attempt and times tamp for punishment
                if ($this->db_pass!=$this->password){
                    $this->attempt =  $this->attempt+1;
                    $this->timestamp = time()+50;
                    // update the attempt and timestamp for that specific user
                    $SQL = "UPDATE `users` SET attempt='$this->attempt',`timestamp`='$this->timestamp' WHERE email='$this->email'";
                    $this->_db->query($SQL);
                    if($this->attempt<4){
                        $this->messages[] = "PASSWORD NOT MATCH, TRY AGAIN";
                    }else{
                        $this->messages[] = "YOU ARE BLOCKED";
                    }
                }else{
                    if ($this->attempt >=4 && $this->timestamp > time()){
                        $this->messages[] = "YOU ARE STILL BLOCKED, TRY TO LOGIN AFTER 1 MINUTE";
                    }else{
                        $SQL = "UPDATE `users` SET attempt=0,`timestamp`=0 WHERE email='$this->email'";
                        $this->_db->query($SQL);
                        Session::set('user',$this->user);
                        header('location:index.php');
                    }
                }
            }
        }
    }

    public function logout(){
        Session::destroy();
        redirectIfNotAuthenticate();
    }

    public function getMessage(){
        return $this->messages;
    }


}