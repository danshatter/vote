<?php
function Autoloader($class_name) {
    if (strpos($_SERVER['PHP_SELF'], 'admin') === false) {
        require_once 'classes/'.$class_name.'.php';
    } elseif (strpos($_SERVER['PHP_SELF'], 'admin') !== false) {
        require_once '../classes/'.$class_name.'.php';
    }
}

function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function output_errors($errors) {
    return implode('<br/>', $errors);
}

function login_redirect($page) {
    if (isset($_SESSION['id'])) {
        Redirect::to($page);
    }
}

function logged_in_only($page) {
    if (!isset($_SESSION['id'])) {
        $_SESSION['not_logged_in'] = 'not logged in';
        Redirect::to($page);
    }
}

function time_convert($time) {
    $stamp = strtotime($time);
    $timecheck = time() / 86400;
    $timecheck_int = floor($timecheck);
    $check = $stamp / 86400;
    $check_int = floor($check);    
    if ($check_int === $timecheck_int) {
        return 'Today at '.date('h:i a', $stamp);
    } elseif ($check_int === $timecheck_int - 1) {
        return 'Yesterday at '.date('h:i a', $stamp);
    } else {
        return date('l d F, Y', $stamp);
    }
}

function upload_errors($error) {
    if ($error === 0) {
        return 'No error';
    } elseif ($error === 1) {
        return 'Your profile picture is larger than upload_max_filesize';
    } elseif ($error === 2) {
        return 'Your picture is larger than MAX_FILE_SIZE';
    } elseif ($error === 3) {
        return 'Upload failed. Partial upload';
    } elseif ($error === 4) {
        return 'No file selected';
    } elseif ($error === 6) {
        return 'No temporary directory';
    } elseif ($error === 7) {
        return 'File can\'t write to disk';
    } else {
        return 'An error occurred in the file extension';
    }
}

function check_null($variable) {
    if (trim($variable) !== "") {
        return trim($variable);
    } else {
        return null;
    }
}

function captcha() {
    $text = '1234567890qwertyuioplkjhgfdsazxcvbnmABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captcha = str_shuffle($text);
    $captcha = substr($captcha, 33, 5);
    return $captcha;
}

function confirm() {
    if (!isset($_SESSION['confirm'])) {
        $_SESSION['confirm_fail'] = 'fail';
        Redirect::to(SITE_ROOT.'/vote.php');
    }
}

function complete() {
    global $user;
    if ($user->voted !== 1) {
        $_SESSION['not_complete'] = 'not complete';
        Redirect::to(SITE_ROOT.'/vote.php');
    }
}

function ready($string) {
    $a = strtolower($string);
    return ucwords($a);
}

function fake_403() {
    global $user;
    if ($user->role !== 2) {
        http_response_code(403);
        include_once '../403.php';
        die();
        exit();
    }
}

function fake_404($page = null) {
    if (pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) === '404.php') {
        return http_response_code(404);
    } elseif (pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) === $page) {
        http_response_code(404);
        include_once '404.php';
        die();
        exit();
    }
}

function has_voted() {
    global $user;
    if ($user->voted === 1) {
        echo '<p style="color: green; text-align: center;">You cannot view this page because you have already voted. Election results will be out soon<br/><br/><a style="color: purple; " href="'.SITE_ROOT.'/complete.php">Go to logout page</a></p>';
        
        if ($user->role === 2) {
        echo '<br/><p style="text-align: center;">Hey '.ready($user->first_name).', where you going? Get back to work. <a href="'.SITE_ROOT.'/admin/">Return to Admin area</a></p>';
        }
        
        die();
        exit();
    }
}

function background() {
    global $start_date;
    global $end_date;

    if ($start_date >= $end_date) {
        http_response_code(500);
        include_once '500.php';
        die();
        exit();
    } elseif ($_SERVER['REQUEST_TIME'] < $start_date) {
        http_response_code(503);
        include_once '503.php';
        die();
        exit();
    } elseif ( ($_SERVER['REQUEST_TIME'] > $end_date) && (pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) !== 'index.php') ) {
        Redirect::to(SITE_ROOT.'/');
    }
}

function display_result() {
    global $end_date;

    if ($_SERVER['REQUEST_TIME'] > $end_date) {
        include_once 'result.php';
        die();
        exit();
    }
}

function display_rem_time() {
    global $end_date;

    $rem = $end_date - $_SERVER['REQUEST_TIME'];
    
    if ($rem === 0) {
        return 'now';
    }

    $hours = (int)date('G', $rem) - 1;
    $minutes = (int)date('i', $rem);
    $seconds = (int)date('s', $rem);

    switch ($hours) {
        case 0:
            $hours = '';
        break;
        case 1:
            $hours = '1 hour';
        break;
        case -1:
            $hours = '23 hours';
        break;
        default:
            $hours = $hours.' hours';
    }

    switch ($minutes) {
        case 0:
            $minutes = '';
        break;
        case 1:
            $minutes = '1 minute';
        break;
        default:
            $minutes = $minutes.' minutes';
    }

    switch ($seconds) {
        case 0:
            $seconds = '';
        break;
        case 1:
            $seconds = '1 second';
        break;
        default:
            $seconds = $seconds.' seconds';
    }

    if ($rem < 86400) {
        $output = 'in '.trim($hours.' '.$minutes.' '.$seconds);
        $out = str_replace('  ', ' ', $output);
        return $out;

    } else {
        $days = floor($rem / 86400);
        $days = (int)$days;

        if ($days === 1) {
            $output = 'in '.trim('1 day '.$hours.' '.$minutes.' '.$seconds);
            $out = str_replace('  ', ' ', $output);
            return $out;

        } else {
            $output = 'in '.trim($days.' days '.$hours.' '.$minutes.' '.$seconds);
            $out = str_replace('  ', ' ', $output);
            return $out;
        }
    
    }

}