<?php
require_once 'Session.php';

class Auth {
    public static function check() {
        Session::init();
        return Session::get('user_id') !== false;
    }

    public static function user() {
        if (self::check()) {
            return [
                'user_id' => Session::get('user_id'),
                'student_num' => Session::get('student_num'),
                'name' => Session::get('name'),
                'role' => Session::get('role')
            ];
        }
        return null;
    }

    public static function login($user) {
        Session::set('user_id', $user->user_id);
        Session::set('student_num', $user->student_num);
        Session::set('name', $user->name);
        Session::set('role', $user->role);
    }

    public static function logout() {
        Session::destroy();
        header('Location: ' . URLROOT . '/login');
        exit();
    }
}