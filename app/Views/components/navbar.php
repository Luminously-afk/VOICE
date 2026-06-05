<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="<?= URLROOT ?>/post/index">VOICE</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>

            <ul class="navbar-nav align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notifDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="fas fa-bell fs-5"></i>
                            <?php 
                            $unread = 0;
                            if(!empty($data['notifications'])) {
                                foreach($data['notifications'] as $n) { if(($n->status ?? 'Unread') == 'Unread') $unread++; }
                            }
                            if($unread > 0): 
                            ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $unread ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 py-0 overflow-hidden" style="width: 380px;">
                            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-bold small">Notifications</span>
                                <div>
                                    <?php if(!empty($data['notifications'])): ?>
                                        <a href="<?= URLROOT ?>/post/readAllNotifs" class="text-success small text-decoration-none fw-bold me-3" title="Mark all as read">Mark All Read</a>
                                        <a href="<?= URLROOT ?>/post/clearNotifs" class="text-danger small text-decoration-none fw-bold" onclick="return confirm('Clear all notifications?');">Clear All</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="list-group list-group-flush overflow-auto" style="max-height: 350px;">
                                <?php if(!empty($data['notifications'])): foreach($data['notifications'] as $notif): ?>
                                    <?php
                                        $type = $notif->type ?? '';
                                        $linkTarget = '';
                                        if (strpos($type, 'Post_Review_') !== false) {
                                            $linkTarget = 'admin/dashboard';
                                        } elseif (strpos($type, 'Suggestion_') !== false || strpos($type, 'vote_group_') !== false) {
                                            $linkTarget = 'post/profile';
                                        } else {
                                            $linkTarget = 'post/index';
                                        }
                                        $readActionLink = URLROOT . "/post/readNotif/" . $notif->notif_id . "?link=" . urlencode($linkTarget);
                                    ?>
                                    <div class="list-group-item p-3 border-bottom small position-relative <?= ($notif->status ?? 'Unread') == 'Unread' ? 'bg-light' : 'bg-white' ?> notification-item" style="transition: background-color 0.2s;">
                                        <div class="position-absolute top-0 end-0 mt-2 me-2 d-flex align-items-center z-3">
                                            <?php if(($notif->status ?? 'Unread') == 'Unread'): ?>
                                                <a href="<?= URLROOT ?>/post/readNotif/<?= $notif->notif_id ?>" class="text-success text-decoration-none me-3 fs-6" title="Mark as Read"><i class="fas fa-check"></i></a>
                                            <?php endif; ?>
                                            <a href="<?= URLROOT ?>/post/deleteNotif/<?= $notif->notif_id ?>" class="text-muted text-decoration-none fs-5" title="Delete Notification">&times;</a>
                                        </div>
                                        
                                        <a href="<?= $readActionLink ?>" class="text-decoration-none d-block z-1 position-relative">
                                            <p class="mb-1 pe-5 <?= ($notif->status ?? 'Unread') == 'Unread' ? 'fw-bold text-dark' : 'text-muted' ?>">
                                                <?= $notif->message ?? 'Update' ?>
                                            </p>
                                            <small class="<?= ($notif->status ?? 'Unread') == 'Unread' ? 'text-success fw-bold' : 'text-muted' ?>"><?= date('M j, g:i a', strtotime($notif->created_at ?? 'now')) ?></small>
                                        </a>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="p-4 text-center text-muted small">No notifications.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="btn btn-outline-light rounded-pill px-4 dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item py-2 fw-bold text-dark" href="<?= URLROOT ?>/post/profile"><i class="fas fa-history me-2 text-success"></i> My Activity</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if(isset($_SESSION['user_role']) && strtolower(trim($_SESSION['user_role'])) === 'admin'): ?>
                                <li><a class="dropdown-item py-2 fw-bold text-success" href="<?= URLROOT ?>/admin/dashboard"><i class="fas fa-user-shield me-2"></i> Admin Panel</a></li>
                                <li><a class="dropdown-item py-2 fw-bold text-primary" href="<?= URLROOT ?>/post/index"><i class="fas fa-eye me-2"></i> Student View</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item py-2" href="<?= URLROOT ?>/auth/logout"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a href="<?= URLROOT ?>/auth/login" class="btn btn-outline-light rounded-pill px-4 fw-bold">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= URLROOT ?>/auth/register" class="btn btn-light text-success rounded-pill px-4 fw-bold">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    .notification-item:hover {
        background-color: #f8f9fa !important;
        cursor: pointer;
    }
</style>