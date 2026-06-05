<?php
class Post extends Model {
    public function getNewsfeed($event_id = null, $user_id = null, $q = null) {
        $sql = "SELECT p.*, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, u.name as student_name, e.title as event_title";
        if (!empty($user_id)) {
            $sql .= ", (SELECT vote FROM votes WHERE post_id = p.post_id AND user_id = :user_id LIMIT 1) AS user_vote";
        } else {
            $sql .= ", NULL AS user_vote";
        }
        $sql .= " FROM posts p LEFT JOIN users u ON p.user_id = u.user_id LEFT JOIN events e ON p.event_id = e.event_id WHERE LOWER(p.status) IN ('approved', 'published')";
        
        if (!empty($event_id)) { $sql .= " AND p.event_id = :event_id"; }
        if (!empty($q)) { $sql .= " AND (p.title LIKE :q OR p.insight LIKE :q)"; }
        $sql .= " ORDER BY (p.upvotes - p.downvotes) DESC, p.created_at DESC";
        
        $this->db->query($sql);
        if (!empty($user_id)) { $this->db->bind(':user_id', $user_id); }
        if (!empty($event_id)) { $this->db->bind(':event_id', $event_id); }
        if (!empty($q)) { $this->db->bind(':q', '%' . $q . '%'); }
        return $this->db->resultSet();
    }

    public function getPopularPosts($event_id = null, $user_id = null, $q = null) { 
        return $this->getNewsfeed($event_id, $user_id, $q); 
    }

    public function getNewestPosts($event_id = null, $user_id = null, $q = null) {
        $sql = "SELECT p.*, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, u.name as student_name, e.title as event_title";
        if (!empty($user_id)) {
            $sql .= ", (SELECT vote FROM votes WHERE post_id = p.post_id AND user_id = :user_id LIMIT 1) AS user_vote";
        } else {
            $sql .= ", NULL AS user_vote";
        }
        $sql .= " FROM posts p LEFT JOIN users u ON p.user_id = u.user_id LEFT JOIN events e ON p.event_id = e.event_id WHERE LOWER(p.status) IN ('approved', 'published')";
        
        if (!empty($event_id)) { $sql .= " AND p.event_id = :event_id"; }
        if (!empty($q)) { $sql .= " AND (p.title LIKE :q OR p.insight LIKE :q)"; }
        $sql .= " ORDER BY p.created_at DESC";
        
        $this->db->query($sql);
        if (!empty($user_id)) { $this->db->bind(':user_id', $user_id); }
        if (!empty($event_id)) { $this->db->bind(':event_id', $event_id); }
        if (!empty($q)) { $this->db->bind(':q', '%' . $q . '%'); }
        return $this->db->resultSet();
    }

    public function getUserPosts($user_id) {
        $this->db->query("SELECT p.*, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, u.name as student_name, e.title as event_title, (SELECT vote FROM votes WHERE post_id = p.post_id AND user_id = :user_id LIMIT 1) AS user_vote FROM posts p LEFT JOIN users u ON p.user_id = u.user_id LEFT JOIN events e ON p.event_id = e.event_id WHERE p.user_id = :user_id ORDER BY p.created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getUpvotedPosts($user_id) {
        $this->db->query("SELECT p.*, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, u.name as student_name, e.title as event_title, 'up' AS user_vote FROM posts p INNER JOIN votes v ON p.post_id = v.post_id LEFT JOIN users u ON p.user_id = u.user_id LEFT JOIN events e ON p.event_id = e.event_id WHERE v.user_id = :user_id AND v.vote = 'up' AND LOWER(p.status) IN ('approved', 'published') ORDER BY v.updated_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getDownvotedPosts($user_id) {
        $this->db->query("SELECT p.*, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, u.name as student_name, e.title as event_title, 'down' AS user_vote FROM posts p INNER JOIN votes v ON p.post_id = v.post_id LEFT JOIN users u ON p.user_id = u.user_id LEFT JOIN events e ON p.event_id = e.event_id WHERE v.user_id = :user_id AND v.vote = 'down' AND LOWER(p.status) IN ('approved', 'published') ORDER BY v.updated_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getPostById($id) {
        $this->db->query("SELECT * FROM posts WHERE post_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updatePost($data) {
        $this->db->query("UPDATE posts SET title = :title, insight = :insight, event_id = :event_id, is_anonymous = :is_anonymous, status = 'Pending' WHERE post_id = :post_id AND user_id = :user_id");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':insight', $data['insight']);
        $this->db->bind(':event_id', $data['event_id']);
        $this->db->bind(':is_anonymous', $data['is_anonymous']);
        $this->db->bind(':post_id', $data['post_id']);
        $this->db->bind(':user_id', $data['user_id']);
        return $this->db->execute();
    }

    public function deletePost($id, $user_id) {
        $this->db->query("SELECT p.post_id, p.title, u.name FROM posts p JOIN users u ON p.user_id = u.user_id WHERE p.post_id = :id AND p.user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        $post = $this->db->single();

        if ($post) {
            $this->db->query("DELETE FROM votes WHERE post_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $notif_type_vote = '%vote_group_' . $id;
            $this->db->query("DELETE FROM notifications WHERE user_id = :user_id AND type LIKE :notif_type");
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':notif_type', $notif_type_vote);
            $this->db->execute();

            $notif_app = 'Suggestion_Approved_' . $id;
            $notif_rej = 'Suggestion_Rejected_' . $id;
            $this->db->query("DELETE FROM notifications WHERE user_id = :user_id AND type IN (:app, :rej)");
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':app', $notif_app);
            $this->db->bind(':rej', $notif_rej);
            $this->db->execute();

            $notif_rev = 'Post_Review_' . $id;
            $this->db->query("DELETE FROM notifications WHERE type = :rev AND LOWER(message) LIKE CONCAT('%', LOWER(:user_name), '%')");
            $this->db->bind(':rev', $notif_rev);
            $this->db->bind(':user_name', $post->name);
            $this->db->execute();

            $this->db->query("DELETE FROM posts WHERE post_id = :id AND user_id = :user_id");
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $user_id);
            return $this->db->execute();
        }
        
        return false;
    }

    public function approve($post_id) {
        $this->db->query("UPDATE posts SET status = 'Approved' WHERE post_id = :post_id");
        $this->db->bind(':post_id', $post_id); 
        if ($this->db->execute()) {
            $this->db->query("CALL sp_approve_insight(:post_id)");
            $this->db->bind(':post_id', $post_id);
            $this->db->execute();

            $notif_type = 'Suggestion_Approved_' . $post_id;
            $this->db->query("INSERT INTO notifications (user_id, message, type) SELECT user_id, 'Your submitted insight has been approved and published.', :notif_type FROM posts WHERE post_id = :post_id");
            $this->db->bind(':notif_type', $notif_type);
            $this->db->bind(':post_id', $post_id);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function reject($post_id) {
        $this->db->query("UPDATE posts SET status = 'Rejected' WHERE post_id = :post_id");
        $this->db->bind(':post_id', $post_id);
        if ($this->db->execute()) {
            $notif_type = 'Suggestion_Rejected_' . $post_id;
            $this->db->query("INSERT INTO notifications (user_id, message, type) SELECT user_id, 'Your submitted insight was reviewed and declined.', :notif_type FROM posts WHERE post_id = :post_id");
            $this->db->bind(':notif_type', $notif_type);
            $this->db->bind(':post_id', $post_id);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function addPost($data) {
        $this->db->query("INSERT INTO posts (user_id, event_id, title, insight, is_anonymous, status) VALUES (:user_id, :event_id, :title, :insight, :is_anonymous, 'Pending')");
        $this->db->bind(':user_id', $data['user_id']); 
        $this->db->bind(':event_id', $data['event_id']); 
        $this->db->bind(':title', $data['title']); 
        $this->db->bind(':insight', $data['insight']);
        $this->db->bind(':is_anonymous', $data['is_anonymous']);
        
        if ($this->db->execute()) {
            $this->db->query("SELECT post_id FROM posts WHERE user_id = :user_id ORDER BY post_id DESC LIMIT 1");
            $this->db->bind(':user_id', $data['user_id']);
            $new_post = $this->db->single();
            $post_id = $new_post->post_id;

            $this->db->query("SELECT name FROM users WHERE user_id = :user_id");
            $this->db->bind(':user_id', $data['user_id']);
            $user = $this->db->single();
            
            $notif_type = 'Post_Review_' . $post_id;
            $this->db->query("INSERT INTO notifications (user_id, message, type) SELECT user_id, :msg, :notif_type FROM users WHERE role = 'Admin'");
            $this->db->bind(':msg', $user->name . ' submitted a post to review.');
            $this->db->bind(':notif_type', $notif_type);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function getPendingPosts() {
        $this->db->query("SELECT p.*, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, u.name as student_name, e.title as event_title FROM posts p LEFT JOIN users u ON p.user_id = u.user_id LEFT JOIN events e ON p.event_id = e.event_id WHERE p.status = 'Pending' ORDER BY p.created_at ASC");
        return $this->db->resultSet();
    }

    public function updateVote($post_id, $user_id, $type) {
        $this->db->query("SELECT vote FROM votes WHERE post_id = :post_id AND user_id = :user_id LIMIT 1");
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':user_id', $user_id);
        $existing = $this->db->single();

        if (!$existing) {
            $this->db->query("INSERT INTO votes (post_id, user_id, vote, created_at, updated_at) VALUES (:post_id, :user_id, :vote, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
            $this->db->bind(':post_id', $post_id); $this->db->bind(':user_id', $user_id); $this->db->bind(':vote', $type);
            $this->db->execute();
        } else if ($existing->vote === $type) {
            $this->db->query("DELETE FROM votes WHERE post_id = :post_id AND user_id = :user_id");
            $this->db->bind(':post_id', $post_id); $this->db->bind(':user_id', $user_id);
            $this->db->execute();
        } else {
            $this->db->query("UPDATE votes SET vote = :vote, updated_at = CURRENT_TIMESTAMP WHERE post_id = :post_id AND user_id = :user_id");
            $this->db->bind(':post_id', $post_id); $this->db->bind(':user_id', $user_id); $this->db->bind(':vote', $type);
            $this->db->execute();
        }

        $this->db->query("SELECT COUNT(vote_id) as count FROM votes WHERE post_id = :post_id AND vote = 'up'");
        $this->db->bind(':post_id', $post_id);
        $up = $this->db->single()->count;

        $this->db->query("SELECT COUNT(vote_id) as count FROM votes WHERE post_id = :post_id AND vote = 'down'");
        $this->db->bind(':post_id', $post_id);
        $down = $this->db->single()->count;

        $score = $up - $down;

        $this->db->query("UPDATE posts SET upvotes = :up, downvotes = :down, vote_score = :score WHERE post_id = :post_id");
        $this->db->bind(':up', $up);
        $this->db->bind(':down', $down);
        $this->db->bind(':score', $score);
        $this->db->bind(':post_id', $post_id);
        $this->db->execute();

        $this->db->query("SELECT p.vote_score, p.upvotes AS upvotes_count, p.downvotes AS downvotes_count, (SELECT vote FROM votes WHERE post_id = :post_id AND user_id = :user_id LIMIT 1) AS user_vote FROM posts p WHERE p.post_id = :post_id");
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    public function handleGroupedVoteNotification($post_id, $voter_id, $type) {
        $this->db->query("SELECT user_id, title FROM posts WHERE post_id = :post_id");
        $this->db->bind(':post_id', $post_id);
        $post = $this->db->single();
        
        if (!$post || (int)$post->user_id === (int)$voter_id) { return; }

        $this->db->query("SELECT u.name FROM votes v JOIN users u ON v.user_id = u.user_id WHERE v.post_id = :post_id AND v.vote = :type ORDER BY v.updated_at DESC");
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':type', $type);
        $voters = $this->db->resultSet();
        $total_votes = count($voters);

        $notif_type = $type . 'vote_group_' . $post_id;

        $this->db->query("DELETE FROM notifications WHERE user_id = :author_id AND type = :notif_type");
        $this->db->bind(':author_id', $post->user_id);
        $this->db->bind(':notif_type', $notif_type);
        $this->db->execute();

        if ($total_votes > 0) {
            $action_word = ($type === 'up') ? 'upvoted' : 'downvoted';
            if ($total_votes === 1) {
                $msg = htmlspecialchars($voters[0]->name) . " {$action_word} your insight: \"" . htmlspecialchars($post->title) . "\"";
            } elseif ($total_votes === 2) {
                $msg = htmlspecialchars($voters[0]->name) . " and " . htmlspecialchars($voters[1]->name) . " {$action_word} your insight: \"" . htmlspecialchars($post->title) . "\"";
            } else {
                $extra = $total_votes - 1;
                $msg = htmlspecialchars($voters[0]->name) . " and {$extra} others {$action_word} your insight: \"" . htmlspecialchars($post->title) . "\"";
            }

            $this->db->query("INSERT INTO notifications (user_id, message, type) VALUES (:author_id, :msg, :notif_type)");
            $this->db->bind(':author_id', $post->user_id);
            $this->db->bind(':msg', $msg);
            $this->db->bind(':notif_type', $notif_type);
            $this->db->execute();
        }
    }
}