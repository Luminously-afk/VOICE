<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 border-top border-success border-4">
                <div class="card-header bg-white pb-0 border-0 text-center">
                    <h3 class="text-success fw-bold mt-4"><i class="fas fa-clipboard-check"></i> Post-Event Survey</h3>
                    <p class="text-muted">We want to hear your thoughts about <strong><?= htmlspecialchars($data['event']->title ?? 'this event') ?></strong>.</p>
                </div>
                <div class="card-body p-4">
                    <?php require_once '../app/Core/Flash.php'; ?>
                    <?php Flash::display('survey_message'); ?>

                    <form action="<?= URLROOT ?>/survey/submit" method="POST">
                        <input type="hidden" name="event_id" value="<?= $data['event']->event_id ?? '' ?>">
                        
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold d-block fs-5 text-dark">How would you rate your experience?</label>
                            <div class="rating-stars fs-1 text-warning mb-2" style="cursor: pointer;">
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <input type="hidden" name="rating" id="rating-value" value="0" required>
                            <small class="text-muted">Click the stars to rate (1-5)</small>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control border-success" placeholder="Leave a comment here" id="feedback" name="feedback" style="height: 150px" required></textarea>
                            <label for="feedback" class="text-success">What did you like? What could be improved?</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold" style="background-color: #006400; border: none;">
                                Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const stars = document.querySelectorAll('.rating-stars i');
    const ratingInput = document.getElementById('rating-value');

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            ratingInput.value = index + 1;
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });
    });
});
</script>

<?php require_once '../app/Views/layout/footer.php'; ?>