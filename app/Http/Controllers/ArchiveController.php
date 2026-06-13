<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveController extends Controller
{
    public function index()
    {
        if (!session('arquivos_autorizado')) {
            abort(403, 'ACESSO NEGADO PELO PROTOCOLO N.O.I.R');
        }

        try {
            $archives = Archive::orderBy('id')->get();
            
            return view('arquivosLista', compact('archives'));

        } catch (\Throwable $e) {
            Log::error('Erro ao acessar a tabela archives: ' . $e->getMessage());

            $archives = collect(); 
            
            return view('arquivosLista', compact('archives'))
                ->with('error', 'Aviso: Banco de dados inacessível ou tabelas não migradas.');
        }
    }
}