<?php

if ('HEAD' === $_SERVER['REQUEST_METHOD'])
    exit();


include dirname(__FILE__) . "/config.php";
include dirname(__FILE__) . "/load.php";

switch ($path_info["call_parts"][0]) {

    /***********************ADMIN REDIRECTION**************************************/

    case 'api';
        include dirname(__FILE__) . "/api.php";
        break;

    case "assets":
        if (file_exists(dirname(__FILE__) . $path_info["call"]))
            echo file_get_contents(dirname(__FILE__) . $path_info["call"]);
        break;

    default:
        include dirname(__FILE__) . "/404.php";
        break;
}



















