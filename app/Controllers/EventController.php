<?php
class EventController extends Controller {
    private $eventModel;
    private $notifModel;

    public function __construct() {
        $this->eventModel = $this->model('Event');
        $this->notifModel = $this->model('Notification');
        
        require_once '../app/Core/AdminGuard.php';
        AdminGuard::protect();
    }

    public function index() {
        header('Location: ' . URLROOT . '/admin/events');
        exit();
    }

    public function create() {
        $this->view('events/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => $_POST['event_date'],
                'status' => $_POST['status']
            ];
            if ($this->eventModel->addEvent($data)) {
                Flash::set('admin_message', 'New event published!');
                header('Location: ' . URLROOT . '/admin/events');
                exit();
            }
        }
    }

    public function edit($id) {
        $event = $this->eventModel->getEventById($id);
        $this->view('events/edit', ['event' => $event]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => $_POST['event_date'],
                'status' => $_POST['status']
            ];

            if ($this->eventModel->updateEvent($data)) {
                if ($data['status'] === 'Finished') {
                    $registrants = $this->eventModel->getRegistrantsByEvent($id);
                    foreach ($registrants as $user) {
                        $msg = "The event '" . $data['title'] . "' has finished. Please check your dashboard for the evaluation survey.";
                        $this->notifModel->addNotification($user->user_id, $msg, 'survey');
                    }
                }
                Flash::set('admin_message', 'Event updated successfully!');
                header('Location: ' . URLROOT . '/admin/events');
                exit();
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->eventModel->deleteEvent($id)) {
                Flash::set('admin_message', 'Event and related data deleted.');
                header('Location: ' . URLROOT . '/admin/events');
                exit();
            }
        }
    }
}