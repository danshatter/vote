<?php
class Voting {
    private static $_instance;

    public static function instance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new Voting;
        }
        return self::$_instance;
    }

    public function initialize() {
        $database = array();

        $database['presidents'] = DB::instance()->select_by_sql("SELECT * FROM `presidents`", array());
        $database['governors'] = DB::instance()->select_by_sql("SELECT * FROM `governors`", array());
        $database['house_of_representatives'] = DB::instance()->select_by_sql("SELECT * FROM `house_of_representatives`", array());
        $database['senators'] = DB::instance()->select_by_sql("SELECT * FROM `senators`", array());
        $database['state_assemblies'] = DB::instance()->select_by_sql("SELECT * FROM `state_assemblies`", array());
        $database['parties'] = DB::instance()->select_by_sql("SELECT * FROM `parties`", array());
        
        return $database;
    }

    public function get_party($id) {
        $party = DB::instance()->select_by_sql("SELECT `name` FROM `parties` WHERE `id` = ?", array($id));
        $party = array_shift($party);
        return $party->name;
    }

    public function get_candidate($table, $id) {
        $candidate = DB::instance()->select_by_sql("SELECT `name` FROM `{$table}` WHERE `id` = ?", array($id));
        $candidate = array_shift($candidate);
        return $candidate;
    }

    public function user_vote() {
        global $errors;
        global $user;
        global $positions;

        foreach($_POST as $key => $value) {
            if (check_null($value) === null && in_array($key, $positions)) {
                $$key = null;
                $errors[] = $key.' is empty';
            } elseif (check_null($value) !== null && in_array($key, $positions)) {
                $$key = trim($value);
            }
        }

        if (count($errors) >= 5) {
            echo '<p class="error">You must vote for at least one position</p>';
        } else {
            $captcha = check_null($_POST['captcha']);
            if ($captcha === null) {
                $errors['captcha'] = 'Please enter the captcha';
            } else {
                if (strtolower($captcha) !== strtolower($_SESSION['captcha'])) {
                    $errors['captcha'] = 'Invalid captcha';
                } else {
                    if ($user->voted !== 0) {
                        echo '<p class="success">You have already casted your vote</p>';
                    } else {
                        unset($_SESSION['captcha']);
                        
                        $_SESSION['confirm'] = 'confirm';

                        $_SESSION['choice'] = array();
                        $_SESSION['choice']['presidents'] = $presidents;
                        $_SESSION['choice']['governors'] = $governors;
                        $_SESSION['choice']['house_of_representatives'] = $house_of_representatives;
                        $_SESSION['choice']['senators'] = $senators;
                        $_SESSION['choice']['state_assemblies'] = $state_assemblies;

                        return true;
                    }
                }
            }
        }
    }

    public function unsure() {
        unset($_SESSION['confirm']);
        unset($_SESSION['choice']);
        Redirect::to(SITE_ROOT.'/vote.php');
    }

    public function cast_vote() {
        global $user;
        if (isset($_SESSION['confirm']) && isset($_SESSION['choice'])) {
            if ($user->voted !== 0) {
                echo '<p class="success">You have already casted your vote</p>';
            } else {
                if (!DB::instance()->insert('votes', array('user_id', 'president_vote', 'senate_vote', 'house_of_reps_vote', 'governor_vote', 'state_assembly_vote'), array($user->id, $_SESSION['choice']['presidents'], $_SESSION['choice']['senators'], $_SESSION['choice']['house_of_representatives'], $_SESSION['choice']['governors'], $_SESSION['choice']['state_assemblies']))) {
                    echo '<p class="error">We couldn\'t cast your vote at this time. Please try again later</p>';
                } else {
                    if (!DB::instance()->update('users', array('voted'), array(1), 'id', $user->id)) {
                        echo '<p class="error">An internal error occured. Be rest assured that you have already casted your vote</p>';
                    } else {
                        unset($_SESSION['confirm']);
                        unset($_SESSION['choice']);
                        Redirect::to(SITE_ROOT.'/complete.php');
                    }
                }
            }
        }
    }

    public function count($column, $id) {
        $count = DB::instance()->select_by_sql("SELECT COUNT(*) as `count` FROM `votes` WHERE {$column} = ?", array($id));
        $count = array_shift($count);
        return $count->count;
    }

    public function collate($column, $table) {
        $candidates = DB::instance()->select_by_sql("SELECT * FROM `{$table}`", array());
        $output = '';
        foreach ($candidates as $candidate) {
            $output .= '('.self::instance()->get_party($candidate->party_id).') '.$candidate->name.' : '.number_format(self::instance()->count($column, $candidate->id)).'<br/><br/>';
        }
        return $output;
    }

}