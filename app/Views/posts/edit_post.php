<?php require_once '../app/Views/layout/header.php'; ?>
<?php require_once '../app/Views/components/navbar.php'; ?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 w-full flex-grow mb-10">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-3xl font-bold text-voice-green flex items-center gap-2">
            <i class="fas fa-edit"></i> Edit Insight
        </h3>
        <a href="<?= URLROOT ?>/post/profile" class="px-6 py-2.5 bg-[#2d333b] border border-voice-border hover:bg-[#30363d] text-gray-300 font-bold rounded-full transition-all">
            Cancel
        </a>
    </div>

    <div class="bg-voice-card border border-voice-border rounded-xl shadow-lg p-6 sm:p-8">
        <form action="<?= URLROOT ?>/post/updatePost/<?= $data['post']->post_id ?>" method="POST" class="space-y-6">
            
            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Link to Event</label>
                <select name="event_id" class="voice-input block w-full px-4 py-3 bg-[#1a1d21] border border-voice-border rounded-lg text-gray-200 focus:border-voice-green focus:ring-1 focus:ring-voice-green transition-colors">
                    <option value="generalized" <?= is_null($data['post']->event_id) ? 'selected' : '' ?>>Generalized Insight (No specific event)</option>
                    <?php if(!empty($data['events'])): foreach($data['events'] as $e): ?>
                        <option value="<?= $e->event_id ?>" <?= ((int)$data['post']->event_id === (int)$e->event_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars(html_entity_decode($e->title ?? '', ENT_QUOTES), ENT_QUOTES) ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Title</label>
                <input type="text" name="title" class="voice-input block w-full px-4 py-3 bg-[#1a1d21] border border-voice-border rounded-lg text-gray-200 focus:border-voice-green focus:ring-1 focus:ring-voice-green transition-colors" value="<?= htmlspecialchars(html_entity_decode($data['post']->title ?? '', ENT_QUOTES), ENT_QUOTES) ?>" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Your Insight / Suggestion</label>
                <textarea name="insight" id="insight-editor" class="w-full" required><?= htmlspecialchars(html_entity_decode($data['post']->insight ?? '', ENT_QUOTES), ENT_QUOTES) ?></textarea>
            </div>

            <div class="flex items-center space-x-3 pt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer custom-checkbox" <?= (int)$data['post']->is_anonymous === 1 ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-voice-green"></div>
                    <span class="ml-3 text-sm font-medium text-gray-300">Post anonymously</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 px-4 bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold rounded-full transition-all shadow-glow text-lg">
                    Save Changes & Resubmit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/4xmrj221v5x6p30au2gcy38qbtlc0it14p5j717h6degeyly/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#insight-editor',
        plugins: 'advlist autolink lists link image preview media table',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image media',
        height: 400,
        skin: 'oxide-dark',
        content_css: 'dark',
        images_upload_url: '<?= URLROOT ?>/post/uploadImage',
        automatic_uploads: true,
        file_picker_types: 'image',
        images_reuse_filename: true,
        document_base_url: '<?= URLROOT ?>/',
        relative_urls: false,
        remove_script_host: true,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save(); // ensure textarea gets updated for required validation
            });
        }
    });
</script>

<?php require_once '../app/Views/layout/footer.php'; ?>