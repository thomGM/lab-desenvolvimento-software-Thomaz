<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Roupas extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'tipo',
        'modulo',
        'id_roupa',
        'descricao',
        'img',
        'cores',
        'genero',
        'descricaoDetalhada',
        'tamanho',
        'quantidade',
        'valor'
    ];
}
