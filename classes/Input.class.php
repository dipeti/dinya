<?php

class Input
{
    public static function exists($key, $array_name = 'post'){
        switch($array_name){
            case 'post':
                return (!empty($_POST) && isset($_POST[$key])) ?  true : false ;
         case 'get':
             return (!empty($_GET) && isset($_GET[$key])) ?  true : false ;
            default: return false;
        }
    }
    public static function get($array_name = "post", $key, $default){
        switch($array_name){
            case 'post':
                return self::sanitize($_POST[$key]);
            case 'get':
                return self::sanitize($_GET[$key]);
            default: return $default;
        }
    }
    public static function getinputs($array_name = "post", array $inputs, $default){
        foreach ($inputs as $key => $input) {
            if(Input::exists($key)){
                $inputs[$key] = Input::get('post', $key, $default);

            }
        }
        return $inputs;
    }

    public static function sanitize($string){
        return htmlentities($string);
    }
}