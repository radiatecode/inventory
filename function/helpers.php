<?php

    function messages($messages){
        if(count($messages)>0){
            echo "<div class='alert alert-danger' role='alert'><ul>";
            foreach ($messages as $message){
                echo "<li>$message</li>";
            }
            echo "</ul></div>";
        }
    }

    function session_message(){
        if (Session::get('created')){
            if (time()-Session::get('created') > 1){
              Session::destroyByKey('success');
              Session::destroyByKey('failed');
              Session::destroyByKey('created');
            }else{
                if(Session::get('success')){
                    echo "<div class='alert alert-success' role='alert'>".Session::get('success')."</div>";
                }else if(Session::get('failed')){
                    echo "<div class='alert alert-danger' role='alert'>".Session::get('failed')."</div>";
                }
            }
        }
    }

    function redirectIfNotAuthenticate(){
        Session::start();
        $email = Session::get('user','email');
        if (!$email){
            header('location:login.php');
        }
    }

    function redirectIfAuthenticate(){
        Session::start();
        $email = Session::get('user','email');
        if ($email){
            header('location:index.php');
        }
    }