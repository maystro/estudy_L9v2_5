<?php

namespace App\Components;

class Element
{
    function __construct()
    {
    }


    function create($elementType,$properties)
    {
        $id = $properties['id'] ?? '';
        $value = $properties['value'] ?? '';
        $class = $properties['class'] ?? '';
        $extra_data = $properties['extra_data'] ?? '';
        $placeholder = $properties['placeholder'] ?? '';


        switch ($elementType)
        {
            case 'text_input':{
                $input = "<input type='text'
                class='$class'  id='$id'
                name='$id' value='" . $value . "' placeholder='$placeholder' $extra_data />";
            };break;

            case 'file':{
                $input = "<div class='ui action input $class' >";
                $input .= "<input type='text' placeholder='$placeholder' value='$value' id='fi_$id' $extra_data>";
                $input .= "<label for='$id' class='ui blue button icon' id='$id" . "_browse'>";
                $input .= "<i class='frm_folder outline icon'></i></label>";
                $input .= "<label for='$id' class='ui teal button icon' id='$id" . "_upload'>";
                $input .= "<i class='attach icon'></i>";
                $input .= "<input type='file' id='$id' name='$id' class='fileinput'>"; //must have input file style in css
                $input .= "</label></div>";
            };break;
        }
        echo $input;
    }
}

?>
