<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5 mb-5" style="max-width: 800px;">
    <div class="card shadow-sm border-0 border-top border-success border-4 rounded-3">
        <div class="card-header bg-white pb-0 border-0 pt-4">
            <h3 class="text-success fw-bold"><i class="fas fa-user-edit"></i> Edit User</h3>
        </div>
        <div class="card-body p-4">
            <form action="<?= URLROOT ?>/admin/editUser/<?= $data['user']->user_id ?>" method="POST">
                <div class="mb-3">
                    <label for="student_num" class="form-label fw-bold">Student Number</label>
                    <input type="text" class="form-control" id="student_num" name="student_num" value="<?= htmlspecialchars($data['user']->student_num) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($data['user']->name) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($data['user']->email) ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="role" class="form-label fw-bold">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Student" <?= $data['user']->role === 'Student' ? 'selected' : '' ?>>Student</option>
                        <option value="Admin" <?= $data['user']->role === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-between pt-3">
                    <a href="<?= URLROOT ?>/admin/users" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-5" style="background-color: #006400; border: none;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>