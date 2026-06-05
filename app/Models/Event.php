<?php
class Event extends Model {
    public function getAllEvents() {
        $this->db->query("SELECT * FROM events ORDER BY event_date DESC");
        return $this->db->resultSet();
    }

    public function getEventsByStatus($status) {
        $this->db->query("SELECT * FROM events WHERE status = :status ORDER BY event_date ASC");
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    public function getActiveAndUpcomingEvents() {
        $this->db->query("SELECT * FROM events WHERE status IN ('Upcoming', 'Ongoing') ORDER BY event_date ASC");
        return $this->db->resultSet();
    }

    public function getEventById($id) {
        $this->db->query("SELECT * FROM events WHERE event_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addEvent($data) {
        $this->db->query("INSERT INTO events (title, category, description, event_date, end_date, status) VALUES (:title, :category, :description, :event_date, :end_date, 'Upcoming')");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':event_date', $data['event_date']);
        $this->db->bind(':end_date', $data['end_date']);
        
        if($this->db->execute()) {
            $this->db->query("SELECT event_id FROM events ORDER BY event_id DESC LIMIT 1");
            $latest = $this->db->single();
            $eventId = $latest->event_id;
            
            $this->db->query("INSERT INTO notifications (user_id, event_id, message, type) SELECT user_id, :event_id, :msg, 'New_Event' FROM users WHERE role = 'Student'");
            $this->db->bind(':event_id', $eventId);
            $this->db->bind(':msg', 'New Event Added: ' . $data['title']);
            $this->db->execute();
            
            return true;
        }
        return false;
    }

    public function updateEvent($data) {
        $this->db->query("UPDATE events SET title = :title, category = :category, description = :description, event_date = :event_date, end_date = :end_date, status = :status WHERE event_id = :event_id");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':event_date', $data['event_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':event_id', $data['event_id']);
        
        return $this->db->execute();
    }

    public function updateEventStatus($id, $status) {
        $this->db->query("UPDATE events SET status = :status WHERE event_id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        
        if($this->db->execute()) {
            $this->db->query("SELECT title FROM events WHERE event_id = :id");
            $this->db->bind(':id', $id);
            $event = $this->db->single();

            if($status === 'Ongoing') {
                $msg = 'Event is now ongoing! <a href="' . URLROOT . '/post/registerEvent/' . $id . '" class="text-success fw-bold text-decoration-none">Click here to register your attendance</a> for: ' . $event->title;
                $this->db->query("INSERT INTO notifications (user_id, event_id, message, type) SELECT user_id, :id, :msg, 'Ongoing_Event' FROM users WHERE role = 'Student'");
                $this->db->bind(':id', $id);
                $this->db->bind(':msg', $msg);
                $this->db->execute();
            } elseif($status === 'Finished') {
                $this->db->query("DELETE FROM notifications WHERE event_id = :id AND type = 'Ongoing_Event'");
                $this->db->bind(':id', $id);
                $this->db->execute();

                $msgWithSurvey = 'The event ' . $event->title . ' has ended. <a href="' . URLROOT . '/post/survey/' . $id . '" class="text-success fw-bold text-decoration-none">Click here to answer the survey.</a>';
                $this->db->query("INSERT INTO notifications (user_id, event_id, message, type) SELECT u.user_id, :id, CONCAT('Hi ', u.name, ', ', :msg), 'Survey' FROM users u INNER JOIN registrations r ON u.user_id = r.user_id WHERE u.role = 'Student' AND r.event_id = :id");
                $this->db->bind(':id', $id);
                $this->db->bind(':msg', $msgWithSurvey);
                $this->db->execute();

                $msgNoSurvey = 'The event ' . $event->title . ' has officially ended. Thank you!';
                $this->db->query("INSERT INTO notifications (user_id, event_id, message, type) SELECT u.user_id, :id, CONCAT('Hi ', u.name, ', ', :msg), 'Event_Finished' FROM users u LEFT JOIN registrations r ON u.user_id = r.user_id AND r.event_id = :id WHERE u.role = 'Student' AND r.user_id IS NULL");
                $this->db->bind(':id', $id);
                $this->db->bind(':msg', $msgNoSurvey);
                $this->db->execute();
            }
            return true;
        }
        return false;
    }

    public function checkIfRegistered($event_id, $user_id) {
        $this->db->query("SELECT * FROM registrations WHERE event_id = :event_id AND user_id = :user_id");
        $this->db->bind(':event_id', $event_id);
        $this->db->bind(':user_id', $user_id);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function registerUser($event_id, $user_id) {
        $this->db->query("INSERT INTO registrations (event_id, user_id, attended_at) VALUES (:event_id, :user_id, CURRENT_TIMESTAMP)");
        $this->db->bind(':event_id', $event_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function getEventRegistrations($event_id) {
        $this->db->query("SELECT u.student_num, u.name, r.attended_at FROM registrations r JOIN users u ON r.user_id = u.user_id WHERE r.event_id = :event_id ORDER BY r.attended_at DESC");
        $this->db->bind(':event_id', $event_id);
        return $this->db->resultSet();
    }

    public function checkIfSurveyAnswered($event_id, $user_id) {
        $this->db->query("SELECT * FROM survey_responses WHERE event_id = :event_id AND user_id = :user_id");
        $this->db->bind(':event_id', $event_id);
        $this->db->bind(':user_id', $user_id);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function submitSurveyResponse($data) {
        $this->db->query("INSERT INTO survey_responses (event_id, user_id, rating, feedback) VALUES (:event_id, :user_id, :rating, :feedback)");
        $this->db->bind(':event_id', $data['event_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':feedback', $data['feedback']);
        return $this->db->execute();
    }

    public function getSurveyResponses($event_id) {
        $this->db->query("SELECT s.*, u.name, u.student_num FROM survey_responses s JOIN users u ON s.user_id = u.user_id WHERE s.event_id = :event_id ORDER BY s.created_at DESC");
        $this->db->bind(':event_id', $event_id);
        return $this->db->resultSet();
    }

    public function deleteEvent($id) {
        try {
            $this->db->query("DELETE FROM survey_responses WHERE event_id = :id");
            $this->db->bind(':id', $id); $this->db->execute();

            $this->db->query("DELETE FROM surveys WHERE event_id = :id");
            $this->db->bind(':id', $id); $this->db->execute();

            $this->db->query("DELETE FROM registrations WHERE event_id = :id");
            $this->db->bind(':id', $id); $this->db->execute();

            $this->db->query("DELETE FROM notifications WHERE event_id = :id");
            $this->db->bind(':id', $id); $this->db->execute();

            $this->db->query("DELETE FROM posts WHERE event_id = :id");
            $this->db->bind(':id', $id); $this->db->execute();

            $this->db->query("DELETE FROM events WHERE event_id = :id");
            $this->db->bind(':id', $id); return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}