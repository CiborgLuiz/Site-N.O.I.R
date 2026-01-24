<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;

class SystemController extends Controller
{
    public function openFolder($id)
    {
        $folder = Folder::findOrFail($id);

        $files = File::where('folder_id', $folder->id)->get();

        return view('partials.folder', [
            'folder' => $folder,
            'files'  => $files
        ]);
    }
}
