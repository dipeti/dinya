<?php

class Builder
{
    public static function buildHTML($replaces, $html){
        foreach ($replaces as $key => $replace) {
            $html = Replace::html($key, $replace, $html);
        }
        return $html;
    }
}