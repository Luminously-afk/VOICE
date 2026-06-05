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
                <h3 class="fw-bold text-success mb-0"><i class="fas fa-calendar-plus me-2"></i> Create New Event</h3>
                <a href="<?= URLROOT ?>/admin/events" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Back</a>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    <form action="<?= URLROOT ?>/admin/createEvent" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Event Title</label>
                            <input type="text" name="title" class="form-control p-3 bg-light border-0 rounded-3" placeholder="e.g., College Intramurals 2026" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Category</label>
                            <select name="category" class="form-select p-3 bg-light border-0 rounded-3" required>
                                <option value="Academics">Academics</option>
                                <option value="Sports">Sports</option>
                                <option value="Arts & Culture">Arts & Culture</option>
                                <option value="General" selected>General</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Description</label>
                            <textarea name="description" class="form-control p-3 bg-light border-0 rounded-3" rows="4" placeholder="Brief details about the event..." required></textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Start Date & Time</label>
                                <input type="datetime-local" name="event_date" class="form-control p-3 bg-light border-0 rounded-3" required>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label fw-bold text-muted small">End Date & Time</label>
                                <input type="datetime-local" name="end_date" class="form-control p-3 bg-light border-0 rounded-3" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm fs-5">Publish Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>