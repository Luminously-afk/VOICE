<?php
class Comment extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function addComment($data) {
        $this->db->query("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
        $this->db->bind(':post_id', $data['post_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content', $data['content']);
        return $this->db->execute();
    }

    public function getCommentsByPostId($postId) {
        $this->db->query("SELECT c.*, u.name as student_name FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.post_id = :post_id ORDER BY c.created_at ASC");
        $this->db->bind(':post_id', $postId);
        return $this->db->resultSet();
    }
}
