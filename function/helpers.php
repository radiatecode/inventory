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

    function abort($code){
        $status = [
            '404'=> [
                'title'=>'Sorry but we could not find this page',
                'details'=>'This page you are looking for does not exist Report this?'
            ],
            '403'=>[
                'title'=>'Access denied',
                'details'=>'Full authentication is required to access this resource. Report this?'
            ],
            '500'=>[
                'title'=>'Internal Server Error',
                'details'=>'We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing. Report this?'
            ]
        ];
        //var_dump($status['404']['title']);
        if (array_key_exists($code,$status)) {
            Session::set('error', [
                'code' => $code,
                'title' => $status[$code]['title'],
                'details' => $status[$code]['details']
            ]);
            echo Session::get('error','title');
        }
        header('location:error.php');
    }