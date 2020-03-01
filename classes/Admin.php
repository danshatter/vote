<?php
class Admin {
    private static $_instance;

    public static function instance() {
        if (!isset(self::$_instance)){
            return self::$_instance = new Admin;
        }
        return self::$_instance;
    }

    public function add_party() {
        global $errors;
        $party = check_null($_POST['party']);
        if ($party === null) {
            $errors['add_party'] = 'Please fill in the party name';
        } else {
            $party = strtoupper($party);
            $parties = DB::instance()->select_by_sql("SELECT * FROM `parties` WHERE `name` = ?", array($party));
            if (count($parties) !== 0) {
                $errors['add_party'] = 'This party is already saved';
            } else {
                if (!DB::instance()->insert('parties', array('name'), array($party))) {
                    $errors['add_party'] = 'An error occurred. Please try again later';
                } else {
                    $_SESSION['add_party_success'] = 'yea';
                    Redirect::to($_SERVER['PHP_SELF']);
                }
            }
        }
    }

    public function add_candidate() {
        global $errors;
        global $positions;

        $required = array('name', 'party', 'position');
        foreach ($_POST as $key => $value) {
            if (check_null($value) === null && in_array($key, $required)) {
                $errors[$key] = 'The '.$key.' field is required';
            }
        }

        if (count($errors) === 0) {
            $name = trim($_POST['name']);
            $party = trim($_POST['party']);
            $position = trim($_POST['position']);

            $check_party = DB::instance()->select_by_sql("SELECT `name` FROM `parties` WHERE `id` = ?", array($party));
            if (count($check_party) !== 1) {
                $errors['party'] = 'This party does not exist';
            }

            if (!in_array($position, $positions)) {
                $errors['position'] = 'You selected an invalid position';
            }

            if (count($errors) === 0) {
                $already = DB::instance()->select_by_sql("SELECT `name` FROM `{$position}` WHERE `party_id` = ?", array($party));
                if (count($already) !== 0) {
                    $already = array_shift($already);
                    $errors['position'] = 'This party is already represented by '.ready($already->name);
                } else {
                    if (!DB::instance()->insert($position, array('party_id', 'name'), array($party, $name))) {
                        $errors['database'] = 'An error occurred. Please try again later';
                    } else {
                        $_SESSION['add_candidate_complete'] = 'yea';
                        Redirect::to($_SERVER['PHP_SELF']);
                    }
                }
            }
        }
    }

    public function show($position) {
        global $errors;
        global $positions;

        $pos = check_null($position);
        if ($pos === null) {
            $errors['position'] = 'Please select a position';
        }

        if (count($errors) === 0) {
            if (!in_array($pos, $positions)) {
                $errors['position'] = 'You selected an invalid position';
            }

            if (count($errors) === 0) {
                $_SESSION['admin_position'] = $pos;
                $table = DB::instance()->select_by_sql("SELECT * FROM `{$pos}`", array());
                return $table;
            }
        }
    }

    public function delete_candidate() {
        global $errors;
        
        $id = check_null($_POST['id']);
        if ($id === null) {
            $errors['id'] = 'A candidate was not selected';
        }

        if (count($errors) === 0) {
            if (isset($_SESSION['admin_position'])) {
                $existence = DB::instance()->select_by_sql("SELECT `name` FROM {$_SESSION['admin_position']} WHERE `id` = ?", array());
                if (count($existence) === 1) {
                    if (!DB::instance()->delete($_SESSION['admin_position'], 'id', $id)) {
                        $errors['database'] = 'An error occurred. Please try again later';
                    } else {
                        $_SESSION['delete_candidate_complete'] = 'yea';
                        Redirect::to($_SERVER['PHP_SELF']);
                    }
                }
            }
        }
    }

    public function delete_party() {
        global $errors;
        global $positions;

        $id = check_null($_POST['id']);
        if ($id === null) {
            $errors['id'] = 'A party was not selected';
        }

        if (count($errors) === 0) {
            $existence = DB::instance()->select_by_sql("SELECT `name` FROM `parties` WHERE `id` = ?", array($id));
            if (count($existence) === 1) {
                $errors['database'] = array();
                if (!DB::instance()->delete('parties', 'id', $id)) {
                    $errors['database'][] = 'An error occurred. Please try again later';
                } else {
                    foreach ($positions as $position) {
                        $existence2 = DB::instance()->select_by_sql("SELECT `name` FROM `{$position}` WHERE `party_id` = ?", array($id));
                        if (count($existence2) === 1) {
                            if (!DB::instance()->delete($position, 'party_id', $id)) {
                                $errors['database'][] = 'An internal error occurred while trying to delete the party\'s candidates from the '.$position.' table. Please contact your administrator';
                            }
                        }
                    }

                    if (count($errors['database']) === 0) {
                        unset($errors['database']);
                    }

                    if (count($errors) === 0) {
                        $_SESSION['delete_party_complete'] = 'yea';
                        Redirect::to($_SERVER['PHP_SELF']);
                    }
                }
            }
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
                                if (!DB::instance()->insert('users', array('first_name', 'middle_name', 'last_name', 'email', 'date_of_birth', 'sex', 'profile_image', 'password', 'role'), array($first_name, $middle_name, $last_name, $email, $date_of_birth, $sex, $profile_image, $hashed_password, 2))) {
                                    $errors['database'] = 'An internal error occurred while signing you up. Please try again later';
                                    return false;
                                } else {
                                    $_SESSION['register_admin_success'] = 'success';
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