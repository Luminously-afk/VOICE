<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<div class="container-fluid mt-0" style="max-width: 1300px;">
    <div class="row position-relative">
        
        <div class="col-md-3 d-none d-md-block border-end pt-3 custom-fixed-left">
            <a href="<?= $isLoggedIn ? URLROOT . '/post/create' : URLROOT . '/auth/login' ?>" class="btn btn-success rounded-pill w-100 mb-4 fw-bold shadow-sm py-2">
                <i class="fas fa-plus"></i> Share an Insight
            </a>
            <div class="list-group list-group-flush shadow-sm rounded-4 overflow-hidden mb-3">
                <div class="list-group-item bg-light fw-bold border-0"><i class="fas fa-filter text-success"></i> Filter by Event</div>
                <?php $cf = ($data['feed_type'] ?? 'popular') == 'popular' ? 'popular' : 'latest'; ?>
                <a href="<?= $isLoggedIn ? URLROOT . '/post/' . $cf : URLROOT . '/auth/login' ?>" class="list-group-item list-group-item-action border-0 <?= !isset($_GET['event_id']) ? 'active bg-success text-white' : '' ?>">All Events</a>
                <?php if(!empty($data['events'])): foreach($data['events'] as $e): ?>
                    <a href="<?= $isLoggedIn ? URLROOT . '/post/' . $cf . '?event_id=' . $e->event_id : URLROOT . '/auth/login' ?>" class="list-group-item list-group-item-action border-0 <?= (isset($_GET['event_id']) && $_GET['event_id'] == $e->event_id) ? 'active bg-success text-white' : '' ?>">
                        <?= htmlspecialchars($e->title) ?>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="col-md-6 p-0 border-end custom-scroll-center">
            <div class="sticky-top bg-white border-bottom shadow-sm" style="top: 0; z-index: 99;">
                <div class="d-flex text-center">
                    <a href="<?= URLROOT ?>/post/popular<?= isset($_GET['event_id']) ? '?event_id='.$_GET['event_id'] : '' ?>" class="flex-fill p-3 nav-link border-end <?= ($data['feed_type'] ?? 'popular') == 'popular' ? 'active-tab fw-bold text-success' : 'text-muted' ?>" style="text-decoration: none;">Popular</a>
                    <a href="<?= URLROOT ?>/post/latest<?= isset($_GET['event_id']) ? '?event_id='.$_GET['event_id'] : '' ?>" class="flex-fill p-3 nav-link <?= ($data['feed_type'] ?? '') == 'new' ? 'active-tab fw-bold text-success' : 'text-muted' ?>" style="text-decoration: none;">New</a>
                </div>
            </div>
            <div class="p-3">
                <?php require_once '../app/Core/Flash.php'; Flash::display('post_message'); ?>
                <div id="posts-container">
                    <?php if (empty($data['posts'])): ?>
                        <div class="text-center p-5 text-muted"><i class="fas fa-comment-slash fs-1 mb-3"></i><p>No insights found.</p></div>
                    <?php else: foreach ($data['posts'] as $p): ?>
                        <?php 
                            $upClass = (isset($p->user_vote) && $p->user_vote === 'up') ? 'text-success fw-bold' : 'text-secondary';
                            $downClass = (isset($p->user_vote) && $p->user_vote === 'down') ? 'text-danger fw-bold' : 'text-secondary';
                        ?>
                        <div class="card mb-3 border-0 border-bottom rounded-0 position-relative">
                            <div class="p-3">
                                <div class="mb-2">
                                    <?php if(!empty($p->event_title)): ?>
                                        <span class="badge bg-dark rounded-pill me-2"><?= htmlspecialchars($p->event_title) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill me-2">Generalized Insight</span>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        Posted by <?= (int)$p->is_anonymous === 1 ? 'Anonymous Student' : htmlspecialchars($p->student_name) ?> • <?= date('M j, Y', strtotime($p->created_at)) ?>
                                    </small>
                                </div>
                                
                                <h5 class="fw-bold mb-2 text-dark"><?= htmlspecialchars(html_entity_decode($p->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?></h5>
                                
                                <?php if(!empty($p->insight)): ?>
                                    <p class="mb-3 text-dark" style="font-size: 0.95rem;"><?= nl2br(htmlspecialchars(html_entity_decode($p->insight, ENT_QUOTES), ENT_QUOTES)) ?></p>
                                <?php endif; ?>

                                <div class="d-flex align-items-center gap-3 position-relative" style="z-index: 20;">
                                    <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1" style="width: fit-content;">
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
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-bold" style="font-size: 0.75rem;">Score: <span id="score-<?= $p->post_id ?>"><?= $p->vote_score ?? 0 ?></span></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-3 d-none d-lg-block pt-3 ps-4 custom-fixed-right">
            <div class="bg-light rounded-4 p-3 shadow-sm border mb-4">
                <h5 class="fw-bold mb-3"><i class="far fa-calendar-alt text-success"></i> Active & Upcoming Events</h5>
                <?php if(!empty($data['upcoming_events'])): foreach($data['upcoming_events'] as $ue): ?>
                    <div class="mb-3 pb-2 border-bottom">
                        <small class="text-success fw-bold">
                            <?= date('M j, Y', strtotime($ue->event_date)) ?>
                            <?= $ue->status === 'Ongoing' ? '<span class="badge bg-danger ms-1" style="font-size: 0.6rem;">Ongoing</span>' : '' ?>
                        </small>
                        <h6 class="mb-1 fw-bold text-dark">
                            <?= htmlspecialchars(html_entity_decode($ue->title, ENT_QUOTES), ENT_QUOTES) ?>
                        </h6>
                        <p id="desc-<?= $ue->event_id ?>" class="text-muted mb-0" style="font-size: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: all 0.3s ease;">
                            <?= htmlspecialchars(html_entity_decode($ue->description ?? '', ENT_QUOTES), ENT_QUOTES) ?>
                        </p>
                        <?php if(!empty($ue->description) && strlen($ue->description) > 60): ?>
                            <a href="javascript:void(0);" onclick="toggleEventDesc(<?= $ue->event_id ?>)" id="btn-desc-<?= $ue->event_id ?>" class="text-success fw-bold" style="font-size: 0.7rem; text-decoration: none;">View more</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; else: ?>
                    <p class="text-muted small italic">No active or upcoming events.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleEventDesc(id) {
        let desc = document.getElementById('desc-' + id);
        let btn = document.getElementById('btn-desc-' + id);
        if (desc.style.webkitLineClamp === '2' || desc.style.webkitLineClamp === 2) {
            desc.style.webkitLineClamp = 'unset';
            btn.innerText = 'View less';
        } else {
            desc.style.webkitLineClamp = '2';
            btn.innerText = 'View more';
        }
    }

    function handleVoteClick(event, postId, type, isLoggedIn) {
        event.preventDefault();
        event.stopPropagation();
        if (!isLoggedIn) {
            window.location.href = '<?= URLROOT ?>/auth/login';
            return;
        }
        
        let upBtn = document.getElementById('up-btn-' + postId);
        let downBtn = document.getElementById('down-btn-' + postId);
        
        upBtn.disabled = true;
        downBtn.disabled = true;
        
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
                    upBtn.classList.remove('text-secondary');
                    upBtn.classList.add('text-success', 'fw-bold');
                } else if (data.user_vote === 'down') {
                    downBtn.classList.remove('text-secondary');
                    downBtn.classList.add('text-danger', 'fw-bold');
                }
            } else if (data.redirect) {
                window.location.href = '<?= URLROOT ?>/auth/login';
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            upBtn.disabled = false;
            downBtn.disabled = false;
        });
    }
</script>

<style>
    .custom-fixed-left {
        position: fixed;
        top: 56px;
        left: calc((100vw - 1300px) / 2);
        width: calc(1300px * 0.25);
        height: calc(100vh - 56px);
        overflow-y: auto;
        z-index: 10;
    }
    .custom-scroll-center {
        margin-left: 25%;
        min-height: calc(100vh - 56px);
    }
    .custom-fixed-right {
        position: fixed;
        top: 56px;
        right: calc((100vw - 1300px) / 2);
        width: calc(1300px * 0.25);
        height: calc(100vh - 56px);
        overflow-y: auto;
        z-index: 10;
    }
    .custom-fixed-left::-webkit-scrollbar,
    .custom-fixed-right::-webkit-scrollbar {
        width: 4px;
    }
    .custom-fixed-left::-webkit-scrollbar-thumb,
    .custom-fixed-right::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 10px;
    }
    @media (max-width: 1300px) {
        .custom-fixed-left { left: 0; width: 25%; }
        .custom-fixed-right { right: 0; width: 25%; }
    }
    @media (max-width: 991px) {
        .custom-fixed-right { display: none !important; }
        .custom-scroll-center { width: 75%; }
    }
    @media (max-width: 767px) {
        .custom-fixed-left { display: none !important; }
        .custom-scroll-center { margin-left: 0; width: 100%; }
    }
    .active-tab { border-bottom: 4px solid #006400; }
    .vote-btn-element { position: relative; z-index: 30; cursor: pointer; transition: color 0.2s; }
    .vote-btn-element:hover { color: #006400 !important; }
</style>
<?php require_once '../app/Views/layout/footer.php'; ?>