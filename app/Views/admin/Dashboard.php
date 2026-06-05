<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container-fluid mt-4" style="max-width: 1400px;">
    <div class="row">
        <div class="col-md-3 d-none d-md-block sticky-sidebar border-end">
            <div class="list-group list-group-flush shadow-sm rounded-4 overflow-hidden">
                <div class="list-group-item bg-light fw-bold"><i class="fas fa-tachometer-alt text-success"></i> Admin Menu</div>
                <a href="<?= URLROOT ?>/admin/dashboard" class="list-group-item list-group-item-action active bg-success border-0">Overview</a>
                <a href="<?= URLROOT ?>/admin/events" class="list-group-item list-group-item-action">List of Events</a>
                <a href="<?= URLROOT ?>/admin/users" class="list-group-item list-group-item-action">Manage Users</a>
            </div>
        </div>

        <div class="col-md-9 ps-md-4">
            <h2 class="text-success fw-bold mb-4">Pending Insights</h2>
            
            <?php if(empty($data['pending_posts'])): ?>
                <div class="status-card text-center border py-5 bg-white shadow-sm rounded-4" style="border-style: dashed !important;">
                    <i class="fas fa-check-circle text-success fs-1 mb-3 d-block"></i>
                    <h5 class="text-dark fw-bold">All clear!</h5>
                    <p class="text-muted mb-0">Clear. No Pending Suggestions.</p>
                </div>
            <?php else: ?>
                <?php foreach($data['pending_posts'] as $post): ?>
                    <div class="card mb-3 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-dark rounded-pill mb-2"><?= htmlspecialchars($post->event_title ?? 'Generalized Insight') ?></span>
                                    <h6 class="mb-0 fw-bold">
                                        Submitted by: <?= htmlspecialchars($post->student_name) ?>
                                        <?= (int)$post->is_anonymous === 1 ? '<span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Requested Anonymous</span>' : '' ?>
                                    </h6>
                                    <small class="text-muted"><?= date('M j, Y - h:i A', strtotime($post->created_at)) ?></small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?= URLROOT ?>/admin/approve/<?= $post->post_id ?>" class="btn btn-success btn-sm px-3 rounded-pill">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                    <a href="<?= URLROOT ?>/admin/reject/<?= $post->post_id ?>" class="btn btn-outline-danger btn-sm px-3 rounded-pill" onclick="return confirm('Are you sure you want to reject this insight?')">
                                        <i class="fas fa-times"></i> Reject
                                    </a>
                                </div>
                            </div>
                            <div class="bg-light p-3 rounded-3" style="border-left: 5px solid #006400;">
                                <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars(html_entity_decode($post->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?></h5>
                                <?php if(!empty($post->insight)): ?>
                                    <p class="fs-6 text-dark mb-0"><?= nl2br(htmlspecialchars(html_entity_decode($post->insight, ENT_QUOTES), ENT_QUOTES)) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>