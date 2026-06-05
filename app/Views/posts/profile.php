<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
    <!-- Profile Header -->
    <div class="text-center mb-10">
        <div class="inline-block bg-voice-green text-voice-dark rounded-full p-6 mb-4 shadow-glow">
            <i class="fas fa-user-graduate text-5xl"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-100 mb-2"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Student Profile') ?></h3>
        <p class="text-gray-400 mb-6">Activity Log</p>
        <a href="<?= URLROOT ?>/post/editProfile" class="px-6 py-2.5 rounded-full border border-voice-green text-voice-green hover:bg-voice-green hover:text-voice-dark font-bold transition-colors">
            <i class="fas fa-user-edit mr-2"></i> Edit Profile
        </a>
    </div>

    <?php require_once '../app/Core/Flash.php'; Flash::display('post_message'); ?>

    <!-- Tabs -->
    <div class="bg-voice-card border border-voice-border rounded-xl flex mb-8 shadow-lg overflow-hidden">
        <a href="<?= URLROOT ?>/post/profile?tab=posts" class="flex-1 text-center py-4 border-r border-voice-border font-bold transition-colors <?= ($data['active_tab'] === 'posts') ? 'text-voice-green bg-[#1a1d21]' : 'text-gray-400 hover:bg-[#30363d]' ?>">
            <i class="fas fa-pen-nib mr-2"></i> My Insights
        </a>
        <a href="<?= URLROOT ?>/post/profile?tab=upvoted" class="flex-1 text-center py-4 border-r border-voice-border font-bold transition-colors <?= ($data['active_tab'] === 'upvoted') ? 'text-voice-green bg-[#1a1d21]' : 'text-gray-400 hover:bg-[#30363d]' ?>">
            <i class="fas fa-arrow-up mr-2"></i> Upvoted
        </a>
        <a href="<?= URLROOT ?>/post/profile?tab=downvoted" class="flex-1 text-center py-4 font-bold transition-colors <?= ($data['active_tab'] === 'downvoted') ? 'text-voice-green bg-[#1a1d21]' : 'text-gray-400 hover:bg-[#30363d]' ?>">
            <i class="fas fa-arrow-down mr-2"></i> Downvoted
        </a>
    </div>

    <!-- Content -->
    <div class="space-y-6 mb-10">
        <?php if (empty($data['posts'])): ?>
            <div class="bg-voice-card border border-voice-border rounded-xl p-10 text-center text-gray-500 shadow-lg">
                <i class="fas fa-box-open text-5xl mb-4 text-gray-600"></i>
                <p>No activity found for this tab.</p>
            </div>
        <?php else: foreach ($data['posts'] as $p): ?>
            <?php 
                $upClass = (isset($p->user_vote) && $p->user_vote === 'up') ? 'text-voice-green' : 'text-gray-500 hover:text-voice-green';
                $downClass = (isset($p->user_vote) && $p->user_vote === 'down') ? 'text-red-400' : 'text-gray-500 hover:text-red-400';
            ?>
            <div class="bg-voice-card border border-voice-border rounded-xl p-6 shadow-lg">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <?php if(!empty($p->event_title)): ?>
                            <span class="bg-[#1a1d21] border border-voice-border text-gray-300 text-xs px-3 py-1.5 rounded-full font-bold"><?= htmlspecialchars($p->event_title) ?></span>
                        <?php else: ?>
                            <span class="bg-[#1a1d21] border border-voice-border text-gray-500 text-xs px-3 py-1.5 rounded-full font-bold">Generalized Insight</span>
                        <?php endif; ?>
                        
                        <?php if(isset($p->status)): ?>
                            <?php if(strtolower($p->status) === 'pending'): ?>
                                <span class="bg-yellow-600 text-white text-xs px-3 py-1.5 rounded-full font-bold">Pending Review</span>
                            <?php elseif(strtolower($p->status) === 'rejected'): ?>
                                <span class="bg-red-600 text-white text-xs px-3 py-1.5 rounded-full font-bold">Rejected</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <span class="text-xs text-gray-500 font-medium"><?= date('M j, Y', strtotime($p->created_at)) ?></span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-100 mb-3"><?= htmlspecialchars(html_entity_decode($p->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?></h3>
                
                <?php if(!empty($p->insight)): ?>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4"><?= nl2br(htmlspecialchars(html_entity_decode($p->insight, ENT_QUOTES), ENT_QUOTES)) ?></p>
                <?php endif; ?>

                <div class="flex items-center justify-between border-t border-voice-border pt-4">
                    <div class="flex items-center bg-[#1a1d21] rounded-full px-3 py-1.5 border border-voice-border">
                        <button type="button" id="up-btn-<?= $p->post_id ?>" class="transition-colors <?= $upClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'up', <?= $isLoggedIn ? 'true' : 'false' ?>)">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <span class="mx-3 text-sm font-bold text-gray-300 w-4 text-center" id="score-<?= $p->post_id ?>"><?= $p->vote_score ?? 0 ?></span>
                        <button type="button" id="down-btn-<?= $p->post_id ?>" class="transition-colors <?= $downClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'down', <?= $isLoggedIn ? 'true' : 'false' ?>)">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </div>
                    
                    <?php if ($data['active_tab'] === 'posts'): ?>
                        <div class="flex items-center gap-2">
                            <a href="<?= URLROOT ?>/post/editPost/<?= $p->post_id ?>" class="text-gray-400 hover:text-voice-green bg-[#1a1d21] p-2 rounded-full" title="Edit Insight"><i class="fas fa-edit"></i></a>
                            <a href="<?= URLROOT ?>/post/deletePost/<?= $p->post_id ?>" class="text-gray-400 hover:text-red-400 bg-[#1a1d21] p-2 rounded-full" onclick="return confirm('Delete this insight? This action cannot be undone.');"><i class="fas fa-trash"></i></a>
                        </div>
                    <?php endif; ?>
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
                
                upBtn.className = "transition-colors text-gray-500 hover:text-voice-green";
                downBtn.className = "transition-colors text-gray-500 hover:text-red-400";
                
                if (data.user_vote === 'up') {
                    upBtn.className = "transition-colors text-voice-green";
                } else if (data.user_vote === 'down') {
                    downBtn.className = "transition-colors text-red-400";
                }
            } else if (data.redirect) { window.location.href = '<?= URLROOT ?>/auth/login'; }
        })
        .catch(err => console.error(err))
        .finally(() => { upBtn.disabled = false; downBtn.disabled = false; });
    }
</script>

<?php require_once '../app/Views/layout/footer.php'; ?>