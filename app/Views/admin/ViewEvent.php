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
                <h3 class="fw-bold text-success mb-0"><i class="fas fa-calendar-check me-2"></i> Event Details</h3>
                <a href="<?= URLROOT ?>/admin/events" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">Back</a>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-5">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-2"><?= htmlspecialchars(html_entity_decode($data['event']->title ?? '', ENT_QUOTES), ENT_QUOTES) ?></h4>
                    <div class="d-flex gap-3 mb-3">
                        <span class="badge bg-success rounded-pill px-3 py-2"><?= htmlspecialchars($data['event']->category) ?></span>
                        <span class="badge bg-secondary rounded-pill px-3 py-2"><?= htmlspecialchars($data['event']->status) ?></span>
                    </div>
                    <div class="text-muted mb-0"><?= html_entity_decode($data['event']->description ?? '', ENT_QUOTES) ?></div>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Survey Results & Feedback</h5>
            <div class="row mb-5">
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-4">Overall Ratings (Anonymous)</h6>
                            <?php 
                            $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            $totalResponses = count($data['responses'] ?? []);
                            if ($totalResponses > 0) {
                                foreach ($data['responses'] as $r) {
                                    if (isset($ratingCounts[$r->rating])) {
                                        $ratingCounts[$r->rating]++;
                                    }
                                }
                            }
                            $avgRating = 0;
                            if ($totalResponses > 0) {
                                $sum = 0;
                                foreach($ratingCounts as $stars => $count) { $sum += ($stars * $count); }
                                $avgRating = number_format($sum / $totalResponses, 1);
                            }
                            ?>
                            <div class="text-center mb-4">
                                <h1 class="display-4 fw-bold text-success mb-0"><?= $avgRating ?></h1>
                                <div class="text-warning fs-5">
                                    <?php for($i=1; $i<=5; $i++) echo $i <= round($avgRating) ? '★' : '☆'; ?>
                                </div>
                                <small class="text-muted"><?= $totalResponses ?> total responses</small>
                            </div>

                            <?php for($i = 5; $i >= 1; $i--): 
                                $pct = $totalResponses > 0 ? ($ratingCounts[$i] / $totalResponses) * 100 : 0;
                            ?>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2 text-muted fw-bold" style="width: 20px;"><?= $i ?></span>
                                    <i class="fas fa-star text-warning me-2 small"></i>
                                    <div class="progress flex-grow-1" style="height: 10px; background-color: #e9ecef;">
                                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: <?= $pct ?>%"></div>
                                    </div>
                                    <span class="ms-2 text-muted small" style="width: 30px; text-align: right;"><?= $ratingCounts[$i] ?></span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <h6 class="fw-bold mb-4">Anonymous Suggestions</h6>
                            <div class="overflow-auto pe-2" style="max-height: 250px;">
                                <?php if($totalResponses > 0): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach($data['responses'] as $resp): ?>
                                            <?php if(!empty(trim($resp->feedback))): ?>
                                                <li class="list-group-item px-0 py-3 border-bottom text-muted fst-italic">
                                                    "<?= nl2br(htmlspecialchars(html_entity_decode($resp->feedback ?? '', ENT_QUOTES), ENT_QUOTES)) ?>"
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <div class="text-center text-muted my-auto py-5">No feedback available yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Registered Students (Attendance)</h5>
            <div class="card shadow-sm border-0 rounded-3 mb-5">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="py-3 ps-4">Student Number</th>
                                    <th class="py-3">Full Name</th>
                                    <th class="py-3">Time Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['registrations'])): foreach($data['registrations'] as $reg): ?>
                                    <tr>
                                        <td class="fw-bold ps-4"><?= htmlspecialchars($reg->student_num) ?></td>
                                        <td><?= htmlspecialchars($reg->name) ?></td>
                                        <td class="text-muted small"><?= date('M d, Y - h:i A', strtotime($reg->attended_at)) ?></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">No registered students yet.</td>
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