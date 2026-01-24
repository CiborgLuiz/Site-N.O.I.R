<div class="window" id="folder-{{ $folder->id }}">
    <div class="window-header">
        {{ $folder->name }}
        <button onclick="closeWindow('folder-{{ $folder->id }}')">X</button>
    </div>

    <div class="window-body">
        @foreach($folder->files as $file)
            <div class="file" onclick="openFile('{{ $file->type }}', '{{ $file->content }}')">
                📄 {{ $file->name }}
            </div>
        @endforeach
    </div>
</div>
