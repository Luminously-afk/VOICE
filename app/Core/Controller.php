<?php
class Controller {
    public function __construct() {
        $this->enforceActiveSessionCheck();
    }

    private function enforceActiveSessionCheck() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            if (file_exists('../app/Models/User.php')) {
                require_once '../app/Models/User.php';
                $userModel = new User();
                
                if (!$userModel->checkUserExistsById($_SESSION['user_id'])) {
                    unset($_SESSION['user_id']);
                    unset($_SESSION['user_name']);
                    unset($_SESSION['user_role']);
                    session_destroy();
                    
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    require_once '../app/Core/Flash.php';
                    Flash::set('auth_error', 'Your account has been deleted by the admin.');
                    
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'redirect' => true]);
                        exit();
                    }
                    
                    header('Location: ' . URLROOT . '/auth/login');
                    exit();
                }
            }
        }
    }

    public function model($model) {
        if (file_exists('../app/Models/' . $model . '.php')) {
            require_once '../app/Models/' . $model . '.php';
            return new $model();
        } else {
            die("Model '$model' does not exist.");
        }
    }

    public function view($view, $data = []) {
        if (file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die("View '$view' does not exist.");
        }
    }
}