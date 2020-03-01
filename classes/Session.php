<?php
class Session {
    private static $_instance;

    public static function instance() {
        if (!isset(self::$_instance)){
            return self::$_instance = new Session;
        }
        return self::$_instance;
    }

    public function flash($message, $class, $session) {
        $output = '<p class="'.$class.'">'.$message.'</p>';
        unset($_SESSION[$session]);
        return $output;
    }
}
