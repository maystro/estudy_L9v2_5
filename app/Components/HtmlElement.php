<?php

namespace App\Components;

class HtmlElement
{
    static function renderBadge($class, $text): string
    {
        return "<span class='badge bg-$class'>$text</span>";
//        return <<<'blade'
//                <span class="badge bg-$class">$text</span>
//        blade;

    }
}
