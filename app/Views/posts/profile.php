<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<div class="container mt-4" style="max-width: 800px;">
    
    <div class="text-center mb-4">
        <div class="d-inline-block bg-success text-white rounded-circle p-4 mb-2 shadow">
            <i class="fas fa-user-graduate fa-3x"></i>
        </div>
        <h3 class="fw-bold mb-1"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Student Profile') ?></h3>
        <p class="text-muted small mb-3">Activity Log</p>
        <a href="<?= URLROOT ?>/post/editProfile" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="fas fa-user-edit me-1"></i> Edit Profile
        </a>
    </div>

    <?php require_once '../app/Core/Flash.php'; Flash::display('post_message'); ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="d-flex text-center bg-light border-bottom">
            <a href="<?= URLROOT ?>/post/profile?tab=posts" class="flex-fill p-3 nav-link <?= ($data['active_tab'] === 'posts') ? 'active-tab fw-bold text-success bg-white' : 'text-muted' ?>" style="text-decoration: none;">
                <i class="fas fa-pen-nib me-1"></i> My Insights
            </a>
            <a href="<?= URLROOT ?>/post/profile?tab=upvoted" class="flex-fill p-3 border-start nav-link <?= ($data['active_tab'] === 'upvoted') ? 'active-tab fw-bold text-success bg-white' : 'text-muted' ?>" style="text-decoration: none;">
                <i class="fas fa-arrow-up me-1"></i> Upvoted
            </a>
            <a href="<?= URLROOT ?>/post/profile?tab=downvoted" class="flex-fill p-3 border-start nav-link <?= ($data['active_tab'] === 'downvoted') ? 'active-tab fw-bold text-success bg-white' : 'text-muted' ?>" style="text-decoration: none;">
                <i class="fas fa-arrow-down me-1"></i> Downvoted
            </a>
        </div>
    </div>

    <div>
        <?php if (empty($data['posts'])): ?>
            <div class="text-center p-5 text-muted bg-white rounded-4 shadow-sm">
                <i class="fas fa-box-open fs-1 mb-3"></i>
                <p>No activity found for this tab.</p>
            </div>
        <?php else: foreach ($data['posts'] as $p): ?>
            <?php 
                $upClass = (isset($p->user_vote) && $p->user_vote === 'up') ? 'text-success fw-bold' : 'text-secondary';
                $downClass = (isset($p->user_vote) && $p->user_vote === 'down') ? 'text-danger fw-bold' : 'text-secondary';
            ?>
            <div class="card mb-3 border-0 shadow-sm rounded-4 position-relative">
                <div class="p-4">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <?php if(!empty($p->event_title)): ?>
                                <span class="badge bg-dark rounded-pill me-2"><?= htmlspecialchars($p->event_title) ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill me-2">Generalized Insight</span>
                            <?php endif; ?>
                            
                            <?php if(isset($p->status)): ?>
                                <?php if(strtolower($p->status) === 'pending'): ?>
                                    <span class="badge bg-warning text-dark rounded-pill">Pending Review</span>
                                <?php elseif(strtolower($p->status) === 'rejected'): ?>
                                    <span class="badge bg-danger rounded-pill">Rejected</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted"><?= date('M j, Y', strtotime($p->created_at)) ?></small>
                    </div>
                    
                    <h5 class="fw-bold mb-2 text-dark"><?= htmlspecialchars(html_entity_decode($p->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?></h5>
                    
                    <?php if(!empty($p->insight)): ?>
                        <p class="mb-3 text-dark" style="font-size: 0.95rem;"><?= nl2br(htmlspecialchars(html_entity_decode($p->insight, ENT_QUOTES), ENT_QUOTES)) ?></p>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1" style="width: fit-content; z-index: 20; position: relative;">
                            <button type="button" id="up-btn-<?= $p->post_id ?>" class="btn btn-link p-0 border-0 align-middle vote-btn-element <?= $upClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'up', <?= $isLoggedIn ? 'true' : 'false' ?>)" style="line-height: 1; text-decoration: none;">
                                <i class="fas fa-arrow-up fs-6"></i>
                            </button>
                            <span class="mx-2 small text-dark fw-bold" id="up-count-<?= $p->post_id ?>" style="user-select: none;"><?= $p->upvotes_count ?? 0 ?></span>
                            
                            <div class="text-muted mx-1" style="font-size: 0.8rem;">|</div>
                            
                            <button type="button" id="down-btn-<?= $p->post_id ?>" class="btn btn-link p-0 border-0 align-middle vote-btn-element <?= $downClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'down', <?= $isLoggedIn ? 'true' : 'false' ?>)" style="line-height: 1; text-decoration: none;">
                                <i class="fas fa-arrow-down fs-6"></i>
                            </button>
                            <span class="mx-2 small text-dark fw-bold" id="down-count-<?= $p->post_id ?>" style="user-select: none;"><?= $p->downvotes_count ?? 0 ?></span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success rounded-pill px-3 py-2 fw-bold" style="font-size: 0.75rem;">Score: <span id="score-<?= $p->post_id ?>"><?= $p->vote_score ?? 0 ?></span></span>
                            
                            <?php if ($data['active_tab'] === 'posts'): ?>
                                <div style="z-index: 20; position: relative;">
                                    <a href="<?= URLROOT ?>/post/editPost/<?= $p->post_id ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1"><i class="fas fa-edit"></i></a>
                                    <a href="<?= URLROOT ?>/post/deletePost/<?= $p->post_id ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Delete this insight? This action cannot be undone.');"><i class="fas fa-trash"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<script>
    function handleVoteClick(event, postId, type, isLoggedIn) {
        event.preventDefault();
        event.stopPropagation();
        if (!isLoggedIn) { window.location.href = '<?= URLROOT ?>/auth/login'; return; }
        
        let upBtn = document.getElementById('up-btn-' + postId);
        let downBtn = document.getElementById('down-btn-' + postId);
        
        upBtn.disabled = true; downBtn.disabled = true;
        
        fetch('<?= URLROOT ?>/post/vote/' + postId + '/' + type, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('score-' + postId).innerText = data.score;
                document.getElementById('up-count-' + postId).innerText = data.upvotes;
                document.getElementById('down-count-' + postId).innerText = data.downvotes;
                
                upBtn.className = "btn btn-link p-0 border-0 align-middle vote-btn-element text-secondary";
                downBtn.className = "btn btn-link p-0 border-0 align-middle vote-btn-element text-secondary";
                
                if (data.user_vote === 'up') {
                    upBtn.classList.remove('text-secondary'); upBtn.classList.add('text-success', 'fw-bold');
                } else if (data.user_vote === 'down') {
                    downBtn.classList.remove('text-secondary'); downBtn.classList.add('text-danger', 'fw-bold');
                }
            } else if (data.redirect) { window.location.href = '<?= URLROOT ?>/auth/login'; }
        })
        .catch(err => console.error(err))
        .finally(() => { upBtn.disabled = false; downBtn.disabled = false; });
    }
</script>

<style>
    .active-tab { border-bottom: 3px solid #006400; }
    .vote-btn-element { position: relative; z-index: 30; cursor: pointer; transition: color 0.2s; }
    .vote-btn-element:hover { color: #006400 !important; }
</style>
<?php require_once '../app/Views/layout/footer.php'; ?>