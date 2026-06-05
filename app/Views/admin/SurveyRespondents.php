<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container-fluid mt-4" style="max-width: 1300px;">
    <div class="row">
        
        <div class="col-md-3 border-end">
            <h5 class="fw-bold mb-3"><i class="fas fa-user-circle text-success"></i> Admin Menu</h5>
            <div class="list-group list-group-flush shadow-sm rounded overflow-hidden">
                <a href="<?= URLROOT ?>/admin/dashboard" class="list-group-item list-group-item-action border-0 py-3">Overview</a>
                <a href="<?= URLROOT ?>/admin/events" class="list-group-item list-group-item-action border-0 py-3 active bg-success text-white fw-bold">List of Events</a>
                <a href="<?= URLROOT ?>/admin/users" class="list-group-item list-group-item-action border-0 py-3">Manage Users</a>
            </div>
        </div>

        <div class="col-md-9 px-md-4">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-3 mt-md-0">
                <h3 class="fw-bold text-success mb-0"><i class="fas fa-clipboard-list me-2"></i> Survey Responses</h3>
                <a href="<?= URLROOT ?>/admin/events" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Back</a>
            </div>

            <h5 class="fw-bold mb-3"><?= htmlspecialchars($data['event']->title ?? 'Event') ?></h5>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="py-3 ps-4">Student</th>
                                    <th class="py-3 text-center">Rating</th>
                                    <th class="py-3 w-50">Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['responses'])): foreach($data['responses'] as $resp): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold d-block"><?= htmlspecialchars($resp->name) ?></span>
                                            <small class="text-muted"><?= htmlspecialchars($resp->student_num) ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success rounded-pill px-3 py-2"><?= htmlspecialchars($resp->rating) ?> / 5</span>
                                        </td>
                                        <td class="text-muted small pe-4"><?= nl2br(htmlspecialchars($resp->feedback)) ?></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">No survey responses yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>