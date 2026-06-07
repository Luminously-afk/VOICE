<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-10 w-full flex-grow">
    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 flex-shrink-0">
            <div class="sticky top-24 bg-voice-card border border-voice-border rounded-xl overflow-hidden shadow-lg">
                <div class="px-4 py-3 border-b border-voice-border bg-[#1a1d21] font-bold text-gray-200 flex items-center gap-2">
                    <i class="fas fa-tachometer-alt text-voice-green"></i> Admin Menu
                </div>
                <a href="<?= URLROOT ?>/admin/dashboard" class="block px-4 py-3 border-b border-voice-border transition-colors text-gray-300 hover:bg-[#30363d]">Overview</a>
                <a href="<?= URLROOT ?>/admin/events" class="block px-4 py-3 border-b border-voice-border transition-colors text-gray-300 hover:bg-[#30363d]">List of Events</a>
                <a href="<?= URLROOT ?>/admin/users" class="block px-4 py-3 border-voice-border transition-colors text-voice-green font-bold bg-[#1a1d21]">Manage Users</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4 flex-1">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-3xl font-bold text-voice-green flex items-center gap-2">
                    <i class="fas fa-users-cog"></i> Manage Users
                </h2>
                <div class="flex items-center gap-4">
                    <span class="bg-[#1a1d21] border border-voice-border text-gray-300 px-4 py-2 rounded-full font-bold shadow-sm">
                        Total Accounts: <?= count($data['users']) ?>
                    </span>
                    <a href="<?= URLROOT ?>/admin/dashboard" class="px-6 py-2 rounded-full border border-gray-500 text-gray-400 hover:bg-gray-500 hover:text-white font-bold transition-colors">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-voice-card border border-voice-border rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#1a1d21] border-b border-voice-border">
                                <th class="py-4 px-6 font-bold text-gray-300">Student Number</th>
                                <th class="py-4 px-6 font-bold text-gray-300">Full Name</th>
                                <th class="py-4 px-6 font-bold text-gray-300">Email Address</th>
                                <th class="py-4 px-6 font-bold text-gray-300 text-center">Role</th>
                                <th class="py-4 px-6 font-bold text-gray-300 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-voice-border">
                            <?php if(!empty($data['users'])): foreach($data['users'] as $user): ?>
                                <tr class="hover:bg-[#30363d] transition-colors">
                                    <td class="py-4 px-6 font-bold text-gray-100">
                                        <?= htmlspecialchars($user->student_num) ?>
                                    </td>
                                    <td class="py-4 px-6 text-gray-300">
                                        <?= htmlspecialchars($user->name) ?>
                                    </td>
                                    <td class="py-4 px-6 text-gray-400 text-sm">
                                        <?= htmlspecialchars($user->email) ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <?php if($user->role === 'Admin'): ?>
                                            <span class="bg-red-500/20 text-red-500 border border-red-500/30 text-xs px-3 py-1 rounded-full font-bold">Admin</span>
                                        <?php else: ?>
                                            <span class="bg-voice-green/20 text-voice-green border border-voice-green/30 text-xs px-3 py-1 rounded-full font-bold">Student</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="<?= URLROOT ?>/admin/editUser/<?= $user->user_id ?>" class="p-2 border border-blue-500/50 text-blue-400 hover:bg-blue-500 hover:text-white rounded transition-colors" title="Edit"><i class="fas fa-edit"></i></a>
                                            <?php if($user->role !== 'Admin'): ?>
                                                <a href="<?= URLROOT ?>/admin/deleteUser/<?= $user->user_id ?>" class="p-2 border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white rounded transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500 italic">No users found.</td>
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