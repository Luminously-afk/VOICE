<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>
<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 w-full flex-grow mb-10">
    <div class="flex items-center justify-between mb-6">
        <a href="<?= URLROOT ?>/post/index" class="text-gray-400 hover:text-voice-green font-bold flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Feed
        </a>
    </div>

    <div class="bg-voice-card border border-voice-border rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-8 sm:p-10">
            <!-- Event Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="bg-voice-green/10 text-voice-green border border-voice-green/20 px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider">
                        <?= htmlspecialchars($data['event']->category) ?>
                    </span>
                    
                    <?php if($data['event']->status === 'Upcoming'): ?>
                        <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider">
                            Upcoming
                        </span>
                    <?php elseif($data['event']->status === 'Ongoing'): ?>
                        <span class="bg-red-500/10 text-red-500 border border-red-500/20 px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider animate-pulse">
                            Ongoing
                        </span>
                    <?php else: ?>
                        <span class="bg-gray-500/10 text-gray-400 border border-gray-500/20 px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider">
                            <?= htmlspecialchars($data['event']->status) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if($isLoggedIn && $data['event']->status === 'Ongoing'): ?>
                    <a href="<?= URLROOT ?>/post/registerEvent/<?= $data['event']->event_id ?>" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold rounded-full transition-colors shadow-glow whitespace-nowrap">
                        <i class="fas fa-clipboard-check"></i> Register Attendance
                    </a>
                <?php endif; ?>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-gray-100 mb-6 leading-tight">
                <?= htmlspecialchars(html_entity_decode($data['event']->title ?? '', ENT_QUOTES), ENT_QUOTES) ?>
            </h1>

            <div class="flex flex-col sm:flex-row gap-6 mb-8 py-4 border-y border-voice-border">
                <div class="flex items-center gap-3 text-gray-400">
                    <div class="bg-[#1a1d21] p-3 rounded-xl text-voice-green">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-gray-500 mb-0.5">Start Date</p>
                        <p class="font-bold text-gray-200"><?= date('M j, Y - h:i A', strtotime($data['event']->event_date)) ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-gray-400">
                    <div class="bg-[#1a1d21] p-3 rounded-xl text-red-400">
                        <i class="fas fa-flag-checkered text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-gray-500 mb-0.5">End Date</p>
                        <p class="font-bold text-gray-200"><?= date('M j, Y - h:i A', strtotime($data['event']->end_date)) ?></p>
                    </div>
                </div>
            </div>

            <!-- Event Rich Text Description -->
            <div class="prose prose-invert prose-voice max-w-none">
                <?php 
                    $eventDescHtml = html_entity_decode($data['event']->description ?? '', ENT_QUOTES);
                    $eventDescHtml = preg_replace('/src="(\.\.\/)+uploads\//', 'src="' . URLROOT . '/uploads/', $eventDescHtml);
                    echo $eventDescHtml;
                ?>
            </div>
        </div>
        
        <div class="bg-[#1a1d21] px-8 py-6 border-t border-voice-border flex items-center justify-between">
            <p class="text-gray-400 text-sm font-medium">Have something to say about this event?</p>
            <a href="<?= URLROOT ?>/post/index?event_id=<?= $data['event']->event_id ?>" class="text-voice-green hover:text-white font-bold transition-colors flex items-center gap-2">
                View Insights <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<style>
/* Basic styling for rich text rendering if tailwind typography plugin is missing */
.prose-voice h1, .prose-voice h2, .prose-voice h3, .prose-voice h4 { color: #f3f4f6; font-weight: bold; margin-top: 1.5em; margin-bottom: 0.5em; }
.prose-voice h1 { font-size: 2.25rem; }
.prose-voice h2 { font-size: 1.875rem; }
.prose-voice h3 { font-size: 1.5rem; }
.prose-voice p { color: #d1d5db; line-height: 1.75; margin-bottom: 1.25em; }
.prose-voice ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.25em; color: #d1d5db; }
.prose-voice ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1.25em; color: #d1d5db; }
.prose-voice a { color: #2ecc71; text-decoration: underline; }
.prose-voice a:hover { color: #27ae60; }
.prose-voice img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5em 0; }
.prose-voice blockquote { border-left: 4px solid #2ecc71; padding-left: 1em; color: #9ca3af; font-style: italic; margin: 1.5em 0; }
</style>

<?php require_once '../app/Views/layout/footer.php'; ?>
