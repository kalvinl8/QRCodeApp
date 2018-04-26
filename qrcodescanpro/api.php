<?php


if ($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'PUT' && $_SERVER['REQUEST_METHOD'] != "DELETE") {
    header('Content-Type: application/json');
    die(json_encode(array("status" => "error", "message" => "request type not supported")));
}


$class = isset($path_info["call_parts"][1]) ? $path_info["call_parts"][1] : false;
$method = isset($path_info["call_parts"][2]) ? $path_info["call_parts"][2] : false;


if (!$class || !$method || !file_exists("api_operations/" . $class . ".php")) {
    header('Content-Type: application/json');


    die(json_encode(array("status" => "error", "message" => "invalid operation")));
} else {
    include "api_operations/" . $class . ".php";
    $method = trim($method, "/");
    if (!class_exists($class) || !method_exists(${$class}, $method)) {
        header('Content-Type: application/json');
        die(json_encode(array("status" => "error", "message" => "invalid operation")));
    }

}


${$class}->{$method}();