<?php
class Image {
    private static $_instance;

    public static function instance() {
        if (!isset(self::$_instance)){
            return self::$_instance = new Image;
        }
        return self::$_instance;
    }

    public function upload_profile($file_upload_name) {
        global $errors;
        $name = $_FILES['profile']['name'];
        $size = $_FILES['profile']['size'];
        $error = $_FILES['profile']['error'];
        $tmp_name = $_FILES['profile']['tmp_name'];
        $type = $_FILES['profile']['type'];

        if ($error !== 4) {
            if ($error !== 0) {
                $errors['profile'] = upload_errors($error);
            } else {
                $allowed = array('image/jpeg', 'image/jpg', 'image/png');
                if (!in_array($type, $allowed)) {
                    $errors['profile'] = 'Your profile image must be a .jpg, .jpeg or .png format';
                } else {
                    if ($size > 5242880) {
                        $errors['profile'] = 'Your profile image must not be more than 5MB';
                    } else {
                        if (!is_uploaded_file($tmp_name)) {
                            $errors['profile'] = 'This action is forbidden';
                        } else {
                            $ext = explode('.', $name);
                            $ext = end($ext);
                            $filename = $file_upload_name.'.'.$ext;

                            if (strpos($_SERVER['PHP_SELF'], 'admin') === false) {
                                $destination = 'profile/'.$filename;
                            } elseif (strpos($_SERVER['PHP_SELF'], 'admin') !== false) {
                                $destination = '../profile/'.$filename;
                            }
                            
                            if (!move_uploaded_file($tmp_name, $destination)) {
                                $errors['profile'] = 'An error occurred while uploading your picture. Please try again later';
                            } else {
                                return $filename;
                            }
                        }
                    }
                }
            }
        }
    }

    public function display_profile() {
        global $user;

        if ($user->profile_image === null) {
            switch($user->sex) {
                case 'male':
                    return 'profile/boy.png';
                break;
                case 'female':
                    return 'profile/girl.png';
                break;
                default:
                    return '';
            }
        
        }

        return $user->profile_image;
    }

}