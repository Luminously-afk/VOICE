<?php 
require_once '../app/Core/Auth.php';
if (!Auth::check()) {
    header('Location: ' . URLROOT . '/login');
    exit();
}
require_once '../app/Views/layout/header.php'; 
require_once '../app/Views/components/navbar.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 border-top border-warning border-4">
                <div class="card-header bg-white pb-0 border-0">
                    <h3 class="text-dark fw-bold mt-3"><i class="fas fa-edit text-warning"></i> Edit Your Insight</h3>
                    <p class="text-muted">Made a typo? Update your suggestion below.</p>
                </div>
                <div class="card-body p-4">

                    <?php require_once '../app/Core/Flash.php'; ?>
                    <?php Flash::display('post_error'); ?>

                    <form action="<?= URLROOT ?>/post/update/<?= $data['post']->post_id ?>" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Event</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($data['post']->event_title) ?>" readonly disabled>
                            <div class="form-text text-muted">You cannot change the event category once posted.</div>
                        </div>

                        <div class="mb-4">
                            <label for="insight" class="form-label fw-bold text-dark">Your Opinion / Insight</label>
                            <textarea class="form-control" id="insight" name="insight" rows="6" required><?= htmlspecialchars($data['post']->insight) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= URLROOT ?>/dashboard" class="btn btn-outline-secondary">Cancel</a>
                            <div>
                                <a href="<?= URLROOT ?>/post/delete/<?= $data['post']->post_id ?>" class="btn btn-outline-danger me-2" onclick="return confirm('Are you sure you want to delete this insight? This cannot be undone.')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                <button type="submit" class="btn btn-warning fw-bold px-4 text-dark">
                                    <i class="fas fa-save me-1"></i> Update Insight
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>