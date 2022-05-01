<?php
include_once("functions.php");
if (isset($_POST['save'])) {
    global $update_profile_img;
    session_start();
    $email = $_SESSION['email'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $uploadOK = 1;
    $new_img = $_FILES['change_img'];
    $user_database = "../../UserData/UserAccounts/accounts.db";
    $data = retrieve_data($user_database);
    $img_database = "../../UserData/UploadImages/";
    $file = substr($email, 0, strpos($email, '@'));
    $file_path = $img_database . $file . '/' . '*';
    echo $file_path;
    $file_location = glob($file_path);
    for ($i = 0; $i <= count($data); $i++) {
        if (strtolower($email) === strtolower($data[$i]['email'])) {
            if (!empty($file_location)) {
                if (verify_update_img($new_img, $img_database)) {
                    foreach ($file_location as $file_img) {
                        if (unlink($file_img)) {
                            $uploadOK = 1;
                            break;
                        } else {
                            $uploadOK = 0;
                        }
                    }
                    if ($uploadOK) {
                        if (update_img_profile($new_img, $email, $fname, $lname, $img_database)) {
                            $update_profile_img = 'Profile picture is updated!';
                            header('Location: /FullStackWebApplication/design/profile_page.php');
                        }
                    } else {
                        $update_profile_img = 'Profile picture cannot be updated!';
                        header('Location: /FullStackWebApplication/design/profile_page.php');
                    }
                    break;
                } else {
                    $update_profile_img = $error_update_img;
                    header('Location: /FullStackWebApplication/design/profile_page.php');
                }
            } else {
                if (update_img_profile($new_img, $email, $fname, $lname, $img_database)) {
                    $update_profile_img = 'Profile picture is updated!';
                    header('Location: /FullStackWebApplication/design/profile_page.php');
                }
            }
        } else {
            continue;
        }
    }
}