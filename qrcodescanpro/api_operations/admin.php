<?php

class admin
{

    function __construct()
    {
        $path_info = parse_path();
        if (isset($path_info["call_parts"][3]) && $path_info["call_parts"][3] === "json")
            header('Content-Type: application/json');
    }

    function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            global $db;
            $email = $_POST['email'];
            $password = $_POST['password'];

            $check_user = $db->users
                ->count()
                ->where("email = :email and password = :password", [':email' => $email, ':password' => md5($password)])
                ->run();

            if ($check_user > 0) { // user found
                $user = $db->users
                    ->select()
                    ->one()
                    ->where("email = :email and password = :password", [':email' => $email, ':password' => md5($password)])
                    ->run();

                $user_id = $user->id;
                $user_name = $user->name;
                $role = $user->role;


                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;

                echo json_encode(array("status" => "success", "message" => "user found", "user_id" => $user_id, 'name' => $user_name, 'role' => $role));
            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "invalid username or password"));
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
        }
    }

    function logout()
    {
        $this->clear_session();
        header("Location: " . BASE_URL . 'login');
    }

    function clear_session()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
    }

    function is_login()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            echo json_encode(array("status" => "failure", "message" => "user not logged in"));
            die();
        }
    }

    function add_user()
    {
        if (is_admin_login()) {
            if (
                isset($_POST['name'])
                && isset($_POST['email'])
                && isset($_POST['designation'])
                && isset($_POST['password'])
                && isset($_POST['role'])
            ) {

                global $db;

                $user_name = strtolower($_POST['name']);
                $user_role = $_POST['role'];
                $designation = $_POST['designation'];
                $user_email = $_POST['email'];
                $user_pass = $_POST['password'];

                $check_user = $db->users
                    ->count()
                    ->by("email", $user_email)
                    ->run();

                if ($check_user <= 0) {
                    //Create the new user
                    $user = $db->users->create([
                        'name' => $user_name,
                        'email' => $user_email,
                        'password' => md5($user_pass),
                        'role' => $user_role,
                        'designation' => $designation,
                        'created' => new DateTime('now'),
                        'status' => 'off duty',
                        'total_hours_worked' => '00 hours 00 minutes 00 seconds'
                    ]);
                    //Save the data
                    $user->save();
                    set_flash_message("handler", "User has been added successfully.", "success");
                    header("Location: " . BASE_URL . 'user-listing');
                } else {
                    set_flash_message("handler", "Email already exist.", "error");
                    header("Location: " . BASE_URL . 'add-user');
                }
            } else {
                set_flash_message("handler", " Something is missing.", "error");
                header("Location: " . BASE_URL . 'add-user');
            }

        } else {
            set_flash_message("handler", "Admin not logged in.", "error");
            header("Location: " . BASE_URL . 'add-user');
        }

    }

    function update_user()
    {
        if (is_admin_login()) {
            if (
                isset($_POST['name'])
                && isset($_POST['email'])
                && isset($_POST['designation'])
                && isset($_POST['role'])
                && isset($_POST['password'])
                && isset($_POST['id'])
            ) {
                global $db;

                $id = $_POST['id'];
                $user_email = $_POST['email'];
                $user_name = strtolower($_POST['name']);
                $designation = $_POST['designation'];
                $user_role = $_POST['role'];
                $user_pass = $_POST['password'];

                if (isset($db->users[$id])) {
                    $user = $db->users[$id];

                    $user->email = $user_email;
                    $user->name = strtolower($user_name);
                    $user->role = $user_role;
                    $user->designation = $designation;
                    if ($user_pass != '') {
                        $user->password = md5($user_pass);
                    }

                    $user->save();

                    set_flash_message("handler", "User has been updated successfully.", "success");
                    header("Location: " . BASE_URL . 'user-listing');
                } else {
                    set_flash_message("handler", "User does not exist.", "error");
                    header("Location: " . BASE_URL . 'edit-user?id=' . $id);
                }
            } else {
                set_flash_message("handler", " omething is missing.", "error");
                header("Location: " . BASE_URL . 'edit-user?id=' . $_GET['id']);
            }
        } else {
            set_flash_message("handler", "Admin is not logged in.", "error");
            header("Location: " . BASE_URL . 'user-listing');
        }
    }

    function delete_user()
    {
        if (is_admin_login()) {
            if (isset($_GET['id'])) {
                global $db;
                $id = $_GET['id'];
                if (isset($db->users[$id])) { //Check if a row exists
                    unset($db->users[$id]); //Delete a user

                    set_flash_message("handler", "User has been deleted successfully.", "success");
                }
            }
            header("Location: " . BASE_URL . 'user-listing');
        } else
            header("Location: " . BASE_URL . 'login');
    }

    function add_site()
    {
        if (is_admin_login()) {
            if (
                isset($_POST['name'])
                && isset($_POST['location_string'])
            ) {

                global $db;

                $name = strtolower($_POST['name']);
                $location = $_POST['location_string'];
                $user_id = $_SESSION['user_id'];

                //Create the new user
                $loc = $db->site->create([
                    'name' => $name,
                    'users_id' => $user_id,
                    'location' => $location,
                    'created' => new DateTime('now')
                ]);
                //Save the data
                $loc->save();
                set_flash_message("handler", "Site has been added successfully.", "success");
                header("Location: " . BASE_URL . 'site-listing');

            } else {
                set_flash_message("handler", " Something is missing.", "error");
                header("Location: " . BASE_URL . 'add-site');
            }

        } else {
            set_flash_message("handler", "Admin not logged in.", "error");
            header("Location: " . BASE_URL . 'add-site');
        }

    }

    function update_site()
    {
        if (is_admin_login()) {
            if (
                isset($_POST['name'])
                && isset($_POST['location_string'])
                && isset($_POST['id'])
            ) {
                global $db;

                $id = $_POST['id'];
                $location = $_POST['location_string'];
                $name = strtolower($_POST['name']);

                if (isset($db->site[$id])) {
                    $loc = $db->site[$id];

                    $loc->name = $name;
                    if ($location != '') {
                        $loc->location = $location;
                    }

                    $loc->save();

                    set_flash_message("handler", "Site has been updated successfully.", "success");
                    header("Location: " . BASE_URL . 'site-listing');
                } else {
                    set_flash_message("handler", "Site does not exist.", "error");
                    header("Location: " . BASE_URL . 'edit-site?id=' . $id);
                }
            } else {
                set_flash_message("handler", " Something is missing.", "error");
                header("Location: " . BASE_URL . 'edit-site?id=' . $_GET['id']);
            }
        } else {
            set_flash_message("handler", "Admin is not logged in.", "error");
            header("Location: " . BASE_URL . 'site-listing');
        }
    }

    function delete_site()
    {
        if (is_admin_login()) {
            if (isset($_GET['id'])) {
                global $db;
                $id = $_GET['id'];
                if (isset($db->site[$id])) { //Check if a row exists
                    unset($db->site[$id]); //Delete a site

                    set_flash_message("handler", "Site has been deleted successfully.", "success");
                }
            }
            header("Location: " . BASE_URL . 'site-listing');
        } else
            header("Location: " . BASE_URL . 'login');
    }

}

$admin = new admin();