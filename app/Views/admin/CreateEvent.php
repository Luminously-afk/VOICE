<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/4xmrj221v5x6p30au2gcy38qbtlc0it14p5j717h6degeyly/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 mt-10 w-full flex-grow">
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
        <div class="w-full md:w-3/4 flex-1 mb-10">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-3xl font-bold text-voice-green flex items-center gap-2">
                    <i class="fas fa-calendar-plus"></i> Create New Event
                </h2>
                <a href="<?= URLROOT ?>/admin/events" class="px-6 py-2.5 bg-[#2d333b] border border-voice-border hover:bg-[#30363d] text-gray-300 font-bold rounded-full transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="bg-voice-card border border-voice-border rounded-xl shadow-lg p-6 sm:p-8">
                <form action="<?= URLROOT ?>/admin/createEvent" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-2">Event Title</label>
                        <input type="text" name="title" class="voice-input block w-full px-4 py-3 bg-[#1a1d21] border border-voice-border rounded-lg text-gray-200 placeholder-gray-500 focus:border-voice-green focus:ring-1 focus:ring-voice-green transition-colors" placeholder="e.g., College Intramurals 2026" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-2">Category</label>
                        <select name="category" class="voice-input block w-full px-4 py-3 bg-[#1a1d21] border border-voice-border rounded-lg text-gray-200 focus:border-voice-green focus:ring-1 focus:ring-voice-green transition-colors" required>
                            <option value="Academics">Academics</option>
                            <option value="Sports">Sports</option>
                            <option value="Arts & Culture">Arts & Culture</option>
                            <option value="General" selected>General</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="editor" class="w-full"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Start Date & Time</label>
                            <input type="datetime-local" name="event_date" class="voice-input block w-full px-4 py-3 bg-[#1a1d21] border border-voice-border rounded-lg text-gray-200 focus:border-voice-green focus:ring-1 focus:ring-voice-green transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">End Date & Time</label>
                            <input type="datetime-local" name="end_date" class="voice-input block w-full px-4 py-3 bg-[#1a1d21] border border-voice-border rounded-lg text-gray-200 focus:border-voice-green focus:ring-1 focus:ring-voice-green transition-colors" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 px-4 bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold rounded-full transition-all shadow-glow text-lg">
                            Publish Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template help',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        skin: 'oxide-dark',
        content_css: 'dark',
        images_upload_url: '<?= URLROOT ?>/admin/uploadImage',
        automatic_uploads: true,
        file_picker_types: 'image',
        images_reuse_filename: true,
        image_title: true
    });
</script>

<?php require_once '../app/Views/layout/footer.php'; ?>