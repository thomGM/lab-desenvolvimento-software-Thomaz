<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Clientes extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'dataAniver',
        'cpf',
        'email',
        'celular',
        'senha',
    ];
}