<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;

use Illuminate\Http\Request;

class carrinhoController extends Controller
{
    public function adicionar(request $request) {

            $roupas = [];

            if (isset($_COOKIE['login'])) {

                $id_cliente = $_COOKIE['login'];

                $roupas = DB::table('carrinho as c')
                    ->leftJoin('roupas as r', 'r.id_roupa', '=', 'c.id_roupa')
                    ->select('r.descricao', 'c.quantidade', 'r.valor', 'c.tamanho', 'r.tamanho as tamanho_todos', 'c.cor', 'c.id_carrinho', 'r.id_roupa', 'r.img')
                    ->where('c.id_cadastro', '=', $id_cliente)
                    ->get();

            } 

            return view('pag.carrinho', compact('roupas'));
    }

    public function verifyLogin(request $request) {
        
        if (isset($_COOKIE['login'])) {
            
            $id_roupa = $request->input('id_roupa');
            $tamanho = $request->input('tamanho');
            $cor = $request->input('cor');
            $id_cliente = $_COOKIE['login'];

            DB::insert("INSERT INTO carrinho (cor, id_cadastro, id_roupa, tamanho, quantidade) values (?,?,?,?,?)",
            ["$cor", "$id_cliente", "$id_roupa", "$tamanho", 1]);

            $data = ["status" => 1, "messagem" => $_COOKIE['login']];
        } else {
            $data = ["status" => 0, "messagem" => 'Não foi encontrado Login'];
        }

        return response()->json($data);
    }

    public function excluir(request $request) {

        $dados = json_decode($request->getContent(), true);
        $id = $dados['id'];
        
        if(!empty($id)) {
            $delete = DB::delete("DELETE FROM carrinho WHERE id_carrinho = ?", [$id]);
            if (!empty($delete)) {
                return response()->json(["status"=> 1]);
            } else {
                return response()->json(["status"=> 0, "messagem"=> "Não foi possível excluir, contate nosso suporte. erro: 001"]);
            }
            
        } else {
            return response()->json(["status"=> 0, "messagem"=> "Não foi possível excluir, contate nosso suporte"]);
        }
    }

    public function quant(request $request) {
        $dados = json_decode($request->getContent(), true);

        $id = $dados['id'];
        $quant = $dados['quant'];

        $update = DB::update("UPDATE carrinho SET quantidade = ? WHERE id_carrinho = ?", [$quant, $id]);

        if (!empty($update)) {
            return response()->json(["status"=> 1]);
        } else {
            return response()->json(["status"=> 0, "messagem"=> "Não foi possível alterar, contate nosso suporte. erro: 002"]);
        }

    }

    public function tamanho(request $request) {
        $dados = json_decode($request->getContent(), true);

        $id = $dados['id'];
        $tamanho = $dados['tamanho'];

        Log::info("UPDATE carrinho SET tamanho =" .$tamanho . " WHERE id_carrinho = " . $id );

        $update = DB::update('UPDATE carrinho SET tamanho = :tamanho WHERE id_carrinho = :id', [
            'tamanho' =>  strval($tamanho),
            'id' => $id
        ]);
        
        if (!empty($update)) {
            return response()->json(["status"=> 1]);
        } else {
            return response()->json(["status"=> 0, "messagem"=> "Não foi possível alterar, contate nosso suporte. erro: 002"]);
        }

    }
}
