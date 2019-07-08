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
                if (empty($post[$key])){
                    self::$errors[] = ucwords($key)." Filed Is Required";
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
            if (self::numberRules($rule_break)){
                if (!empty($post[$key]) && !is_numeric($post[$key]) ){
                    self::$errors[] = ucwords($key)." Field Value Must Be Numeric";
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

        }
        return self::$errors;
    }

    private static function requiredRules($rules){
        return in_array('required',$rules);
    }

    private static function arrayRules($rules){
        return in_array('array',$rules);
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
            }else{
                return false;
            }
        }
    }


}