<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ArquivosController extends Controller
{
    public function unlock(Request $request)
    {
        $request->validate([
            'codigo_acesso' => 'required'
        ]);

        $senhaBanco = DB::table('archive_passwords')->first();

        if (!$senhaBanco) {
            return back()->with('error', 'SISTEMA INDISPONÍVEL — NENHUMA CHAVE CADASTRADA');
        }

        $digitado = $request->input('codigo_acesso');
        $salvoNoBanco = $senhaBanco->codigo_acesso;

        $senhaValida = ($digitado === $salvoNoBanco) || Hash::check($digitado, $salvoNoBanco);

        if (!$senhaValida) {
            return back()->with('error', 'ACESSO NEGADO — SENHA INVÁLIDA');
        }

        session(['arquivos_autorizado' => true]);

        return redirect()->route('arquivosLista');
    }

    public function lista()
    {
        if (!session('arquivos_autorizado')) {
            return redirect()->route('arquivos')
                ->with('error', 'ACESSO NÃO AUTORIZADO');
        }

        return view('arquivosLista');
    }
}