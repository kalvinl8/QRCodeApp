<?php

class Admin_Form_Fields_Helper
{


    public static function input($data, $arr = array())
    {


        $data = set_atts(
            array(
                "place_holder" => "",
                "name" => "",
                "value" => "",
                "class" => "",
                "id" => "",
                "data-att" => "",
                "style" => "",
                "type" => "text",
                "autofocus" => "no",
                "autocomplete" => "no",

            ), $data);

        $parsley = '';
        foreach ($arr as $key => $value) {
            $parsley .= $key . '="' . $value . '" ';
        }

        return '<input ' . $parsley . ' type="' . $data["type"] . '" placeholder="' . $data["place_holder"] . '"  name="' . $data["name"] . '" value="' . $data["value"] . '" class="' . $data["class"] . '" id="' . $data["id"] . '"  ' . $data["data-att"] . ' style="' . $data["style"] . '" autofocus="' . $data["autofocus"] . '" autocomplete="' . $data["autocomplete"] . '" >';
    }

    public static function successMessage($message, $start = 'Success!')
    {

        $html = '<div class="alert alert-success" role="alert">
                        <strong>' . $start . '</strong> ' . $message . '</a>
                    </div>';
        return $html;
    }


    public static function textarea($data)
    {

        $data = set_atts(
            array(
                "place_holder" => "",
                "name" => "",
                "value" => "",
                "class" => "",
                "id" => "",
                "data-att" => "",
                "style" => "",

                "required" => false
            ), $data);


        return '<textarea required="yes"   placeholder="' . $data["place_holder"] . '"  name="' . $data["name"] . '"  class="' . $data["class"] . '" id="' . $data["id"] . '"  style="' . $data["style"] . '"   ' . $data["data-att"] . '  required="' . $data["required"] . '">' . $data["value"] . '</textarea>';
    }


    public static function select($data, $arr = array())
    {

        $parsley = '';
        foreach ($arr as $key => $value) {
            $parsley .= $key . '="' . $value . '" ';
        }

        $data = set_atts(
            array(

                "name" => "",
                "selected" => "",
                "class" => "",
                "id" => "",
                "data-att" => "",
                "style" => "",
                "type" => "text",
                "required" => false,
                "options" => array()
            ), $data);

        $html = '<select ' . $parsley . '  name="' . $data["name"] . '"  class="' . $data["class"] . '" id="' . $data["id"] . '"  ' . $data["data-att"] . ' style="' . $data["style"] . '"  required="' . $data["required"] . '" >';

        foreach ($data["options"] as $key => $value) {
            $selected = ($data["selected"] != "" && $data["selected"] == $key) ? "selected='true'" : false;
            $html .= "<option  value='$key'  $selected >$value</option>";
        }

        $html .= "</select>";
        return $html;
    }


    public static function input_repater($data)
    {
        $html = "";
        $data = set_atts(
            array(
                "place_holder" => "",
                "name" => "",

                "class" => "",
                "id" => "",
                "data-att" => "",
                "style" => "",
                "type" => "text",
                "required" => false,
                "saved_data" => array()
            ), $data);


        // =====================  Saved =========================
        $saved_repaters = "";
        $first_data = "";
        if (isset($data["saved_data"][0])) {
            $first_data = $data["saved_data"][0];
            unset($data["saved_data"][0]);

            foreach ($data["saved_data"] as $key => $value) {
                $data["value"] = $value;
                $saved_repaters .= "<p>" . self::input($data) . "<a href='javascript:void(0)' class='repater_remove'>Remove</a></p>";
            }

        }

        //=======================================================

        $rand_id = rand(1000, 10000000000000);
        $data["id"] = "";
        $data["class"] .= " repater_input";
        $data["value"] = $first_data;
        $html .= "<div class='repater_main_div'>";
        $html .= "<span class='repater_input_first'>" . self::input($data) . "</span>";
        $html .= self::input(array(
            "type" => "button",
            "value" => "Add another",
            "id" => "repater_button_" . $rand_id,
            "class" => "input_repater_button"
        ));
        $html .= "<div class='repater_div' id='repater_div_$rand_id'>$saved_repaters</div>";
        $html .= "<div>";

        return $html;


    }


}

