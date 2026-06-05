<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Left Sidebar -->
        <div class="w-full md:w-1/4 flex-shrink-0">
            <div class="sticky top-24">
                <button onclick="<?= $isLoggedIn ? 'openModal()' : 'window.location.href=\''.URLROOT.'/auth/login\'' ?>" class="w-full py-3 px-4 bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold rounded-full transition-all duration-200 shadow-glow mb-6 flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Share an Insight
                </button>
                
                <div class="bg-voice-card border border-voice-border rounded-xl overflow-hidden shadow-lg">
                    <div class="px-4 py-3 border-b border-voice-border bg-[#1a1d21] font-bold text-gray-200">
                        <i class="fas fa-filter text-voice-green mr-2"></i> Filter by Event
                    </div>
                    <?php $cf = ($data['feed_type'] ?? 'popular') == 'popular' ? 'popular' : 'latest'; ?>
                    <?php $q_param = isset($_GET['q']) ? '?q=' . urlencode($_GET['q']) : ''; ?>
                    <a href="<?= $isLoggedIn ? URLROOT . '/post/' . $cf . $q_param : URLROOT . '/auth/login' ?>" class="block px-4 py-3 border-b border-voice-border hover:bg-[#30363d] transition-colors <?= !isset($_GET['event_id']) ? 'text-voice-green font-bold bg-[#1a1d21]' : 'text-gray-300' ?>">All Events</a>
                    <?php if(!empty($data['events'])): foreach($data['events'] as $e): ?>
                        <?php $q_param_and = isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : ''; ?>
                        <a href="<?= $isLoggedIn ? URLROOT . '/post/' . $cf . '?event_id=' . $e->event_id . $q_param_and : URLROOT . '/auth/login' ?>" class="block px-4 py-3 border-b border-voice-border hover:bg-[#30363d] transition-colors <?= (isset($_GET['event_id']) && $_GET['event_id'] == $e->event_id) ? 'text-voice-green font-bold bg-[#1a1d21]' : 'text-gray-300' ?>">
                            <?= htmlspecialchars($e->title) ?>
                        </a>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Feed -->
        <div class="w-full md:w-1/2 flex-1">
            <!-- Tabs -->
            <div class="bg-voice-card border border-voice-border rounded-xl flex mb-6 shadow-lg overflow-hidden">
                <?php $q_param = isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : ''; ?>
                <a href="<?= URLROOT ?>/post/popular<?= isset($_GET['event_id']) ? '?event_id='.$_GET['event_id'].$q_param : '?'.$q_param ?>" class="flex-1 text-center py-4 border-r border-voice-border font-bold transition-colors <?= ($data['feed_type'] ?? 'popular') == 'popular' ? 'text-voice-green bg-[#1a1d21]' : 'text-gray-400 hover:bg-[#30363d]' ?>">Popular</a>
                <a href="<?= URLROOT ?>/post/latest<?= isset($_GET['event_id']) ? '?event_id='.$_GET['event_id'].$q_param : '?'.$q_param ?>" class="flex-1 text-center py-4 font-bold transition-colors <?= ($data['feed_type'] ?? '') == 'new' ? 'text-voice-green bg-[#1a1d21]' : 'text-gray-400 hover:bg-[#30363d]' ?>">New</a>
            </div>

            <?php require_once '../app/Core/Flash.php'; Flash::display('post_message'); ?>

            <div id="posts-container" class="space-y-6 mb-10">
                <?php if (empty($data['posts'])): ?>
                    <div class="bg-voice-card border border-voice-border rounded-xl p-10 text-center text-gray-500 shadow-lg">
                        <i class="fas fa-comment-slash text-5xl mb-4 text-gray-600"></i>
                        <p>No insights found.</p>
                    </div>
                <?php else: foreach ($data['posts'] as $p): ?>
                    <?php 
                        $upClass = (isset($p->user_vote) && $p->user_vote === 'up') ? 'text-voice-green' : 'text-gray-500 hover:text-voice-green';
                        $downClass = (isset($p->user_vote) && $p->user_vote === 'down') ? 'text-red-400' : 'text-gray-500 hover:text-red-400';
                    ?>
                    <div class="bg-voice-card border border-voice-border rounded-xl p-6 shadow-lg relative group">
                        <!-- Action dropdown for my posts -->
                        <?php if($isLoggedIn && $_SESSION['user_id'] == $p->user_id): ?>
                            <div class="absolute top-4 right-4 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= URLROOT ?>/post/edit/<?= $p->post_id ?>" class="text-gray-400 hover:text-voice-green bg-[#1a1d21] p-2 rounded-full mr-1" title="Edit Insight"><i class="fas fa-edit"></i></a>
                                <a href="<?= URLROOT ?>/post/delete/<?= $p->post_id ?>" class="text-gray-400 hover:text-red-400 bg-[#1a1d21] p-2 rounded-full" title="Delete Insight" onclick="return confirm('Delete this insight?')"><i class="fas fa-trash"></i></a>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4 flex flex-wrap items-center gap-2">
                            <?php if(!empty($p->event_title)): ?>
                                <span class="bg-[#1a1d21] border border-voice-border text-gray-300 text-xs px-3 py-1.5 rounded-full font-bold"><?= htmlspecialchars($p->event_title) ?></span>
                            <?php else: ?>
                                <span class="bg-[#1a1d21] border border-voice-border text-gray-500 text-xs px-3 py-1.5 rounded-full font-bold">Generalized Insight</span>
                            <?php endif; ?>
                            <span class="text-xs text-gray-500 font-medium">
                                Posted by <span class="text-gray-400"><?= (int)$p->is_anonymous === 1 ? 'Anonymous Student' : htmlspecialchars($p->student_name) ?></span> • <?= date('M j, Y', strtotime($p->created_at)) ?>
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-100 mb-3"><?= htmlspecialchars(html_entity_decode($p->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?></h3>
                        
                        <?php if(!empty($p->insight)): ?>
                            <p class="text-gray-300 text-sm leading-relaxed mb-4"><?= nl2br(htmlspecialchars(html_entity_decode($p->insight, ENT_QUOTES), ENT_QUOTES)) ?></p>
                        <?php endif; ?>

                        <div class="flex items-center gap-4 border-t border-voice-border pt-4">
                            <div class="flex items-center bg-[#1a1d21] rounded-full px-3 py-1.5 border border-voice-border">
                                <button type="button" id="up-btn-<?= $p->post_id ?>" class="transition-colors <?= $upClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'up', <?= $isLoggedIn ? 'true' : 'false' ?>)">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                                <span class="mx-3 text-sm font-bold text-gray-300 w-4 text-center" id="score-<?= $p->post_id ?>"><?= $p->vote_score ?? 0 ?></span>
                                <button type="button" id="down-btn-<?= $p->post_id ?>" class="transition-colors <?= $downClass ?>" onclick="handleVoteClick(event, <?= $p->post_id ?>, 'down', <?= $isLoggedIn ? 'true' : 'false' ?>)">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="w-full md:w-1/4 hidden lg:block flex-shrink-0">
            <div class="sticky top-24 bg-voice-card border border-voice-border rounded-xl p-5 shadow-lg">
                <h5 class="font-bold text-gray-200 mb-4 pb-2 border-b border-voice-border flex items-center gap-2">
                    <i class="far fa-calendar-alt text-voice-green"></i> Active Events
                </h5>
                <?php if(!empty($data['upcoming_events'])): foreach($data['upcoming_events'] as $ue): ?>
                    <div class="mb-4 last:mb-0">
                        <div class="flex items-center justify-between mb-1">
                            <small class="text-voice-green font-bold text-xs">
                                <?= date('M j, Y', strtotime($ue->event_date)) ?>
                            </small>
                            <?= $ue->status === 'Ongoing' ? '<span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded uppercase tracking-wider font-bold">Ongoing</span>' : '' ?>
                        </div>
                        <h6 class="font-bold text-gray-200 text-sm mb-1 leading-tight">
                            <?= htmlspecialchars(html_entity_decode($ue->title, ENT_QUOTES), ENT_QUOTES) ?>
                        </h6>
                        <p class="text-gray-500 text-xs line-clamp-2">
                            <?= htmlspecialchars(html_entity_decode($ue->description ?? '', ENT_QUOTES), ENT_QUOTES) ?>
                        </p>
                    </div>
                <?php endforeach; else: ?>
                    <p class="text-gray-500 text-sm italic">No active or upcoming events.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN: ShareInsightModal -->
<div id="insightModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <main class="relative z-10 w-full max-w-2xl bg-[#2d333b] rounded-xl shadow-2xl border border-[#444c56] overflow-hidden text-left" data-purpose="share-insight-modal">
                <!-- Header -->
                <header class="flex items-center justify-between px-6 py-4 border-b border-[#444c56]">
                    <h1 class="text-xl font-semibold text-white">Share Insight Post</h1>
                    <button type="button" aria-label="Close modal" class="text-gray-400 hover:text-white transition-colors" onclick="closeModal()">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </header>
                
                <!-- Form Content -->
                <form action="<?= URLROOT ?>/post/store" method="POST" class="p-6 space-y-6">
                    <!-- Select Event Dropdown -->
                    <section data-purpose="event-selection">
                        <label class="block text-sm font-medium text-gray-300 mb-2" for="event_id">Select Event (Optional)</label>
                        <div class="relative">
                            <select name="event_id" class="w-full bg-[#22272e] border border-[#444c56] text-white rounded-md py-2.5 px-4 appearance-none focus:ring-1 focus:ring-[#2ecc71] focus:border-[#2ecc71] voice-input transition-all" id="event_id">
                                <option value="">General Insight (No specific event)</option>
                                <?php if(!empty($data['events'])): foreach($data['events'] as $event): ?>
                                    <option value="<?= $event->event_id ?>" <?= (isset($_GET['event_id']) && $_GET['event_id'] == $event->event_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($event->title) ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                        </div>
                    </section>
                    
                    <!-- Title Input -->
                    <section data-purpose="title-input">
                        <label class="block text-sm font-medium text-gray-300 mb-2" for="title">Title</label>
                        <input name="title" class="w-full bg-[#22272e] border border-[#444c56] text-white rounded-md py-3 px-4 placeholder:text-gray-500 focus:ring-1 focus:ring-[#2ecc71] focus:border-[#2ecc71] voice-input transition-all" id="title" placeholder="Enter a title for your insight" required type="text"/>
                    </section>
                    
                    <!-- Rich Text Area -->
                    <section class="border border-[#444c56] rounded-md overflow-hidden bg-[#22272e] focus-within:ring-1 focus-within:ring-[#2ecc71] focus-within:border-[#2ecc71] transition-all" data-purpose="rich-text-editor">
                        <div class="flex items-center space-x-4 px-4 py-2 bg-[#2d333b] border-b border-[#444c56]">
                            <button class="text-gray-400 hover:text-white" title="Bold" type="button"><strong>B</strong></button>
                            <button class="text-gray-400 hover:text-white" title="Italic" type="button"><em>I</em></button>
                            <button class="text-gray-400 hover:text-white" title="Underline" type="button"><u>U</u></button>
                        </div>
                        <textarea name="insight" class="w-full bg-transparent border-none text-white p-4 h-40 resize-none focus:ring-0 placeholder:text-gray-500" placeholder="Share your thoughts, experiences, or observations here..." required></textarea>
                    </section>
                    
                    <!-- Footer Actions -->
                    <footer class="flex flex-col sm:flex-row items-center justify-between pt-4 space-y-4 sm:space-y-0">
                        <!-- Anonymous Toggle -->
                        <div class="flex items-center space-x-3" data-purpose="anonymous-toggle-container">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer custom-checkbox">
                                <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-voice-green"></div>
                                <span class="ml-3 text-sm font-medium text-gray-300">Post Anonymously</span>
                            </label>
                        </div>
                        <!-- Buttons -->
                        <div class="flex items-center space-x-3">
                            <button onclick="closeModal()" class="px-6 py-2.5 rounded-md font-medium text-gray-300 bg-[#444c56] hover:bg-[#545d68] transition-colors" type="button">
                                Cancel
                            </button>
                            <button class="px-6 py-2.5 rounded-md font-bold text-[#1a1d21] bg-[#2ecc71] hover:bg-[#27ae60] shadow-[0_0_15px_rgba(46,204,113,0.4)] transition-all" type="submit">
                                Share Insight
                            </button>
                        </div>
                    </footer>
                </form>
            </main>
        </div>
    </div>
</div>
<!-- END: ShareInsightModal -->

<script>
    function openModal() {
        document.getElementById('insightModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('insightModal').classList.add('hidden');
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
                
                upBtn.className = "transition-colors text-gray-500 hover:text-voice-green";
                downBtn.className = "transition-colors text-gray-500 hover:text-red-400";
                
                if (data.user_vote === 'up') {
                    upBtn.className = "transition-colors text-voice-green";
                } else if (data.user_vote === 'down') {
                    downBtn.className = "transition-colors text-red-400";
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

<?php require_once '../app/Views/layout/footer.php'; ?>