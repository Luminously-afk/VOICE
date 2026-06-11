<?php
class AdminController extends Controller {
    private $postModel;
    private $userModel;
    private $eventModel;
    private $notifModel;

    public function __construct() {
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
        $this->eventModel = $this->model('Event');
        $this->notifModel = $this->model('Notification');
        
        if (!isset($_SESSION['user_role']) || strtolower(trim($_SESSION['user_role'])) !== 'admin') {
            header('Location: ' . URLROOT . '/post/index');
            exit();
        }
    }

    public function index() { 
        $this->dashboard(); 
    }

    public function dashboard() {
        $pending = $this->postModel->getPendingPosts();
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('admin/Dashboard', ['pending_posts' => $pending, 'notifications' => $notifs]);
    }

    public function approve($id) {
        if ($this->postModel->approve($id)) {
            Flash::set('admin_message', 'Insight approved!');
            header('Location: ' . URLROOT . '/admin/dashboard');
            exit();
        }
    }

    public function reject($id) {
        if ($this->postModel->reject($id)) {
            Flash::set('admin_message', 'Insight rejected.');
            header('Location: ' . URLROOT . '/admin/dashboard');
            exit();
        }
    }

    public function events() {
        $events = $this->eventModel->getAllEvents();
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('admin/Events', ['events' => $events, 'notifications' => $notifs]);
    }

    public function viewEvent($id) {
        $event = $this->eventModel->getEventById($id);
        $registrations = $this->eventModel->getEventRegistrations($id);
        $responses = $this->eventModel->getSurveyResponses($id);
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('admin/ViewEvent', [
            'event' => $event, 
            'registrations' => $registrations, 
            'responses' => $responses, 
            'notifications' => $notifs
        ]);
    }

    public function createEvent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $raw_description = $_POST['description'] ?? '';
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'description' => trim($raw_description),
                'event_date' => trim($_POST['event_date']),
                'end_date' => trim($_POST['end_date'])
            ];

            if ($this->eventModel->addEvent($data)) {
                Flash::set('admin_message', 'Event created successfully!');
                header('Location: ' . URLROOT . '/admin/events');
                exit();
            }
        } else {
            $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
            $this->view('admin/CreateEvent', ['notifications' => $notifs]);
        }
    }

    public function editEvent($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $raw_description = $_POST['description'] ?? '';
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'event_id' => $id,
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'description' => trim($raw_description),
                'event_date' => trim($_POST['event_date']),
                'end_date' => trim($_POST['end_date']),
                'status' => trim($_POST['status'])
            ];

            $oldEvent = $this->eventModel->getEventById($id);

            if ($this->eventModel->updateEvent($data)) {
                if ($oldEvent->status !== $data['status']) {
                    $this->eventModel->updateEventStatus($id, $data['status']);
                }
                Flash::set('admin_message', 'Event updated successfully!');
                header('Location: ' . URLROOT . '/admin/events');
                exit();
            }
        } else {
            $event = $this->eventModel->getEventById($id);
            $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
            $this->view('admin/EditEvent', ['event' => $event, 'notifications' => $notifs]);
        }
    }

    public function deleteEvent($id) {
        $this->eventModel->deleteEvent($id);
        header('Location: ' . URLROOT . '/admin/events');
        exit();
    }

    public function surveyRespondents($event_id) {
        $responses = $this->eventModel->getSurveyResponses($event_id);
        $event = $this->eventModel->getEventById($event_id);
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('admin/SurveyRespondents', ['responses' => $responses, 'event' => $event, 'notifications' => $notifs]);
    }

    public function users() {
        $users = $this->userModel->getUsers();
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('admin/Users', ['users' => $users, 'notifications' => $notifs]);
    }

    public function editUser($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'user_id' => $id,
                'student_num' => trim($_POST['student_num']),
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role'])
            ];

            if ($this->userModel->updateUser($data)) {
                Flash::set('admin_message', 'User updated successfully!');
                header('Location: ' . URLROOT . '/admin/users');
                exit();
            }
        } else {
            $user = $this->userModel->getUserById($id);
            $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
            $this->view('admin/EditUser', ['user' => $user, 'notifications' => $notifs]);
        }
    }

    public function uploadImage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array(strtolower($ext), $allowed)) {
                    $filename = uniqid('evt_') . '.' . $ext;
                    $spaces = new Spaces();
                    $url = $spaces->uploadImage($file['tmp_name'], $filename, 'events');
                    if ($url) {
                        header('Content-Type: application/json');
                        echo json_encode(['location' => $url]);
                        exit();
                    }
                }
            }
        }
        header('HTTP/1.1 500 Server Error');
        exit();
    }

    public function deleteUser($id) {
        $this->userModel->deleteUser($id);
        Flash::set('admin_message', 'User deleted successfully!');
        header('Location: ' . URLROOT . '/admin/users');
        exit();
    }
}