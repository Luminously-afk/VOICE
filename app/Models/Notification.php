<?php
class Notification extends Model {
    public function getNotifications($user_id) {
        $this->db->query("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 10");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addNotification($user_id, $message, $type = 'info') {
        $this->db->query("INSERT INTO notifications (user_id, message, type, status) VALUES (:user_id, :message, :type, 'Unread')");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':message', $message);
        $this->db->bind(':type', $type);
        return $this->db->execute();
    }

    public function markEventNotifAsRead($event_id, $user_id) {
        $this->db->query("UPDATE notifications SET status = 'Read', is_read = 1 WHERE event_id = :event_id AND user_id = :user_id");
        $this->db->bind(':event_id', $event_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function markAsRead($notif_id, $user_id) {
        $this->db->query("UPDATE notifications SET status = 'Read', is_read = 1 WHERE notif_id = :notif_id AND user_id = :user_id");
        $this->db->bind(':notif_id', $notif_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function markAllAsRead($user_id) {
        $this->db->query("UPDATE notifications SET status = 'Read', is_read = 1 WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function deleteNotification($notif_id, $user_id) {
        $this->db->query("DELETE FROM notifications WHERE notif_id = :notif_id AND user_id = :user_id");
        $this->db->bind(':notif_id', $notif_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function clearAllNotifications($user_id) {
        $this->db->query("DELETE FROM notifications WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }
}