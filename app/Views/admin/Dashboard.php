<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 flex-shrink-0">
            <div class="sticky top-24 bg-voice-card border border-voice-border rounded-xl overflow-hidden shadow-lg">
                <div class="px-4 py-3 border-b border-voice-border bg-[#1a1d21] font-bold text-gray-200 flex items-center gap-2">
                    <i class="fas fa-tachometer-alt text-voice-green"></i> Admin Menu
                </div>
                <a href="<?= URLROOT ?>/admin/dashboard" class="block px-4 py-3 border-b border-voice-border transition-colors text-voice-green font-bold bg-[#1a1d21]">Overview</a>
                <a href="<?= URLROOT ?>/admin/events" class="block px-4 py-3 border-b border-voice-border transition-colors text-gray-300 hover:bg-[#30363d]">List of Events</a>
                <a href="<?= URLROOT ?>/admin/users" class="block px-4 py-3 border-voice-border transition-colors text-gray-300 hover:bg-[#30363d]">Manage Users</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4 flex-1">
            <h2 class="text-3xl font-bold text-voice-green mb-6">Pending Insights</h2>
            
            <?php if(empty($data['pending_posts'])): ?>
                <div class="bg-voice-card border border-dashed border-voice-border rounded-xl p-10 text-center shadow-lg">
                    <i class="fas fa-check-circle text-5xl mb-4 text-voice-green opacity-50"></i>
                    <h5 class="text-xl font-bold text-gray-200 mb-2">All clear!</h5>
                    <p class="text-gray-500">No Pending Suggestions.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach($data['pending_posts'] as $post): ?>
                        <div class="bg-voice-card border border-voice-border rounded-xl p-6 shadow-lg">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                                <div>
                                    <div class="mb-2">
                                        <span class="bg-[#1a1d21] border border-voice-border text-gray-300 text-xs px-3 py-1 rounded-full font-bold">
                                            <?= htmlspecialchars($post->event_title ?? 'Generalized Insight') ?>
                                        </span>
                                    </div>
                                    <h6 class="font-bold text-gray-200 flex items-center gap-2">
                                        Submitted by: <?= htmlspecialchars($post->student_name) ?>
                                        <?php if((int)$post->is_anonymous === 1): ?>
                                            <span class="bg-yellow-600/20 text-yellow-500 border border-yellow-600/30 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Requested Anonymous</span>
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-gray-500"><?= date('M j, Y - h:i A', strtotime($post->created_at)) ?></small>
                                </div>
                                <div class="flex gap-2">
                                    <a href="<?= URLROOT ?>/admin/approve/<?= $post->post_id ?>" class="px-4 py-1.5 bg-voice-green hover:bg-voice-green-dark text-voice-dark text-sm font-bold rounded-full transition-colors shadow-glow flex items-center gap-1">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                    <a href="<?= URLROOT ?>/admin/reject/<?= $post->post_id ?>" class="px-4 py-1.5 border border-red-500 text-red-500 hover:bg-red-500 hover:text-white text-sm font-bold rounded-full transition-colors flex items-center gap-1" onclick="return confirm('Are you sure you want to reject this insight?')">
                                        <i class="fas fa-times"></i> Reject
                                    </a>
                                </div>
                            </div>
                            <div class="bg-[#1a1d21] p-4 rounded-lg border-l-4 border-voice-green">
                                <h5 class="font-bold text-gray-100 mb-2"><?= htmlspecialchars(html_entity_decode($post->title ?? 'Untitled Insight', ENT_QUOTES), ENT_QUOTES) ?></h5>
                                <?php if(!empty($post->insight)): ?>
                                    <p class="text-gray-300 text-sm leading-relaxed"><?= nl2br(htmlspecialchars(html_entity_decode($post->insight, ENT_QUOTES), ENT_QUOTES)) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>