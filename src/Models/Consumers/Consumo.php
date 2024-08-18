<?php

namespace App\Models\Consumers;

use App\Models\Traits\UuidTrait;

class Consumo {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_reserva;
    public $id_produto;
    public $id_usuario;
    public $quantity;
    public $amount;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Consumo {
        $consumo = new Consumo();
        $consumo->id = $data['id'] ?? null;
        $consumo->uuid = $data['uuid'] ?? $this->generateUUID();
        $consumo->id_reserva = $data['id_reserva'];
        $consumo->id_produto = $data['id_produto'];
        $consumo->id_usuario = $data['id_usuario'] ?? null;
        $consumo->amount = $data['amount']; 
        $consumo->quantity = $data['quantity'];        
        $consumo->created_at = $data['created_at'] ?? null;
        $consumo->updated_at = $data['updated_at'] ?? null;
        return $consumo;
    }
}