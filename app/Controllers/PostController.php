<?php
class PostController extends Controller {
    private $postModel;
    private $eventModel;
    private $notifModel;

    public function __construct() {
        parent::__construct();
        $this->postModel = $this->model('Post');
        $this->eventModel = $this->model('Event');
        $this->notifModel = $this->model('Notification');
    }

    public function index() {
        $event_id = $_GET['event_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;
        $q = $_GET['q'] ?? null;
        $posts = $this->postModel->getPopularPosts($event_id, $user_id, $q);
        $events = $this->eventModel->getAllEvents();
        $active_events = $this->eventModel->getActiveAndUpcomingEvents();
        $notifs = isset($_SESSION['user_id']) ? $this->notifModel->getNotifications($_SESSION['user_id']) : [];
        $data = ['posts' => $posts, 'events' => $events, 'upcoming_events' => $active_events, 'notifications' => $notifs, 'feed_type' => 'popular'];
        $this->view('posts/index', $data);
    }

    public function latest() {
        $event_id = $_GET['event_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;
        $q = $_GET['q'] ?? null;
        $posts = $this->postModel->getNewestPosts($event_id, $user_id, $q);
        $events = $this->eventModel->getAllEvents();
        $active_events = $this->eventModel->getActiveAndUpcomingEvents();
        $notifs = isset($_SESSION['user_id']) ? $this->notifModel->getNotifications($_SESSION['user_id']) : [];
        $data = ['posts' => $posts, 'events' => $events, 'upcoming_events' => $active_events, 'notifications' => $notifs, 'feed_type' => 'new'];
        $this->view('posts/index', $data);
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        
        $user_id = $_SESSION['user_id'];
        $tab = $_GET['tab'] ?? 'posts';
        
        if ($tab === 'upvoted') {
            $posts = $this->postModel->getUpvotedPosts($user_id);
        } elseif ($tab === 'downvoted') {
            $posts = $this->postModel->getDownvotedPosts($user_id);
        } else {
            $posts = $this->postModel->getUserPosts($user_id);
        }

        $notifs = $this->notifModel->getNotifications($user_id);
        $data = ['posts' => $posts, 'notifications' => $notifs, 'active_tab' => $tab];
        $this->view('posts/profile', $data);
    }

    public function editPost($id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        
        $post = $this->postModel->getPostById($id);
        if (!$post || (int)$post->user_id !== (int)$_SESSION['user_id']) { 
            header('Location: ' . URLROOT . '/post/profile'); 
            exit(); 
        }
        
        $active_events = $this->eventModel->getActiveAndUpcomingEvents();
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('posts/edit_post', ['post' => $post, 'events' => $active_events, 'notifications' => $notifs]);
    }

    public function updatePost($id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $post = $this->postModel->getPostById($id);
            if (!$post || (int)$post->user_id !== (int)$_SESSION['user_id']) { 
                header('Location: ' . URLROOT . '/post/profile'); 
                exit(); 
            }

            $event_id = trim($_POST['event_id']);
            if ($event_id === 'generalized' || empty($event_id)) {
                $event_id = null;
            }

            $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;

            $data = [
                'post_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'event_id' => $event_id,
                'title' => trim($_POST['title']),
                'insight' => trim($_POST['insight']),
                'is_anonymous' => $is_anonymous
            ];
            
            if ($this->postModel->updatePost($data)) {
                Flash::set('post_message', 'Insight updated and resubmitted for review!');
                header('Location: ' . URLROOT . '/post/profile');
                exit();
            }
        }
    }

    public function deletePost($id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        
        if ($this->postModel->deletePost($id, $_SESSION['user_id'])) {
            Flash::set('post_message', 'Insight deleted successfully.');
        }
        header('Location: ' . URLROOT . '/post/profile');
        exit();
    }

    public function editProfile() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        
        $userModel = $this->model('User');
        $user = $userModel->getUserById($_SESSION['user_id']);
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        
        $this->view('posts/edit_profile', ['user' => $user, 'notifications' => $notifs]);
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'password' => trim($_POST['password'] ?? '')
            ];

            if (!empty($data['password']) && $data['password'] !== trim($_POST['confirm_password'] ?? '')) {
                Flash::set('post_message', 'Passwords do not match.', 'error');
                header('Location: ' . URLROOT . '/post/editProfile');
                exit();
            }

            $userModel = $this->model('User');
            if ($userModel->updateStudentProfile($data)) {
                $_SESSION['user_name'] = $data['name'];
                Flash::set('post_message', 'Profile updated successfully!');
                header('Location: ' . URLROOT . '/post/profile');
                exit();
            }
        }
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        $active_events = $this->eventModel->getActiveAndUpcomingEvents();
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        $this->view('posts/create', ['events' => $active_events, 'notifications' => $notifs]);
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $event_id = trim($_POST['event_id']);
            if ($event_id === 'generalized' || empty($event_id)) {
                $event_id = null;
            }

            $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;

            $data = [
                'user_id' => $_SESSION['user_id'], 
                'event_id' => $event_id, 
                'title' => trim($_POST['title']),
                'insight' => trim($_POST['insight']),
                'is_anonymous' => $is_anonymous
            ];
            if ($this->postModel->addPost($data)) {
                Flash::set('post_message', 'Insight submitted for review!');
                header('Location: ' . URLROOT . '/post/index');
                exit();
            }
        }
    }

    public function vote($post_id, $type) {
        if (!isset($_SESSION['user_id'])) { 
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'redirect' => true]); 
            exit(); 
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $updatedRow = $this->postModel->updateVote($post_id, $_SESSION['user_id'], $type);
            header('Content-Type: application/json');
            if($updatedRow) { 
                $this->postModel->handleGroupedVoteNotification($post_id, $_SESSION['user_id'], $type);
                echo json_encode([
                    'success' => true, 
                    'score' => $updatedRow->vote_score,
                    'upvotes' => $updatedRow->upvotes_count,
                    'downvotes' => $updatedRow->downvotes_count,
                    'user_vote' => $updatedRow->user_vote
                ]); 
            } else {
                echo json_encode(['success' => false]);
            }
            exit();
        }
    }

    public function registerEvent($event_id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        
        $event = $this->eventModel->getEventById($event_id);
        if ($event->status !== 'Ongoing') {
            Flash::set('post_message', 'You can only register for ongoing events.');
            header('Location: ' . URLROOT . '/post/index');
            exit();
        }

        if ($this->eventModel->checkIfRegistered($event_id, $_SESSION['user_id'])) {
            Flash::set('post_message', 'You are already registered for this event.');
        } else {
            $this->eventModel->registerUser($event_id, $_SESSION['user_id']);
            Flash::set('post_message', 'Attendance registered successfully!');
        }

        $this->notifModel->markEventNotifAsRead($event_id, $_SESSION['user_id']);
        header('Location: ' . URLROOT . '/post/index');
        exit();
    }

    public function survey($event_id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        
        $event = $this->eventModel->getEventById($event_id);
        if ($event->status !== 'Finished') {
            Flash::set('post_message', 'Survey is only available for finished events.');
            header('Location: ' . URLROOT . '/post/index');
            exit();
        }

        if ($this->eventModel->checkIfSurveyAnswered($event_id, $_SESSION['user_id'])) {
            Flash::set('post_message', 'You already answered this survey.');
            header('Location: ' . URLROOT . '/post/index');
            exit();
        }

        $this->notifModel->markEventNotifAsRead($event_id, $_SESSION['user_id']);
        $notifs = $this->notifModel->getNotifications($_SESSION['user_id']);
        
        $this->view('posts/survey', ['event' => $event, 'notifications' => $notifs]);
    }

    public function submitSurvey() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $event_id = trim($_POST['event_id']);
            $event = $this->eventModel->getEventById($event_id);
            
            if ($event->status !== 'Finished') {
                Flash::set('post_message', 'You can only submit surveys for finished events.');
                header('Location: ' . URLROOT . '/post/index');
                exit();
            }

            if ($this->eventModel->checkIfSurveyAnswered($event_id, $_SESSION['user_id'])) {
                Flash::set('post_message', 'You already answered this survey.');
                header('Location: ' . URLROOT . '/post/index');
                exit();
            }

            $data = [
                'event_id' => $event_id,
                'user_id' => $_SESSION['user_id'],
                'rating' => trim($_POST['rating']),
                'feedback' => trim($_POST['feedback'])
            ];
            $this->eventModel->submitSurveyResponse($data);
            Flash::set('post_message', 'Survey submitted! Thank you for your feedback.');
            header('Location: ' . URLROOT . '/post/index');
            exit();
        }
    }

    public function readNotif($notif_id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        $this->notifModel->markAsRead($notif_id, $_SESSION['user_id']);
        
        if (!empty($_GET['link'])) {
            $redirect = URLROOT . '/' . ltrim($_GET['link'], '/');
        } else {
            $redirect = $_SERVER['HTTP_REFERER'] ?? URLROOT . '/post/index';
        }
        
        header('Location: ' . $redirect);
        exit();
    }

    public function readAllNotifs() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        $this->notifModel->markAllAsRead($_SESSION['user_id']);
        $redirect = $_SERVER['HTTP_REFERER'] ?? URLROOT . '/post/index';
        header('Location: ' . $redirect);
        exit();
    }

    public function deleteNotif($notif_id) {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        $this->notifModel->deleteNotification($notif_id, $_SESSION['user_id']);
        $redirect = $_SERVER['HTTP_REFERER'] ?? URLROOT . '/post/index';
        header('Location: ' . $redirect);
        exit();
    }

    public function clearNotifs() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        $this->notifModel->clearAllNotifications($_SESSION['user_id']);
        $redirect = $_SERVER['HTTP_REFERER'] ?? URLROOT . '/post/index';
        header('Location: ' . $redirect);
        exit();
    }
}