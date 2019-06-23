<?php
/**
 * Created by PhpStorm.
 * User: Radiate
 * Date: 6/17/2019
 * Time: 11:27 AM
 */

class Session
{

    public static function start(){

        if (empty($_SESSION)  && !isset($_SESSION)){
            session_start();
        }
    }

    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function flush($key,$value){
        $_SESSION[$key] = $value;
        $_SESSION['created'] = time();
    }

    public static function get($key,$secondKey=false){
        if ($secondKey==true){
            if (isset($_SESSION[$key][$secondKey])){
                return $_SESSION[$key][$secondKey];
            }
        }else{
            if (isset($_SESSION[$key])){
                return $_SESSION[$key];
            }
        }
        return false;
    }

    public static function display(){
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    }

    public static function destroy(){
        if (!empty($_SESSION)  && isset($_SESSION)){
            session_unset();
            session_destroy();
        }
    }

    public static function destroyByKey($key){
        if (!empty($_SESSION[$key]) && isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

}