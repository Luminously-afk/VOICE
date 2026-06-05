<?php
class User extends Model {
    public function checkUserExists($student_num, $email) {
        $this->db->query("SELECT * FROM users WHERE student_num = :student_num OR email = :email");
        $this->db->bind(':student_num', $student_num);
        $this->db->bind(':email', $email);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function checkUserExistsById($id) {
        $this->db->query("SELECT user_id FROM users WHERE user_id = :id");
        $this->db->bind(':id', $id);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function register($data) {
        $this->db->query("INSERT INTO users (student_num, name, email, password, role) VALUES (:student_num, :name, :email, :password, :role)");
        $this->db->bind(':student_num', $data['student_num']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role', $data['role']);
        return $this->db->execute();
    }

    public function login($login_id, $password) {
        $this->db->query("SELECT * FROM users WHERE email = :id OR student_num = :id");
        $this->db->bind(':id', $login_id);
        $row = $this->db->single();

        if ($row && password_verify($password, $row->password)) {
            return $row;
        }
        return false;
    }

    public function getUsers() {
        $this->db->query("SELECT * FROM users ORDER BY user_id DESC");
        return $this->db->resultSet();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE user_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUser($data) {
        $this->db->query("UPDATE users SET student_num = :student_num, name = :name, email = :email, role = :role WHERE user_id = :user_id");
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':student_num', $data['student_num']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        return $this->db->execute();
    }

    public function updateStudentProfile($data) {
        if (!empty($data['password'])) {
            $this->db->query("UPDATE users SET name = :name, password = :password WHERE user_id = :user_id");
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        } else {
            $this->db->query("UPDATE users SET name = :name WHERE user_id = :user_id");
        }
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':user_id', $data['user_id']);
        return $this->db->execute();
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE user_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}