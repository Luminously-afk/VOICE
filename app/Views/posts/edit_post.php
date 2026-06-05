<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-4" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-success mb-0"><i class="fas fa-edit me-2"></i> Edit Insight</h3>
        <a href="<?= URLROOT ?>/post/profile" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Cancel</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <form action="<?= URLROOT ?>/post/updatePost/<?= $data['post']->post_id ?>" method="POST">
            
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Link to Event</label>
                <select name="event_id" class="form-select rounded-3">
                    <option value="generalized" <?= is_null($data['post']->event_id) ? 'selected' : '' ?>>Generalized Insight (No specific event)</option>
                    <?php if(!empty($data['events'])): foreach($data['events'] as $e): ?>
                        <option value="<?= $e->event_id ?>" <?= ((int)$data['post']->event_id === (int)$e->event_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars(html_entity_decode($e->title ?? '', ENT_QUOTES), ENT_QUOTES) ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Title</label>
                <input type="text" name="title" class="form-control rounded-3 py-2 text-dark" value="<?= htmlspecialchars(html_entity_decode($data['post']->title ?? '', ENT_QUOTES), ENT_QUOTES) ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Your Insight / Suggestion</label>
                <textarea name="insight" class="form-control rounded-3 text-dark" rows="6" required><?= htmlspecialchars(html_entity_decode($data['post']->insight ?? '', ENT_QUOTES), ENT_QUOTES) ?></textarea>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonSwitch" <?= (int)$data['post']->is_anonymous === 1 ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold text-dark" for="anonSwitch">Post anonymously</label>
            </div>

            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">Save Changes & Resubmit</button>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>