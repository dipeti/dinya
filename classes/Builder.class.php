<?php

class Builder
{
    /**
     * @param array $replaces
     * @param string $html
     * @return mixed
     */
    public static function buildHTML($replaces, $html){
        foreach ($replaces as $key => $replace) {
            $html = Replace::html($key, $replace, $html);
        }
        return $html;
    }
}