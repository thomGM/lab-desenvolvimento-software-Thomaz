<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function salvarDados(Request $request)

            {
                $mensagens = [
                    'nome.required' => 'O campo nome é obrigatório.',
                    'data.required' => 'O campo data é obrigatório.',
                    'data.date' => 'O campo data deve ser uma data válida.',
                    'cpf.required' => 'O campo CPF é obrigatório.',
                    'cpf.max' => 'O campo CPF não pode ter mais de :max caracteres.',
                    'email.unique' => 'Esse email ja foi cadastrado',
                    'senha.min' => 'A senha deve conter no mínimo 6 caracteres.',
                    'celular.max' => 'O campo celular não pode ter mais de :max caracteres.',
                    'celular.min' => 'Celular inválido.',
                    'cpf.min' => 'CPF inválido.',
                    'email.email' => 'Email inválido.',
                    'senha.confirmed' => 'A confirmação da senha não corresponde.',
                ];

                $validator = Validator::make($request->all(), [
                    'nome' => 'required|string|max:70',
                    'data' => 'required|date',
                    'cpf' => 'required|string|max:14|min:14|', // Usei string porque CPF pode ter caracteres como '.' e '-'
                    'email' => 'required|email|unique:clientes,email',
                    'celular' => 'required|string|max:16|min:16|', // Usei string porque número de celular pode ter caracteres como '+' e '-'
                    'senha' => 'required|string|min:6|confirmed',
                ], $mensagens);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $nome = $request->input('nome');
                $data = $request->input('data');
                $cpf = $request->input('cpf');
                $email = $request->input('email');
                $celular = $request->input('celular');
                $senha = $request->input('senha');
        
                // The password confirmation is now handled by the 'confirmed' validation rule.
                // Consider using Eloquent ORM here for better code structure and security.
                DB::insert('INSERT into clientes (nome, dataAniver, cpf, email, celular, senha) 
                values (?, ?, ?, ?, ?, ?)', [$nome, $data, $cpf, $email, $celular, bcrypt($senha)]); // It's a good practice to hash passwords.
        
                $mensagem = "Salvo com sucesso";
                return redirect()->back()->with('mensagem', $mensagem);
    }
}
