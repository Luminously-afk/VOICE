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
                <h3 class="fw-bold text-success mb-0"><i class="far fa-calendar-alt me-2"></i> List of Events</h3>
                <a href="<?= URLROOT ?>/admin/createEvent" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">+ Create New Event</a>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="text-start py-3 ps-4">Event Title</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['events'])): foreach($data['events'] as $event): ?>
                                    <tr>
                                        <td class="text-start fw-bold ps-4"><?= htmlspecialchars(html_entity_decode($event->title ?? '', ENT_QUOTES), ENT_QUOTES) ?></td>
                                        <td><?= date('M d, Y - h:i A', strtotime($event->event_date)) ?></td>
                                        <td>
                                            <span class="badge bg-secondary rounded-pill px-3 py-2"><?= htmlspecialchars($event->status) ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= URLROOT ?>/admin/viewEvent/<?= $event->event_id ?>" class="text-primary me-3 fs-5" title="View Details"><i class="fas fa-eye"></i></a>
                                            <a href="<?= URLROOT ?>/admin/editEvent/<?= $event->event_id ?>" class="text-success me-3 fs-5" title="Edit Event"><i class="fas fa-edit"></i></a>
                                            <a href="<?= URLROOT ?>/admin/deleteEvent/<?= $event->event_id ?>" class="text-danger fs-5" title="Delete Event" onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" class="py-5 text-muted">No events found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>