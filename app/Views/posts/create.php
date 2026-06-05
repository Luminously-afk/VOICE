<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 border-top border-success border-4 rounded-4">
                <div class="card-header bg-white pb-0 border-0 pt-4">
                    <h3 class="text-success fw-bold mt-3"><i class="fas fa-pen-square"></i> Share Your Insight</h3>
                    <p class="text-muted">Your voice matters. Suggest improvements or share your thoughts about an event.</p>
                </div>
                <div class="card-body p-4">

                    <?php require_once '../app/Core/Flash.php'; ?>
                    <?php Flash::display('post_error'); ?>

                    <form action="<?= URLROOT ?>/post/store" method="POST">
                        <div class="mb-4">
                            <label for="event_id" class="form-label fw-bold text-dark">Which event is this for?</label>
                            <select class="form-select border-success rounded-3" id="event_id" name="event_id" required>
                                <option value="" disabled selected>Select an event from the list...</option>
                                <option value="generalized">Generalized Insight (No specific event)</option>
                                <?php if(!empty($data['events'])): ?>
                                    <?php foreach($data['events'] as $event): ?>
                                        <option value="<?= $event->event_id ?>">
                                            <?= htmlspecialchars($event->title) ?> (<?= htmlspecialchars($event->status) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text text-muted">Select the specific event your suggestion is related to, or choose Generalized Insight.</div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-dark">Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success rounded-3" id="title" name="title" placeholder="Give your insight a catchy title" required>
                        </div>

                        <div class="mb-4">
                            <label for="insight" class="form-label fw-bold text-dark">Body text (optional)</label>
                            <textarea class="form-control border-success rounded-3" id="insight" name="insight" rows="6" placeholder="Share your thoughts here..."></textarea>
                            <div class="form-text text-muted">Be respectful and constructive. Note: Other users can upvote or downvote your insight once approved.</div>
                        </div>

                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input border-success shadow-none" type="checkbox" role="switch" id="is_anonymous" name="is_anonymous" value="1">
                            <label class="form-check-label fw-bold text-dark" for="is_anonymous">Post as Anonymous</label>
                            <div class="form-text text-muted">Other students will see 'Anonymous Student'. Admin will still see your name.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="<?= URLROOT ?>/post/index" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-success fw-bold px-5 rounded-pill shadow-sm" style="background-color: #006400; border: none;">
                                <i class="fas fa-paper-plane me-1"></i> Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>