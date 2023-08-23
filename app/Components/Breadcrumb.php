<?php
namespace App\Components;

class Breadcrumb
{
    private $segments;

    function __construct()
    {
        $this->segments=array();
    }
    function add($segments)
    {
        foreach($segments as $segment)
        {
            array_push($this->segments,$segment);
        }
        
    }
    
    function get($segment)
    {
        return array_search($segment,$this->segments);
    }

    function render()
    {

        foreach($this->segments as $index=>$segment)
        {
            
                $title = $segment['title'];
                $href = $segment['href'];

                $html = "<div class='ui breadcrumb'>";
                $html .= "<a class='section' href='$href'>$title</a>";

                if($index<sizeof($this->segments)-1)
                {
                    $html .= "<i class='left chevron icon divider'></i>";
                }
               
                $html .= "</div>";
                echo ($html);
        }
    }
}
