<?php
class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        self::init();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    public static function destroy() {
        self::init();
        session_unset();
        session_destroy();
    }
}