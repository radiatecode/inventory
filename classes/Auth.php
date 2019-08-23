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
            $this->password = md5($pass);
            $this->login();
        }
    }

    private function login(){
        // query execute
        $result = $this->_db->select(['users.*'])
            ->table('users')
            ->where('email','=',$this->email)
            ->get();
        // count database row
        $row = $this->_db->numRows($result);
        // mysqli_fetch_assoc or mysqli_fetch_array for data
        // retrieve from mysqli_query result
        foreach ($result as $data){
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
                    $this->_db->update('users',[
                        'attempt'=>$this->attempt,
                        'timestamp'=>$this->timestamp
                    ])->where('email','=',$this->email)->get();
                    if($this->attempt<4){
                        $this->messages[] = "PASSWORD NOT MATCH, TRY AGAIN";
                    }else{
                        $this->messages[] = "YOU ARE BLOCKED";
                    }
                }else{
                    if ($this->attempt >=4 && $this->timestamp > time()){
                        $this->messages[] = "YOU ARE STILL BLOCKED, TRY TO LOGIN AFTER 1 MINUTE";
                    }else{
                        $this->_db->update('users',[
                            'attempt'=>0,
                            'timestamp'=>0
                        ])->where('email','=',$this->email)->get();
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

    public function allUsers(){
        $auth_id = Session::get('user','id');
        if ($auth_id) {
            $users = $this->_db->select(['users.*'])
                ->table('users')
                ->whereNotIn('id', [$auth_id])
                ->get();
            return $this->_db->fetchAll($users);
        }
        return [];
    }

}