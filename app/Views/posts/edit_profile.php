<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-success mb-0"><i class="fas fa-user-cog me-2"></i> Edit Profile</h3>
        <a href="<?= URLROOT ?>/post/profile" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Back</a>
    </div>

    <?php require_once '../app/Core/Flash.php'; Flash::display('post_message'); ?>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <form action="<?= URLROOT ?>/post/updateProfile" method="POST">
            
            <div class="mb-3">
                <label class="form-label fw-bold text-muted">Student Number (Read-only)</label>
                <input type="text" class="form-control bg-light rounded-3 text-secondary border-0" value="<?= htmlspecialchars($data['user']->student_num) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-muted">Campus Email Address (Read-only)</label>
                <input type="text" class="form-control bg-light rounded-3 text-secondary border-0" value="<?= htmlspecialchars($data['user']->email) ?>" readonly>
            </div>

            <hr class="my-4 text-muted">

            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Full Name</label>
                <input type="text" name="name" class="form-control rounded-3 py-2 text-dark" value="<?= htmlspecialchars($data['user']->name) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-dark">New Password</label>
                <input type="password" name="password" class="form-control rounded-3 py-2 text-dark" placeholder="Leave blank to keep current password">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control rounded-3 py-2 text-dark" placeholder="Confirm your new password">
            </div>

            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">Update Profile Details</button>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>