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
                <h3 class="fw-bold text-success mb-0"><i class="fas fa-edit me-2"></i> Edit Event</h3>
                <a href="<?= URLROOT ?>/admin/events" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Back</a>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    <form action="<?= URLROOT ?>/admin/editEvent/<?= $data['event']->event_id ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Event Title</label>
                            <input type="text" name="title" class="form-control p-3 bg-light border-0 rounded-3" value="<?= htmlspecialchars($data['event']->title) ?>" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Category</label>
                                <select name="category" class="form-select p-3 bg-light border-0 rounded-3" required>
                                    <option value="Academics" <?= $data['event']->category == 'Academics' ? 'selected' : '' ?>>Academics</option>
                                    <option value="Sports" <?= $data['event']->category == 'Sports' ? 'selected' : '' ?>>Sports</option>
                                    <option value="Arts & Culture" <?= $data['event']->category == 'Arts & Culture' ? 'selected' : '' ?>>Arts & Culture</option>
                                    <option value="General" <?= $data['event']->category == 'General' ? 'selected' : '' ?>>General</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label fw-bold text-muted small">Status</label>
                                <select name="status" class="form-select p-3 bg-light border-0 rounded-3" required>
                                    <option value="Upcoming" <?= $data['event']->status == 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                                    <option value="Ongoing" <?= $data['event']->status == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                    <option value="Finished" <?= $data['event']->status == 'Finished' ? 'selected' : '' ?>>Finished</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Description</label>
                            <textarea name="description" class="form-control p-3 bg-light border-0 rounded-3" rows="4" required><?= htmlspecialchars($data['event']->description) ?></textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Start Date & Time</label>
                                <input type="datetime-local" name="event_date" class="form-control p-3 bg-light border-0 rounded-3" value="<?= date('Y-m-d\TH:i', strtotime($data['event']->event_date)) ?>" required>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label fw-bold text-muted small">End Date & Time</label>
                                <input type="datetime-local" name="end_date" class="form-control p-3 bg-light border-0 rounded-3" value="<?= date('Y-m-d\TH:i', strtotime($data['event']->end_date)) ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm fs-5">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>