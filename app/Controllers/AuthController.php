<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index() {
        $this->login();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $login_id = trim($_POST['login_id'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->userModel->login($login_id, $password);

            if ($user) {
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role;

                if (strtolower(trim($user->role)) === 'admin') {
                    header('Location: ' . URLROOT . '/admin/dashboard');
                } else {
                    header('Location: ' . URLROOT . '/post/index');
                }
                exit();
            } else {
                Flash::set('auth_error', 'Invalid Student Number/Email or password.');
                $this->view('auth/login');
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'student_num' => trim($_POST['student_num'] ?? ''),
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'role' => 'Student'
            ];

            if (!preg_match('/^[a-zA-Z]+\.[a-zA-Z]+@olivarezcollege\.edu\.ph$/', $data['email'])) {
                Flash::set('auth_error', 'Invalid email. Use firstname.lastname@olivarezcollege.edu.ph');
                $this->view('auth/registration');
                return;
            }

            if ($data['password'] !== trim($_POST['confirm_password'] ?? '')) {
                Flash::set('auth_error', 'Passwords do not match.');
                $this->view('auth/registration');
                return;
            }

            if ($this->userModel->checkUserExists($data['student_num'], $data['email'])) {
                Flash::set('auth_error', 'Student Number or Email is already registered.');
                $this->view('auth/registration');
                return;
            }

            if ($this->userModel->register($data)) {
                Flash::set('auth_success', 'Account created successfully! You can now login.');
                header('Location: ' . URLROOT . '/auth/login');
                exit();
            }
        } else {
            $this->view('auth/registration');
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
        exit();
    }
}