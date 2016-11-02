<?php

class Redirect
{
    public static function to($to){
        header("Location: ".$to);
        die();
    }
}