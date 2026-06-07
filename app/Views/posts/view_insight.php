<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 w-full flex-grow mb-10">
    <div class="flex items-center justify-between mb-6">
        <a href="<?= URLROOT ?>/post/index" class="text-gray-400 hover:text-voice-green font-bold flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Feed
        </a>
    </div>

    <!-- Main Insight Card -->
    <div class="bg-voice-card border border-voice-border rounded-2xl shadow-2xl overflow-hidden mb-8">
        <div class="p-8 sm:p-10">
            <!-- Author & Meta -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8 border-b border-voice-border pb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-voice-green to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-glow">
                        <?= (int)$data['post']->is_anonymous ? 'A' : strtoupper(substr(htmlspecialchars($data['post']->student_name ?? 'U'), 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="text-gray-200 font-bold text-lg leading-tight">
                            <?= (int)$data['post']->is_anonymous ? 'Anonymous Student' : htmlspecialchars($data['post']->student_name) ?>
                        </h4>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                            <span><?= date('M j, Y \a\t g:i A', strtotime($data['post']->created_at)) ?></span>
                            <?php if(!empty($data['post']->event_title)): ?>
                                <span class="w-1 h-1 rounded-full bg-gray-500"></span>
                                <span class="bg-[#1a1d21] text-gray-400 border border-voice-border px-2 py-0.5 rounded-full font-bold">
                                    <?= htmlspecialchars($data['post']->event_title) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <h1 class="text-3xl sm:text-4xl font-black text-gray-100 mb-6 leading-tight">
                <?= htmlspecialchars(html_entity_decode($data['post']->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?>
            </h1>

            <div class="prose prose-invert prose-voice max-w-none text-gray-300 leading-relaxed mb-8">
                <?php 
                    $insightHtml = html_entity_decode($data['post']->insight ?? '', ENT_QUOTES);
                    $insightHtml = preg_replace('/src="(\.\.\/)+uploads\//', 'src="' . URLROOT . '/uploads/', $insightHtml);
                    echo $insightHtml;
                ?>
            </div>

            <!-- Votes -->
            <?php 
                $p = $data['post'];
                $upClass = (isset($p->user_vote) && $p->user_vote === 'up') ? 'text-voice-green' : 'text-gray-500 hover:text-voice-green';
                $downClass = (isset($p->user_vote) && $p->user_vote === 'down') ? 'text-red-400' : 'text-gray-500 hover:text-red-400';
            ?>
            <div class="flex items-center gap-3 pt-6 border-t border-voice-border">
                <button type="button" id="up-btn-<?= $p->post_id ?>" class="flex items-center bg-[#1a1d21] rounded-full px-4 py-2 border border-voice-border transition-colors <?= $upClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'up', <?= $isLoggedIn ? 'true' : 'false' ?>)">
                    <i class="fas fa-arrow-up mr-2"></i>
                    <span id="up-count-<?= $p->post_id ?>" class="font-bold"><?= $p->upvotes_count ?? 0 ?></span>
                </button>
                
                <div class="flex items-center bg-[#1a1d21] rounded-full px-5 py-2 border border-voice-border">
                    <span class="text-xs text-gray-500 mr-2 uppercase tracking-widest font-bold">Score</span>
                    <span class="font-bold text-lg <?= ($p->vote_score ?? 0) > 0 ? 'text-voice-green' : (($p->vote_score ?? 0) < 0 ? 'text-red-400' : 'text-gray-300') ?>" id="score-<?= $p->post_id ?>"><?= $p->vote_score ?? 0 ?></span>
                </div>

                <button type="button" id="down-btn-<?= $p->post_id ?>" class="flex items-center bg-[#1a1d21] rounded-full px-4 py-2 border border-voice-border transition-colors <?= $downClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'down', <?= $isLoggedIn ? 'true' : 'false' ?>)">
                    <span id="down-count-<?= $p->post_id ?>" class="font-bold mr-2"><?= $p->downvotes_count ?? 0 ?></span>
                    <i class="fas fa-arrow-down"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-voice-card border border-voice-border rounded-2xl shadow-2xl p-8 sm:p-10" id="comments">
        <h3 class="text-2xl font-bold text-gray-100 mb-8 flex items-center gap-3">
            <i class="fas fa-comments text-voice-green"></i> 
            Discussion (<?= count($data['comments']) ?>)
        </h3>

        <!-- Add Comment Form -->
        <?php if($isLoggedIn): ?>
            <div class="mb-10 flex gap-4">
                <div class="w-10 h-10 shrink-0 bg-voice-green rounded-full flex items-center justify-center text-voice-dark font-bold text-sm shadow-glow">
                    <?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? 'U'), 0, 1)) ?>
                </div>
                <form action="<?= URLROOT ?>/post/addComment/<?= $data['post']->post_id ?>" method="POST" class="flex-grow">
                    <div class="bg-[#1a1d21] border border-voice-border rounded-xl focus-within:border-voice-green focus-within:ring-1 focus-within:ring-voice-green transition-all overflow-hidden">
                        <textarea name="content" rows="3" class="w-full bg-transparent border-none text-gray-200 p-4 focus:ring-0 resize-none placeholder:text-gray-500" placeholder="Add to the discussion..." required></textarea>
                        <div class="bg-[#2d333b] px-4 py-3 border-t border-voice-border flex justify-end">
                            <button type="submit" class="bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold py-2 px-6 rounded-full transition-colors text-sm">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="mb-10 p-6 bg-[#1a1d21] border border-voice-border rounded-xl text-center">
                <p class="text-gray-400 mb-4">You must be logged in to join the discussion.</p>
                <a href="<?= URLROOT ?>/auth/login" class="inline-block bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold py-2 px-6 rounded-full transition-colors">Log In to Comment</a>
            </div>
        <?php endif; ?>

        <!-- Comments List -->
        <div class="space-y-6">
            <?php if(empty($data['comments'])): ?>
                <p class="text-center text-gray-500 italic py-8">No comments yet. Be the first to share your thoughts!</p>
            <?php else: foreach($data['comments'] as $c): ?>
                <div class="flex gap-4 group">
                    <div class="w-10 h-10 shrink-0 bg-gray-700 rounded-full flex items-center justify-center text-gray-300 font-bold text-sm">
                        <?= strtoupper(substr(htmlspecialchars($c->student_name ?? 'U'), 0, 1)) ?>
                    </div>
                    <div class="flex-grow">
                        <div class="bg-[#1a1d21] border border-voice-border rounded-2xl rounded-tl-none p-5 text-sm text-gray-300 group-hover:border-gray-600 transition-colors">
                            <div class="flex items-baseline justify-between mb-2">
                                <span class="font-bold text-gray-200"><?= htmlspecialchars($c->student_name) ?></span>
                                <span class="text-xs text-gray-500"><?= date('M j, Y g:i A', strtotime($c->created_at)) ?></span>
                            </div>
                            <div class="leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars(html_entity_decode($c->content, ENT_QUOTES), ENT_QUOTES) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
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
                let scoreEl = document.getElementById('score-' + postId);
                scoreEl.innerText = data.score;
                
                scoreEl.className = "font-bold text-lg " + (data.score > 0 ? "text-voice-green" : (data.score < 0 ? "text-red-400" : "text-gray-300"));

                let upCountEl = document.getElementById('up-count-' + postId);
                if (upCountEl) upCountEl.innerText = data.upvotes;
                let downCountEl = document.getElementById('down-count-' + postId);
                if (downCountEl) downCountEl.innerText = data.downvotes;
                
                let baseBtnClass = "flex items-center bg-[#1a1d21] rounded-full px-4 py-2 border border-voice-border transition-colors ";
                upBtn.className = baseBtnClass + "text-gray-500 hover:text-voice-green";
                downBtn.className = baseBtnClass + "text-gray-500 hover:text-red-400";
                
                if (data.user_vote === 'up') {
                    upBtn.className = baseBtnClass + "text-voice-green";
                } else if (data.user_vote === 'down') {
                    downBtn.className = baseBtnClass + "text-red-400";
                }
            } else if (data.redirect) { window.location.href = '<?= URLROOT ?>/auth/login'; }
        })
        .catch(err => console.error(err))
        .finally(() => { upBtn.disabled = false; downBtn.disabled = false; });
    }
</script>

<style>
.prose-voice h1, .prose-voice h2, .prose-voice h3, .prose-voice h4 { font-weight: bold; margin-top: 1.5em; margin-bottom: 0.5em; color: #f3f4f6; }
.prose-voice h1 { font-size: 2.25rem; }
.prose-voice h2 { font-size: 1.875rem; }
.prose-voice p { margin-bottom: 1.25em; }
.prose-voice ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.25em; }
.prose-voice ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1.25em; }
.prose-voice a { color: #2ecc71; text-decoration: underline; }
.prose-voice a:hover { color: #27ae60; }
.prose-voice img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5em 0; }
.prose-voice blockquote { border-left: 4px solid #2ecc71; padding-left: 1em; color: #9ca3af; font-style: italic; margin: 1.5em 0; }
</style>

<?php require_once '../app/Views/layout/footer.php'; ?>
