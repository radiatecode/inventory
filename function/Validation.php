<?php
/**
 * Created by PhpStorm.
 * User: Radiate Noor
 * Date: 6/20/2019
 * Time: 1:39 PM
 */

class Validation
{
    private static $errors = [];

    public static function PostValidate($post,array $rules){
        foreach ($rules as $key=>$rule){
            $rule_break = explode('|',$rule);
            if (self::requiredRules($rule_break)){
                $element = $post[$key];
                if (is_array($element)){
                    foreach ($element as $i=>$val){
                        if (empty($element[$i])){
                            self::$errors[] = "All Of The ".ucwords($key)." Filed Is Required";
                        }
                    }
                }else{
                    if (empty($element)){
                        self::$errors[] = ucwords($key)." Filed Is Required";
                    }
                }
            }
            if (self::arrayRules($rule_break)){
                $element = $post[$key];
                if (count($element)>0){
                    foreach ($element as $i=>$val){
                        if (empty($element[$i])){
                            self::$errors[] = ucwords($key)." Field Must Have One Value ";
                        }
                    }
                }
            }
            if (self::is_ArrayRules($rule_break)){
                $element = $post[$key];
                if (!is_array($element)){
                    self::$errors[] = ucwords($key)." Field Is Not An Array";
                }
            }
            if (self::numberRules($rule_break)){
                $element = $post[$key];
                if (is_array($element)){
                    foreach ($element as $i=>$val){
                        if (!empty($element[$i]) && !is_numeric($element[$i])){
                            self::$errors[] = "All Of The ".ucwords($key)." Field Value Must Be Numeric";
                        }
                    }
                }else{
                    if (!empty($element) && !is_numeric($element)){
                        self::$errors[] = ucwords($key)." Field Value Must Be Numeric";
                    }
                }
            }

            if (self::requiredIf($rule_break)){
                $field = self::requiredIf($rule_break);
                $element = $post[$field];
                $key_elm = $post[$key];
                if (is_array($element)){
                    if (count($element)>0){
                        foreach ($element as $i=>$val){
                            if (!empty($element[$i])){
                                if (empty($key_elm[$i])){
                                    self::$errors[] = ucwords($key)." Field Must Have A Value";
                                }
                            }
                        }
                    }
                }else{
                    if (!empty($element)){
                        if (empty($key_elm)){
                            self::$errors[] = ucwords($key)." Field Must Have Value";
                        }
                    }
                }
            }

            if (self::minimum($rule_break)){
                $minimum = self::minimum($rule_break);
                $key_elm = array_filter($post[$key]);
                if (count($key_elm)>=$minimum){

                }else{
                    self::$errors[] = ucwords($key)." Field Must Have Minimum ".$minimum." Value";
                }
            }



        }
        return self::$errors;
    }

    private static function requiredRules($rules){
        return in_array('required',$rules);
    }

    private static function arrayRules($rules){
        return in_array('array',$rules);
    }

    private static function is_ArrayRules($rules){
        return in_array('is_array',$rules);
    }

    private static function numberRules($rules){
        return in_array('number',$rules);
    }

    private static function requiredIf($rules){
        foreach ($rules as $rule){
            if (strpos($rule, 'required_if') !== false) {
                $break = explode(':',$rule);
                $field = $break[1];
                return $field;
            }
        }
        return false;
    }
    private static function minimum($rules){
        foreach ($rules as $rule){
            if (strpos($rule, 'minimum') !== false){
                $break = explode(':',$rule);
                $min = (int)$break[1];
                return $min;
            }
        }
        return false;
    }


}