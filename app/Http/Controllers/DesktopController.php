<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;

class DesktopController extends Controller
{
    public function index()
    {
        $folders = Folder::with('files')->get();
        return view('sistema', compact('folders'));
    }
}
