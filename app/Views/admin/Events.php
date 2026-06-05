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
                <a href="<?= URLROOT ?>/admin/dashboard" class="block px-4 py-3 border-b border-voice-border transition-colors text-gray-300 hover:bg-[#30363d]">Overview</a>
                <a href="<?= URLROOT ?>/admin/events" class="block px-4 py-3 border-b border-voice-border transition-colors text-voice-green font-bold bg-[#1a1d21]">List of Events</a>
                <a href="<?= URLROOT ?>/admin/users" class="block px-4 py-3 border-voice-border transition-colors text-gray-300 hover:bg-[#30363d]">Manage Users</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4 flex-1">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-3xl font-bold text-voice-green flex items-center gap-2">
                    <i class="far fa-calendar-alt"></i> List of Events
                </h2>
                <a href="<?= URLROOT ?>/admin/createEvent" class="px-6 py-2.5 bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold rounded-full transition-all shadow-glow flex items-center gap-2">
                    <i class="fas fa-plus"></i> Create New Event
                </a>
            </div>

            <div class="bg-voice-card border border-voice-border rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#1a1d21] border-b border-voice-border">
                                <th class="py-4 px-6 font-bold text-gray-300">Event Title</th>
                                <th class="py-4 px-6 font-bold text-gray-300">Date</th>
                                <th class="py-4 px-6 font-bold text-gray-300 text-center">Status</th>
                                <th class="py-4 px-6 font-bold text-gray-300 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-voice-border">
                            <?php if(!empty($data['events'])): foreach($data['events'] as $event): ?>
                                <tr class="hover:bg-[#30363d] transition-colors">
                                    <td class="py-4 px-6 font-bold text-gray-100">
                                        <?= htmlspecialchars(html_entity_decode($event->title ?? '', ENT_QUOTES), ENT_QUOTES) ?>
                                    </td>
                                    <td class="py-4 px-6 text-gray-400 text-sm">
                                        <?= date('M d, Y - h:i A', strtotime($event->event_date)) ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <?php if($event->status === 'Upcoming'): ?>
                                            <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 text-xs px-3 py-1 rounded-full font-bold">Upcoming</span>
                                        <?php elseif($event->status === 'Ongoing'): ?>
                                            <span class="bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 text-xs px-3 py-1 rounded-full font-bold">Ongoing</span>
                                        <?php else: ?>
                                            <span class="bg-gray-500/20 text-gray-400 border border-gray-500/30 text-xs px-3 py-1 rounded-full font-bold"><?= htmlspecialchars($event->status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="<?= URLROOT ?>/admin/viewEvent/<?= $event->event_id ?>" class="text-blue-400 hover:text-blue-300 transition-colors" title="View Details"><i class="fas fa-eye text-lg"></i></a>
                                            <a href="<?= URLROOT ?>/admin/editEvent/<?= $event->event_id ?>" class="text-voice-green hover:text-voice-green-dark transition-colors" title="Edit Event"><i class="fas fa-edit text-lg"></i></a>
                                            <a href="<?= URLROOT ?>/admin/deleteEvent/<?= $event->event_id ?>" class="text-red-500 hover:text-red-400 transition-colors" title="Delete Event" onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                <i class="fas fa-trash text-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500 italic">No events found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>