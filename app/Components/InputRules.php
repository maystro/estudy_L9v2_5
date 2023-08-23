<?php

namespace App\Components;

use Illuminate\Support\Str;

class InputRules
{
    function __construct()
    {
    }
    static function translateCharacters($text)
    {
        //return str_replace(['ة','ي','أ','إ','آ'],['ه','ى','ا','ا','ا'],$text);
        return Str::of($text)->trim();
    }
}
