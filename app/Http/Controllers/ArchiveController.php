<?php

namespace App\Http\Controllers;
use App\Models\Archive;


class ArchiveController extends Controller
{
    public function index()
    {
        if (!session('arquivos_autorizado')) {
            abort(403, 'ACESSO NEGADO PELO PROTOCOLO N.O.I.R');
        }

        $archives = Archive::orderBy('id')->get();

        return view('arquivosLista', compact('archives'));
    }
}
