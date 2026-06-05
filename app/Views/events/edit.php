<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-success fw-bold mb-4"><i class="fas fa-edit"></i> Edit Event</h2>
                    
                    <form action="<?= URLROOT ?>/event/update/<?= $data['event']->event_id ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Event Title</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['event']->title) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($data['event']->description) ?></textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date & Time</label>
                                <input type="datetime-local" name="event_date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['event']->event_date)) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Upcoming" <?= $data['event']->status == 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                                    <option value="Ongoing" <?= $data['event']->status == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                    <option value="Finished" <?= $data['event']->status == 'Finished' ? 'selected' : '' ?>>Finished</option>
                                </select>
                            </div>
                        </div>

                        <div class="note-box text-dark p-3 rounded-3 mb-4" style="background-color: #fff3cd; border: 1px solid #ffeeba;">
                            <i class="fas fa-info-circle text-warning"></i> <strong>Note:</strong> Changing the status to "Finished" will automatically allow students who registered to submit feedback/surveys.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= URLROOT ?>/admin/events" class="btn btn-light px-4 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-warning fw-bold px-4 rounded-pill">Update Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>