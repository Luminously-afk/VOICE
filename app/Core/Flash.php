<?php
require_once 'Session.php';

class Flash {
    public static function set($name, $message, $type = 'success') {
        Session::set('flash_' . $name, [
            'message' => $message,
            'type' => $type
        ]);
    }

    public static function display($name) {
        $flashKey = 'flash_' . $name;
        
        if (Session::get($flashKey)) {
            $flash = Session::get($flashKey);
            
            $bgColor = ($flash['type'] == 'success') ? '#e8f5e9' : '#f8d7da';
            $textColor = ($flash['type'] == 'success') ? '#006400' : '#721c24';
            $border = ($flash['type'] == 'success') ? '#c8e6c9' : '#f5c6cb';
            
            echo '<div class="alert alert-dismissible fade show" role="alert" style="background-color: '.$bgColor.'; color: '.$textColor.'; border: 1px solid '.$border.'; margin-bottom: 20px;">';
            echo htmlspecialchars($flash['message']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            
            unset($_SESSION[$flashKey]);
        }
    }
}