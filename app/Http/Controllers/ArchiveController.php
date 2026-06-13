<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveController extends Controller
{
    public function index()
    {
        // 1. Verifica se o usuário passou pela tela da senha
        if (!session('arquivos_autorizado')) {
            abort(403, 'ACESSO NEGADO PELO PROTOCOLO N.O.I.R');
        }

        try {
            // 2. Tenta buscar os dados do banco normalmente
            $archives = Archive::orderBy('id')->get();
            
            return view('arquivosLista', compact('archives'));

        } catch (\Throwable $e) {
            // 3. Se o banco falhar (tabela não existe ou conexão caiu), evita o Erro 500 global
            Log::error('Erro ao acessar a tabela archives: ' . $e->getMessage());

            // Retorna um array vazio para a view não quebrar, permitindo que a página carregue
            $archives = collect(); 
            
            return view('arquivosLista', compact('archives'))
                ->with('error', 'Aviso: Banco de dados inacessível ou tabelas não migradas.');
        }
    }
}