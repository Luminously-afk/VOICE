<nav class="sticky top-0 z-40 bg-[#2d333b] border-b border-voice-border">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a class="font-black tracking-widest text-voice-green text-2xl italic" href="<?= URLROOT ?>/post/index">V.O.I.C.E</a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 flex justify-center px-6">
                <div class="w-full max-w-lg relative">
                    <form action="<?= URLROOT ?>/post/index" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Search insights..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="w-full bg-[#1a1d21] border border-voice-border text-gray-200 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-1 focus:ring-voice-green focus:border-voice-green transition-colors text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right side icons -->
            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Notifications -->
                    <div class="relative group">
                        <button type="button" class="text-gray-400 hover:text-white relative p-2" id="notif-btn">
                            <i class="fas fa-bell text-xl"></i>
                            <?php 
                            $unread = 0;
                            if(!empty($data['notifications'])) {
                                foreach($data['notifications'] as $n) { if(($n->status ?? 'Unread') == 'Unread') $unread++; }
                            }
                            if($unread > 0): 
                            ?>
                            <span class="absolute top-0 right-0 bg-voice-green text-voice-dark text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-[#2d333b]"><?= $unread ?></span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Notification Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-80 bg-[#2d333b] border border-voice-border rounded-xl shadow-2xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="p-3 border-b border-voice-border flex justify-between items-center bg-[#1a1d21]">
                                <span class="font-bold text-sm text-gray-200">Notifications</span>
                                <div class="flex gap-2">
                                    <?php if(!empty($data['notifications'])): ?>
                                        <a href="<?= URLROOT ?>/post/readAllNotifs" class="text-voice-green text-xs font-bold hover:underline" title="Mark all as read">Read All</a>
                                        <span class="text-gray-600">|</span>
                                        <a href="<?= URLROOT ?>/post/clearNotifs" class="text-red-400 text-xs font-bold hover:underline" onclick="return confirm('Clear all notifications?');">Clear</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                                <?php if(!empty($data['notifications'])): foreach($data['notifications'] as $notif): ?>
                                    <?php
                                        $type = $notif->type ?? '';
                                        $linkTarget = '';
                                        if (strpos($type, 'Post_Review_') !== false) {
                                            $linkTarget = 'admin/dashboard';
                                        } elseif (strpos($type, 'Suggestion_') !== false || strpos($type, 'vote_group_') !== false) {
                                            $linkTarget = 'post/profile';
                                        } else {
                                            $linkTarget = 'post/index';
                                        }
                                        $readActionLink = URLROOT . "/post/readNotif/" . $notif->notif_id . "?link=" . urlencode($linkTarget);
                                    ?>
                                    <div class="p-3 border-b border-voice-border text-sm relative <?= ($notif->status ?? 'Unread') == 'Unread' ? 'bg-[#1a1d21]' : 'bg-[#2d333b]' ?> hover:bg-[#30363d] transition-colors group/item">
                                        <div class="absolute top-2 right-2 flex items-center z-10 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                            <?php if(($notif->status ?? 'Unread') == 'Unread'): ?>
                                                <a href="<?= URLROOT ?>/post/readNotif/<?= $notif->notif_id ?>" class="text-voice-green hover:text-voice-green-dark mr-2 bg-[#2d333b] p-1.5 rounded-full" title="Mark as Read"><i class="fas fa-check"></i></a>
                                            <?php endif; ?>
                                            <a href="<?= URLROOT ?>/post/deleteNotif/<?= $notif->notif_id ?>" class="text-gray-500 hover:text-red-400 bg-[#2d333b] p-1.5 rounded-full" title="Delete Notification"><i class="fas fa-times"></i></a>
                                        </div>
                                        
                                        <a href="<?= $readActionLink ?>" class="block pr-12">
                                            <p class="mb-1 text-xs <?= ($notif->status ?? 'Unread') == 'Unread' ? 'font-bold text-gray-100' : 'text-gray-400' ?>">
                                                <?= $notif->message ?? 'Update' ?>
                                            </p>
                                            <small class="text-[10px] <?= ($notif->status ?? 'Unread') == 'Unread' ? 'text-voice-green font-bold' : 'text-gray-500' ?>"><?= date('M j, g:i a', strtotime($notif->created_at ?? 'now')) ?></small>
                                        </a>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="p-6 text-center text-gray-500 text-sm">No notifications.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Menu -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-full border border-voice-border text-gray-300 hover:bg-[#1a1d21] transition-colors flex items-center gap-2 text-sm font-medium">
                            <i class="fas fa-user-circle text-gray-400 text-lg"></i>
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                            <i class="fas fa-chevron-down text-xs text-gray-500 ml-1"></i>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-[#2d333b] border border-voice-border rounded-xl shadow-2xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <a href="<?= URLROOT ?>/post/profile" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1a1d21] hover:text-voice-green transition-colors flex items-center gap-2">
                                    <i class="fas fa-history w-4"></i> My Activity
                                </a>
                                <div class="h-px bg-voice-border my-1"></div>
                                <?php if(isset($_SESSION['user_role']) && strtolower(trim($_SESSION['user_role'])) === 'admin'): ?>
                                    <a href="<?= URLROOT ?>/admin/dashboard" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1a1d21] hover:text-voice-green transition-colors flex items-center gap-2">
                                        <i class="fas fa-user-shield w-4"></i> Admin Panel
                                    </a>
                                    <a href="<?= URLROOT ?>/post/index" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1a1d21] hover:text-blue-400 transition-colors flex items-center gap-2">
                                        <i class="fas fa-eye w-4"></i> Student View
                                    </a>
                                    <div class="h-px bg-voice-border my-1"></div>
                                <?php endif; ?>
                                <a href="<?= URLROOT ?>/auth/logout" class="block px-4 py-2 text-sm text-red-400 hover:bg-red-500 hover:text-white transition-colors flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= URLROOT ?>/auth/login" class="text-gray-300 hover:text-white text-sm font-medium mr-2">Login</a>
                    <a href="<?= URLROOT ?>/auth/register" class="px-4 py-2 rounded-full bg-voice-green text-voice-dark text-sm font-bold hover:bg-voice-green-dark transition-colors shadow-glow">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>