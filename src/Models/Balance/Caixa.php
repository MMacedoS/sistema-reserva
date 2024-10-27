<?php

namespace App\Models\Balance;

use App\Models\Traits\UuidTrait;

class Caixa {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_usuario;
    public $opening_date;
    public $starting_balance;
    public $current_balance;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Caixa {
        $caixa = new Caixa();
        $caixa->id = $data['id'] ?? null;
        $caixa->uuid = $data['uuid'] ?? $this->generateUUID();
        $caixa->opening_date = $data['opening_date'];
        $caixa->starting_balance = $data['starting_balance'] ?? 0;
        $caixa->id_usuario = $data['id_usuario'] ?? null;
        $caixa->current_balance = $data['current_balance'] ?? 0; 
        $caixa->status = $data['status'] ?? null;         
        $caixa->created_at = $data['created_at'] ?? null;
        $caixa->updated_at = $data['updated_at'] ?? null;
        return $caixa;
    }
}