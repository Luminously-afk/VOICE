<?php 
require_once '../app/Core/Auth.php';

if (!Auth::check()) {
    header('Location: ' . URLROOT . '/login');
    exit();
}

$user = Auth::user();

require_once '../app/Views/layout/header.php'; 
require_once '../app/Views/components/navbar.php'; 
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 border-top border-success border-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-graduate text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="card-title fw-bold text-dark"><?= htmlspecialchars($user['name']) ?></h4>
                    <p class="text-muted mb-1">
                        <i class="fas fa-id-card"></i> <?= htmlspecialchars($user['student_num']) ?>
                    </p>
                    <span class="badge bg-success mb-3"><?= htmlspecialchars($user['role']) ?></span>
                    
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="<?= URLROOT ?>/auth/logout" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-success fw-bold" id="insights-tab" data-bs-toggle="tab" data-bs-target="#insights" type="button" role="tab" aria-controls="insights" aria-selected="true">
                        <i class="fas fa-comment-dots"></i> My Insights
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-success fw-bold" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab" aria-controls="events" aria-selected="false">
                        <i class="fas fa-calendar-check"></i> Registered Events
                    </button>
                </li>
            </ul>

            <div class="tab-content bg-white p-4 border border-top-0 shadow-sm rounded-bottom" id="dashboardTabsContent">
                
                <div class="tab-pane fade show active" id="insights" role="tabpanel" aria-labelledby="insights-tab">
                    <h5 class="mb-4">My Submitted Suggestions</h5>
                    
                    <?php if (empty($data['my_posts'])): ?>
                        <div class="alert alert-light text-center" role="alert">
                            You haven't shared any insights yet! <br>
                            <a href="<?= URLROOT ?>/popular" class="text-success fw-bold text-decoration-none">Go to Feed and post one.</a>
                        </div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($data['my_posts'] as $post): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary mb-1"><?= htmlspecialchars($post->event_title) ?></span>
                                        <p class="mb-0 text-truncate" style="max-width: 400px;"><?= htmlspecialchars($post->insight) ?></p>
                                    </div>
                                    <span class="badge bg-success rounded-pill" title="Total Score">
                                        <i class="fas fa-arrow-up"></i> <?= $post->score ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="events" role="tabpanel" aria-labelledby="events-tab">
                    <h5 class="mb-4">Events I'm Attending</h5>
                    
                    <?php if (empty($data['my_events'])): ?>
                        <div class="alert alert-light text-center" role="alert">
                            You haven't registered for any events yet.
                        </div>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($data['my_events'] as $event): ?>
                                <li class="list-group-item">
                                    <i class="fas fa-calendar-day text-success me-2"></i> 
                                    <strong><?= htmlspecialchars($event->title) ?></strong>
                                    <br>
                                    <small class="text-muted ms-4">Status: <?= htmlspecialchars($event->status) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require_once '../app/Views/layout/footer.php'; 
?>