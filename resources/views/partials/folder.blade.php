<div class="window folder-window draggable">
    <div class="window-header">
        <span>{{ $folder->name }}</span>
        <button onclick="this.closest('.window').remove()">✕</button>
    </div>

    <div class="window-body files-grid">
        @foreach($files as $file)
            <div class="file-icon"
                onclick="openFile(
                    '{{ $file->type }}',
                    '{{ $file->name }}',
                    '{{ $file->content }}',
                    '{{ asset($file->path) }}'
                )">
                <img src="/images/icons/{{ $file->type }}.png">
                <span>{{ $file->name }}</span>
            </div>
        @endforeach
    </div>
</div>
