<?php

namespace App\Http\Controllers;

use App\Models\Folder;

class SystemController extends Controller
{
    public function index()
    {
        $folders = Folder::all();
        return view('sistema', compact('folders'));
    }

    public function openFolder($id)
    {
        $folder = Folder::with('files')->findOrFail($id);
        return view('partials.folder', compact('folder'));
    }
}
