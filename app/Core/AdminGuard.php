<?php
require_once 'Auth.php';
require_once 'Flash.php';

class AdminGuard {
    public static function protect() {
        if (!Auth::check()) {
            Flash::set('auth_error', 'Please log in to access this page.', 'danger');
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }

        $user = Auth::user();
        if ($user['role'] !== 'Admin') {
            Flash::set('auth_error', 'Access Denied: You do not have admin privileges.', 'danger');
            header('Location: ' . URLROOT . '/post/index');
            exit();
        }
    }
}