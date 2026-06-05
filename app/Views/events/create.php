<?php 
require_once '../app/Core/AdminGuard.php';
AdminGuard::protect();

require_once '../app/Views/layout/header.php'; 
require_once '../app/Views/components/navbar.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 border-top border-success border-4">
                <div class="card-header bg-white pb-0 border-0">
                    <h3 class="text-success fw-bold mt-3"><i class="fas fa-plus-circle"></i> Create New Event</h3>
                    <p class="text-muted">Fill out the details below to broadcast a new campus event.</p>
                </div>
                <div class="card-body p-4">
                    <?php require_once '../app/Core/Flash.php'; ?>
                    <?php Flash::display('event_error'); ?>

                    <form action="<?= URLROOT ?>/event/store" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Foundation Day" required>
                            <label for="title">Event Title</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" placeholder="Describe the event" style="height: 120px" required></textarea>
                            <label for="description">Event Description</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
                                    <label for="event_date">Date & Time</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="status" name="status" aria-label="Event Status">
                                        <option value="Upcoming" selected>Upcoming</option>
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Finished">Finished</option>
                                    </select>
                                    <label for="status">Initial Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= URLROOT ?>/admin" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-success fw-bold" style="background-color: #006400; border: none;">
                                <i class="fas fa-save"></i> Publish Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>