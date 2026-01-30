<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CorreiosController extends Controller
{
    public function token() {

        // Fazendo a requisição para a API dos Correios
        $response = Http::post('https://cwshom.correios.com.br/v1/autentica');

        // Retornando a resposta da API para o frontend
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Falha na autenticação',
                'status' => $response->status(),
                'message' => $response->body()
            ], $response->status());
        }
    }
}
