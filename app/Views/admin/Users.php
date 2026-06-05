<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5 mb-5" style="max-width: 1200px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success mb-0"><i class="fas fa-users-cog me-2"></i> Manage Users</h2>
        <div class="d-flex align-items-center">
            <span class="badge bg-dark fs-6 py-2 px-3 me-3">Total Accounts: <?= count($data['users']) ?></span>
            <a href="<?= URLROOT ?>/admin/dashboard" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Back</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th class="py-3 ps-4">Student Number</th>
                            <th class="py-3">Full Name</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Role</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['users'])): foreach($data['users'] as $user): ?>
                            <tr>
                                <td class="fw-bold ps-4"><?= htmlspecialchars($user->student_num) ?></td>
                                <td><?= htmlspecialchars($user->name) ?></td>
                                <td><?= htmlspecialchars($user->email) ?></td>
                                <td>
                                    <?php if($user->role === 'Admin'): ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2">Student</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= URLROOT ?>/admin/editUser/<?= $user->user_id ?>" class="btn btn-outline-primary btn-sm me-2"><i class="fas fa-edit"></i></a>
                                    <?php if($user->role !== 'Admin'): ?>
                                        <a href="<?= URLROOT ?>/admin/deleteUser/<?= $user->user_id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>