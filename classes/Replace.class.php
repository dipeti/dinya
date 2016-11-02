<?php


class Replace
{
    public static function html($from, $to, $template){
        if(file_exists($to)){
            $to = file_get_contents($to);
        }
        return str_replace(self::brackets($from),$to, $template);
    }
    private static function brackets ($string){
        return strtoupper("[". $string . "]");
    }
}