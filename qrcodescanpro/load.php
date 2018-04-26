<?php

if (DEBUG) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

session_start();
include dirname(__FILE__) . "/libraries/db/vendor/autoload.php";

use SimpleCrud\SimpleCrud;

try {
    $pdo = new PDO("mysql:host=" . DATABASE_HOST . ";dbname=" . DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
} catch (PDOException $Exception) {
    die("<h1>can not connect to database</h1>");
}

global $db;
global $script_add_to_head_back_end;
global $AP_task_type_dropdown;
global $legiscope_filter;
global $merged_filters;

$db = new SimpleCrud($pdo);
date_default_timezone_set('America/Los_Angeles');

/////////////////////////////  Files Required //////////////////////////////
include dirname(__FILE__) . "/helpers/admin_form_fields_helper.php";
include dirname(__FILE__) . "/helpers/functions.php";
include dirname(__FILE__) . "/lang/french.php";

//===================== Task Types =====================
global $AP_Base_Task_Type,
       $AP_Filled_Task_Type,
       $Task_Library_Model,
       $AP_Normal_Validation_Task_Type,
       $AP_Single_choice_Task_Type,
       $AP_Multiple_choice_Task_Type,
       $AP_Multi_Element_Task_Type;
include dirname(__FILE__) . "/helpers/task_library/task_library_model.php";
include dirname(__FILE__) . "/helpers/task_library/base.php";
include dirname(__FILE__) . "/helpers/task_library/filled_task_type.php";
include dirname(__FILE__) . "/helpers/task_library/normal_validation_task_type.php";
include dirname(__FILE__) . "/helpers/task_library/single_choice_task_type.php";
include dirname(__FILE__) . "/helpers/task_library/multiple_choice_task_type.php";
include dirname(__FILE__) . "/helpers/task_library/multi_element_task_type.php";
$Task_Library_Model = new Task_Library_Model();
$AP_Base_Task_Type = new AP_Base_Task_Type();
$AP_Normal_Validation_Task_Type = new AP_Normal_Validation_Task_Type();
$AP_Filled_Task_Type = new AP_Filled_Task_Type();
$AP_Single_choice_Task_Type = new AP_Single_choice_Task_Type();
$AP_Multiple_choice_Task_Type = new AP_Multiple_choice_Task_Type();
$AP_Multi_Element_Task_Type = new AP_Multi_Element_Task_Type();


///======================  Load Global scripts and styles ===============

$script_add_to_head_back_end .= "<script src='" . BASE_URL . "back-end/assets/js/admin_field_helper.js'></script>";

$path_info = parse_path();

/////////////////////////////////////////////////////////////////////////

function parse_path()
{
    $path = array();
    if (isset($_SERVER['REQUEST_URI'])) {
        $request_path = explode('?', $_SERVER['REQUEST_URI']);

        $path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
        $path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
        $path['call'] = utf8_decode($path['call_utf8']);
        if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
            $path['call'] = '';
        }
        $path['call_parts'] = explode('/', $path['call']);

        //   $path['query_utf8'] = urldecode($request_path[1]);
        //  $path['query'] = utf8_decode(urldecode($request_path[1]));
        // $vars = explode('&', $path['query']);
        // foreach ($vars as $var) {
        //   $t = explode('=', $var);
        //   $path['query_vars'][$t[0]] = $t[1];
        // }
    }
    return $path;
}