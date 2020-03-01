<?php
class User {
    private static $_instance;

    public static function instance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new User;
        }
        return self::$_instance;
    }

    public function login_validate() {
        global $errors;

        $email = check_null($_POST['email']);
        $password = $_POST['password'];

        if ($email === null || $password === "") {
            $errors[] = '<p class="error center">Please fill in your email and password</p>';
            echo output_errors($errors);
        } else {
            $stmt = DB::instance()->select_by_sql("SELECT `id`, `role`, `password` FROM `users` WHERE `email` = ?", array($email));
            if (count($stmt) !== 1) {
                $errors[] = '<p class="error center">This user does not exist</p>';
                echo output_errors($errors);
            } else {
                $result = array_shift($stmt);
                $verify = password_verify($password, $result->password);
                if ($verify === false) {
                    $errors[] = '<p class="error center">Invalid email and password combination</p>';
                    echo output_errors($errors);
                } elseif ($verify === true) {
                    $_SESSION['id'] = $result->id;
                    
                    if ($result->role === 1) {
                        $_SESSION['success'] = 'success';
                        Redirect::to(SITE_ROOT.'/vote.php');
                    } elseif ($result->role === 2) {
                        $_SESSION['admin_success'] = 'success';
                        Redirect::to(SITE_ROOT.'/admin/');
                    }
                
                }
            }
        }
    }

    public function user_data($id) {
        global $errors;
        $result = DB::instance()->select_by_sql("SELECT * FROM `users` WHERE `id` = ?", array($id));
        if (count($result) === 0) {
            $errors[] = '<i>No User with this ID</i>';
            echo output_errors($errors);
            return false;
        } else {
            return array_shift($result);
        }
    }

    public function logout() {
        if (isset($_SESSION['id'])) {
            session_unset();
            session_destroy();
            Redirect::to(SITE_ROOT.'/');
        } else {
            Redirect::to(SITE_ROOT.'/');
        }
    }

    public function create_user() {
        global $errors;
        $first_name     = trim($_POST['first_name']);
        $middle_name    = check_null($_POST['middle_name']);
        $last_name      = trim($_POST['last_name']);
        $email          = trim($_POST['email']);
        $date_of_birth  = trim($_POST['date_of_birth']);
        $sex            = trim($_POST['sex']);
        $profile        = $_FILES['profile'];
        $password       = $_POST['password'];
        $password_again = $_POST['password_again'];

        $required = array('first_name', 'last_name', 'email', 'date_of_birth', 'sex', 'profile', 'password', 'password_again');
        foreach($_POST as $key => $value) {
            if ($value === "" && in_array($key, $required)) {
                $new = str_replace('_', ' ', $key);
                $errors[$key] = 'The '.$new.' is required';
            }
        }

        if (count($errors) === 0) {
            $stmt = DB::instance()->select_by_sql("SELECT * FROM `users` WHERE `email` = ?", array($email));
            if (count($stmt) === 1) {
                $errors['email'] = 'This email is already in use';
            } else {
                $focus = array('first_name', 'middle_name', 'last_name', 'sex');
                foreach ($_POST as $key => $value) {
                    if (!preg_match("/^[a-zA-Z]*$/", $value) && in_array($key, $focus)) {
                        $new = str_replace('_', ' ', $key);
                        $errors[$key] = 'Your '.$new.' must contain only alphabets and must not contain white spaces';
                    }
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'This email address is invalid';
                }

                $sexes = array('male', 'female');
                if (!in_array($sex, $sexes)) {
                    $errors['sex'] = 'This is not a valid value';
                }

                if (count($errors) === 0) {
                    $errors['password'] = array();
                    if (!preg_match("/[a-z]/", $password)) {
                        $errors['password'][] = 'Your Password must contain a small alphabet';
                    }
        
                    if (!preg_match("/[A-Z]/", $password)) {
                        $errors['password'][] = 'Your Password must contain a capital alphabet';
                    }
        
                    if (!preg_match("/[0-9]/", $password)) {
                        $errors['password'][] = 'Your Password must contain a number';
                    }

                    if (strlen($password) < 5 || strlen($password) > 50) {
                        $errors['password'][] = 'Your Password cannot be less than 5 characters and cannot be more than 50 characters';
                    }
        
                    if (!preg_match("/[@#$%&?*_!]/", $password)) {
                        $errors['password'][] = 'Your Password must contain a special character among @, #, $, %, &, ?, *, _, and !';
                    }

                    if (count($errors['password']) === 0) {
                        unset($errors['password']);
                        if ($password !== $password_again) {
                            $errors['password'] = 'Your passwords do not match';
                        } else {
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $profile_image = check_null($profile['name']);
                            if ($profile_image !== null) {
                                $ext = explode('.', $profile_image);
                                $ext = end($ext);
                                $profile_image = 'profile/'.$email.'.'.$ext;
                                Image::instance()->upload_profile($email);
                            }

                            if (count($errors) === 0) {
                                if (!DB::instance()->insert('users', array('first_name', 'middle_name', 'last_name', 'email', 'date_of_birth', 'sex', 'profile_image', 'password'), array($first_name, $middle_name, $last_name, $email, $date_of_birth, $sex, $profile_image, $hashed_password))) {
                                    $errors['database'] = 'An internal error occurred while signing you up. Please try again later';
                                    return false;
                                } else {
                                    $_SESSION['register_success'] = 'success';
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }     
    }

}