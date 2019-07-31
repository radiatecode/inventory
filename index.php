<?php
require_once './vendor/autoload.php';
Session::start();
$email = Session::get('user','email');
if (!$email){
    header('location:ui/login.php');
}else{
    header('location:ui/index.php');
}