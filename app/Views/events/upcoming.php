<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success fw-bold"><i class="fas fa-calendar-alt"></i> Upcoming Campus Events</h2>
        
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') : ?>
            <a href="<?= URLROOT ?>/event/create" class="btn btn-success fw-bold shadow-sm">
                <i class="fas fa-plus-circle"></i> Create New Event
            </a>
        <?php endif; ?>
    </div>
    
    <p class="text-muted mb-4">See what's happening around Olivarez College and register for upcoming activities.</p>

    <?php require_once '../app/Core/Flash.php'; ?>
    <?php Flash::display('event_message'); ?>

    <div class="row">
        <?php if (empty($data['events'])): ?>
            <div class="col-12">
                <div class="info-box text-center border py-5 bg-white shadow-sm rounded-4" style="border-style: dashed !important;">
                    <i class="fas fa-box-open fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted fw-bold">No upcoming events at the moment.</h5>
                    <p class="text-muted mb-0">Stay tuned for future campus activities!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($data['events'] as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 border-bottom border-success border-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success"><?= htmlspecialchars($event->status) ?></span>
                                <small class="text-muted"><i class="fas fa-clock"></i> <?= date('F j, Y', strtotime($event->event_date)) ?></small>
                            </div>
                            <h5 class="card-title fw-bold text-dark mt-3"><?= htmlspecialchars($event->title) ?></h5>
                            <p class="card-text text-muted" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($event->description) ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4 text-center">
                            <form action="<?= URLROOT ?>/register-event" method="POST" class="mb-2">
                                <input type="hidden" name="event_id" value="<?= $event->event_id ?>">
                                <button type="submit" class="btn btn-success w-100 fw-bold">
                                    <i class="fas fa-ticket-alt"></i> Register Now
                                </button>
                            </form>
                            
                            <a href="<?= URLROOT ?>/posts/create?event_id=<?= $event->event_id ?>" class="small text-success text-decoration-none fw-bold">
                                Got an early suggestion? Drop it here.
                            </a>

                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') : ?>
                                <hr>
                                <a href="<?= URLROOT ?>/event/edit/<?= $event->event_id ?>" class="btn btn-sm btn-outline-warning w-100 fw-bold">
                                    <i class="fas fa-edit"></i> Manage Event
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>