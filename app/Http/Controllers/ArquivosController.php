<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ArquivosController extends Controller
{
    public function unlock(Request $request)
    {
        // 1. Validação manual para evitar o "back()" automático do Laravel
        $validator = Validator::make($request->all(), [
            'codigo_acesso' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('arquivos')->with('error', 'O código de acesso é obrigatório.');
        }

        $senhaBanco = DB::table('archive_passwords')->first();

        if (!$senhaBanco) {
            return redirect()->route('arquivos')->with('error', 'SISTEMA INDISPONÍVEL — NENHUMA CHAVE CADASTRADA');
        }

        $digitado = $request->input('codigo_acesso');
        $salvoNoBanco = $senhaBanco->codigo_acesso;

        $senhaValida = ($digitado === $salvoNoBanco) || Hash::check($digitado, $salvoNoBanco);

        if (!$senhaValida) {
            // 2. Redirecionamento explícito usando a rota nomeada
            return redirect()->route('arquivos')->with('error', 'ACESSO NEGADO — SENHA INVÁLIDA');
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