<?php

namespace app;

class Frutas extends Model
{
    protected string $table = 'frutas';

    protected array $fields = [
        'nome' => 'string',
        'valor' => 'float',
        'criado_em' => 'datetime',
        'removido_em' => 'datetime',
    ];

    protected array $fieldParsers = [
        'fruta_nome' => 'nome',
        'fruta_valor' => 'valor',
    ];

    public function rules(): array
    {
        return [
            'nome' => 'required|max:255|min:3',
            'valor' => 'required|numeric',
        ];
    }
}
