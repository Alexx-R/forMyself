<?php

namespace sc\app;

trait MyFunc {
    public function str_replace_once($search, $replace, $text) {
       $pos = strpos($text, $search);
       return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
    }
}