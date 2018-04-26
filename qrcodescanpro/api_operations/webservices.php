<?php

class webservices
{

    function __construct()
    {
        //header('content-type: application/json; charset=utf-8');
        $path_info = parse_path();
        if (isset($path_info["call_parts"][3]) && $path_info["call_parts"][3] === "json")
            header('Content-Type: application/json; charset=utf-8');
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

                echo json_encode(array("status" => "success", "message" => "User is found", 'data' => $user));
                return;

            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "Invalid email or password"));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function get_profile_info()
    {
        if (isset($_GET['id'])) {
            global $db;
            $id = $_GET['id'];

            if (isset($db->users[$id])) { // user found
                $user = $db->users
                    ->select()
                    ->one()
                    ->by('id', $id)
                    ->run();

                echo json_encode(array("status" => "success", "message" => "User is found", 'data' => $user));
                return;

            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "This user is removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function register()
    {
        if (isset($_POST['name']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['phone']) &&
            isset($_POST['user_type'])
        ) {

            global $db;
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $user_type = $_POST['user_type'];

            $check_user = $db->users
                ->count()
                ->where("email = :email ", [':email' => $email])
                ->run();

            if ($check_user < 1) { // email is unique

                if ($user_type == 'user') {

                    $user = $db->users->create([
                        'name' => ucwords($name),
                        'email' => $email,
                        'password' => md5($password),
                        'phone' => $phone,
                        'user_type' => 'user',
                        'profile_pic' => BASE_URL . 'assets/img/default.png',
                        'createdAt' => new DateTime('now')
                    ]);
                    $user->save();

                    echo json_encode(array("status" => "success", "message" => "Registered successfully.", "data" => $user));
                    return;
                } else {

                    $c_name = $_POST['company_name'];
                    $c_address = $_POST['company_address'];
                    $c_website = $_POST['company_website'];

                    $user = $db->users->create([
                        'name' => ucwords($name),
                        'email' => $email,
                        'password' => md5($password),
                        'phone' => $phone,
                        'user_type' => 'buisness',
                        'profile_pic' => BASE_URL . 'assets/img/default.png',
                        'c_name' => ucwords($c_name),
                        'c_address' => $c_address,
                        'c_website' => $c_website,
                        'createdAt' => new DateTime('now')
                    ]);
                    $user->save();

                    echo json_encode(array("status" => "success", "message" => "Registered successfully.", "data" => $user));
                    return;
                }

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This email is already take."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function update_profile_info()
    {
        if (isset($_POST['id']) &&
            isset($_POST['name']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['phone']) &&
            isset($_POST['user_type'])
        ) {

            global $db;
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $user_type = $_POST['user_type'];

            if (isset($db->users[$id])) { // email is unique

                if ($user_type == 'user') {

                    $user = $db->users[$id];

                    $user->name = ucwords($name);
                    $user->email = $email;
                    $user->password = md5($password);
                    $user->phone = $phone;
                    $user->user_type = 'user';

                    $user->save();

                    echo json_encode(array("status" => "success", "message" => "User Updated successfully.", "data" => $user));
                    return;
                } else {

                    $c_name = $_POST['company_name'];
                    $c_address = $_POST['company_address'];
                    $c_website = $_POST['company_website'];

                    $user = $db->users[$id];

                    $user->name = ucwords($name);
                    $user->email = $email;
                    $user->password = md5($password);
                    $user->phone = $phone;
                    $user->user_type = 'buisness';

                    $user->c_name = ucwords($c_name);
                    $user->c_address = $c_address;
                    $user->c_website = $c_website;

                    $user->save();

                    echo json_encode(array("status" => "success", "message" => "User Updated successfully.", "data" => $user));
                    return;
                }

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This user does not exist."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function update_profile_picture()
    {
        if (isset($_POST['id']) &&
            isset($_POST['picture'])
        ) {

            global $db;
            $id = $_POST['id'];
            $picture = $_POST['picture'];

            if (isset($db->users[$id])) { // email is unique

                $path = $this->saveImage($picture);

                $user = $db->users[$id];
                $user->profile_pic = $path;
                $user->save();

                echo json_encode(array("status" => "success", "message" => $path));
                return;

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This user does not exist."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }


	function get_all_picture_posts_of_user()
    {
        if (isset($_GET['id'])) {
            global $db;
            $id = $_GET['id'];

            $check_user = $db->users
                ->count()
                ->by('id', $id)
                ->run();

            if ($check_user > 0) { // user found

                $posts = $db->picture_posts
                    ->select()
                    ->by('users_id',$id)
                    ->orderBy('createdAt DESC')
                    ->run();

                $posts->users;

                echo json_encode(array("status" => "success", "message" => "Posts found.", "data" => $posts));
                return;

            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "User does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "Parameters is missing"));
            return;
        }
    }

    function get_all_saved_cards()
    {
        if (isset($_GET['id'])) {
            global $db;
            $id = $_GET['id'];

            $check_user = $db->users
                ->count()
                ->by('id', $id)
                ->run();

            if ($check_user > 0) { // user found

                $cards = $db->qrcodes
                    ->select()
                    ->by('users_id', $id)
                    ->orderBy('createdAt DESC')
                    ->run();

                echo json_encode(array("status" => "success", "message" => "cards found.", "data" => $cards));
                return;

            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "User does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "Parameters is missing"));
            return;
        }
    }

    function add_new_card()
    {
        if (isset($_POST['id']) &&
            isset($_POST['data'])
        ) {

            global $db;
            $userId = $_POST['id'];

            $check_user = $db->users
                ->count()
                ->by('id', $userId)
                ->run();

            if ($check_user > 0) { // check user

                $data = $_POST['data'];

                $card = $db->qrcodes->create([
                    'users_id' => $userId,
                    'data' => $data,
                    'createdAt' => new DateTime('now')
                ]);
                $card->save();

                echo json_encode(array("status" => "success", "message" => "Card Saved Successfully."));
                return;

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This user does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function get_all_picture_posts()
    {
        if (isset($_GET['id'])) {
            global $db;
            $id = $_GET['id'];

            $check_user = $db->users
                ->count()
                ->by('id', $id)
                ->run();

            if ($check_user > 0) { // user found

                $posts = $db->picture_posts
                    ->select()
                    ->orderBy('createdAt DESC')
                    ->run();

                $posts->users;

                echo json_encode(array("status" => "success", "message" => "Posts found.", "data" => $posts));
                return;

            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "User does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "Parameters is missing"));
            return;
        }
    }

    function add_new_picture_post()
    {
        if (isset($_POST['user_id']) &&
            isset($_POST['picture']) &&
            isset($_POST['description'])
        ) {

            global $db;
            $userId = $_POST['user_id'];

            $check_user = $db->users
                ->count()
                ->by('id', $userId)
                ->run();

            if ($check_user > 0) { // check user

                $picture = $_POST['picture'];
                $description = $_POST['description'];

                $path = $this->saveImage($picture);

                $post = $db->picture_posts->create([
                    'users_id' => $userId,
                    'url' => $path,
                    'description' => $description,
                    'createdAt' => new DateTime('now')
                ]);
                $post->save();

                echo json_encode(array("status" => "success", "message" => "Posted successfully."));
                return;

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This user does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function saveImage($base64img)
    {
        define('UPLOAD_DIR', 'assets/img/');
        $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        $base64img = str_replace('data:image/png;base64,', '', $base64img);
        $data = base64_decode($base64img);
        $file = UPLOAD_DIR . time() . '.png';
        file_put_contents($file, $data);
        return BASE_URL . $file;
    }

    function delete_picture_post()
    {
        if (isset($_POST['post_id'])) {
            global $db;
            $postId = $_POST['post_id'];

            if (isset($db->picture_posts[$postId])) { //Check if a row exists
                unset($db->picture_posts[$postId]); //Delete a post
                echo json_encode(array("status" => "success", "message" => "Post removed successfully."));
                return;

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This post does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function delete_saved_card()
    {
        if (isset($_POST['card_id'])) {
            global $db;
            $postId = $_POST['card_id'];

            if (isset($db->qrcodes[$postId])) { //Check if a row exists
                unset($db->qrcodes[$postId]); //Delete a card
                echo json_encode(array("status" => "success", "message" => "Card removed successfully."));
                return;

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This buisness card does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function is_following()
    {
        if (isset($_GET['id']) && isset($_GET['following_id'])) {
            global $db;
            $id = $_GET['id'];
            $followingId = $_GET['following_id'];

            if (isset($db->users[$id]) && isset($db->users[$followingId])) { //Check if a row exists

                $check_user = $db->following
                    ->count()
                    ->where("users_id = :id AND follower_id = :followingId", [':id' => $id, ':followingId' => $followingId])
                    ->run();

                if ($check_user > 0) {
                    echo json_encode(array("status" => "success", "message" => "true"));
                    return;
                } else {
                    echo json_encode(array("status" => "success", "message" => "false"));
                    return;
                }

            } else { // user found
                echo json_encode(array("status" => "failure", "message" => "This user does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }

    function change_follow_unfollow()
    {
        if (isset($_GET['id']) && isset($_GET['following_id'])) {
            global $db;
            $id = $_GET['id'];
            $followingId = $_GET['following_id'];

            if (isset($db->users[$id]) && isset($db->users[$followingId])) { //Check if a row exists

                $check_user = $db->following
                    ->count()
                    ->where("users_id = :id AND follower_id = :followingId", [':id' => $id, ':followingId' => $followingId])
                    ->run();

                if ($check_user > 0) {

                    $get_user = $db->following
                        ->select()
                        ->one()
                        ->where("users_id = :id AND follower_id = :followingId", [':id' => $id, ':followingId' => $followingId])
                        ->run();
                    unset($db->following[$get_user->id]); //Delete entry

                } else {

                    $status_change = $db->following->create([
                        'users_id' => $id,
                        'follower_id' => $followingId,
                        'createdAt' => new DateTime('now')
                    ]);
                    $status_change->save();

                }

                echo json_encode(array("status" => "success", "message" => "User is followed or unfollowed successfully."));
                return;

            } else { // user not found
                echo json_encode(array("status" => "failure", "message" => "This user does not exist or removed."));
                return;
            }
        } else {
            echo json_encode(array("status" => "failure", "message" => "parameters is missing"));
            return;
        }
    }
}

$webservices = new webservices();