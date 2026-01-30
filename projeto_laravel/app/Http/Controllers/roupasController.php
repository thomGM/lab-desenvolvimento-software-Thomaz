<?php

namespace App\Http\Controllers;

use App\Models\Roupas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class roupasController extends Controller
{
    public function roupas(request $request) {
        log::info('Chegou aqui roupasController roupas');
        if($request->hasFile('foto')) {
            $arquivo = $_FILES['foto'];

            if($arquivo['error']) {
                $mensagem = "Erro ao salvar a imagem.";
                return redirect()->back()->with('mensagem', $mensagem)->withInput();
            }

            $nomeDoArquivo = $arquivo['name'];
            $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

            //strtolower - converte para letras minusculas
            //pathinfo - vai pegar a extensao do arquivo 
        
            if($extensao != "jpg" && $extensao != "png"){
                Log::info("Tipo de arquivo invalido: " . $extensao);
                $mensagem = "Tipo de arquivo invalido.";
                return redirect()->back()->with('mensagem', $mensagem)->withInput();
            }

            $nome_id = uniqid();
            $fileNameToStore= $nome_id . "." . $extensao;

            $path = $request->file('foto')->storeAs('public/img', $fileNameToStore);

            $modulo = $request->input('modulo');
            $tipo = $request->input('tipo');
            $descricao = $request->input('texto');
            $cor = $request->input('cor');
            $cores = implode(",", $cor);
            $genero = $request->input('genero');
            $tamanhos = $request->input('tamanho');
            $tamanho = implode(",", $tamanhos);
            $quantidade = $request->input('quantidade');
            $descricaoDetalhada = $request->input('textoDetalhado');
            $valor = $request->input('valor');

            log::info("antes do inserto ");
            DB::insert('INSERT into roupas (modulo, tipo, descricao, img, cores, genero, quantidade, tamanho, descricaoDetalhada, valor) 
            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', ["$modulo", "$tipo", "$descricao", "$fileNameToStore", "{$cores}", "$genero", "$quantidade", "{$tamanho}", "$descricaoDetalhada", "{$valor}"]);
        }
    }

    public function modas() {
       
        $modulo = request('modulo');
        $tipo = request('tipo');

        log::info('modulo recebido: ' . $modulo);
        log::info('tipo recebido: ' . $tipo);

        switch($modulo) {
            case 1:
                $title = 'Feminino';
                break;
            case 2:
                $title = "Masculino";
                break;
            case 3:
                $title = "Calçados e Acessorios";
                break;
            case 4:
                $title = "Lançamentos";
                break;
            case 5:
                $title = "Ofertas";
                break;
            default:
                $title = "Roupas";
                break;
        }

        $roupas = Roupas::where('tipo', $tipo)
                ->where('modulo', $modulo)
                ->get();
        
                log::info('Roupas encontradas: ' . $roupas->count());
       return view('pag.modas', compact('roupas', 'title'));
    }

    public function compra($id) {
        $data = Roupas::where('id_roupa', $id)
                ->get();
        return view('pag.compra', compact('data'));
    }

}