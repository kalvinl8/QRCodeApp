<?php


// ========  Match first array with send and add missing indexs with default values 
function set_atts($pairs, $atts)
{
    $atts = (array)$atts;
    $out = array();
    foreach ($pairs as $name => $default) {
        if (array_key_exists($name, $atts))
            $out[$name] = $atts[$name];
        else
            $out[$name] = $default;
    }


    return $out;
}

//===============  Global scripts ============

function global_head_back_end()
{
    global $script_add_to_head_back_end;
    echo $script_add_to_head_back_end;
}

//============= Set session flash message =======
function set_flash_message($tag, $msg, $type = "success")
{
    $_SESSION[$tag]["msg"] = $msg;
    $_SESSION[$tag]["type"] = $type;
}

//============= show session flash message =======
//============= show session flash message =======
function show_flash_message($tag)
{
    if (session_id() == '') {
        session_start();
    }
    $html = "";

    //var_dump($_SESSION);
    if (isset($_SESSION[$tag]) && isset($_SESSION[$tag]["msg"])) {

        $msg = $_SESSION[$tag]["msg"];
        $type = "alert-success";
        $head = "Well done!";
        switch ($_SESSION[$tag]["type"]) {
            case 'error':
                $type = "alert-danger";
                $head = "Error!";
                break;
        }
        $id = rand(1000, 10000000);
        $html = '<div id="alert-message-' . $id . '" class="alert ' . $type . '" role="alert"> <strong>' . $head . '</strong> ' . $msg . '</div><script>setTimeout(function(){$("#alert-message-' . $id . '").fadeOut("slow");},3000)</script>';
        $_SESSION[$tag] = array();

    }

    return $html;
}

//============= check user login and admin login =======
function is_admin_login()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function check_id_received($url)
{
    if (isset($_GET['id'])) {
        return $_GET['id'];
    } else {
        header("Location: " . BASE_URL . $url);
    }
}

function check_user_exist($id, $url)
{
    global $db;
    if (isset($db->users[$id])) {
        return $db->users
            ->select()
            ->one()
            ->by('id', $id)
            ->run();
    } else {
        header("Location: " . BASE_URL . $url);
    }
}

function check_site_exist($id, $url)
{
    global $db;
    if (isset($db->site[$id])) {
        return $db->site
            ->select()
            ->one()
            ->by('id', $id)
            ->run();
    } else {
        header("Location: " . BASE_URL . $url);
    }
}

function get_all_rows($table_name)
{
    global $db;
    $table_data = $db->$table_name
        ->select()
        ->run();
    return $table_data;
}

function get_all_rows_by($table_name, $column_name, $column_value)
{
    global $db;
    $table_data = $db->$table_name
        ->select()
        ->by($column_name, $column_value)
        ->run();
    return $table_data;
}

function get_counted_rows_by_id($table_name, $count, $column)
{
    global $db;
    $table_data = $db->$table_name
        ->select()
        ->orderBy(' ' . $column . ' DESC')
        ->limit($count)
        ->run();
    return $table_data;
}