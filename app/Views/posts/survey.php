<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5 mb-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-success text-white py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold">Post-Event Survey</h4>
        </div>
        <div class="card-body p-4 p-md-5 bg-white">
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($data['event']->title ?? 'Event') ?></h5>
            <p class="text-muted small mb-4">Please let us know your thoughts about this event.</p>

            <form action="<?= URLROOT ?>/post/submitSurvey" method="POST">
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($data['event']->event_id ?? '') ?>">
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Rate the event (1-5)</label>
                    <select name="rating" class="form-select p-3 bg-light border-0" required>
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Very Good</option>
                        <option value="3">3 - Good</option>
                        <option value="2">2 - Fair</option>
                        <option value="1">1 - Poor</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Feedback / Suggestions</label>
                    <textarea name="feedback" class="form-control p-3 bg-light border-0" rows="5" placeholder="Tell us what you liked or what could be improved..." required></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-pill">Submit Feedback</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>