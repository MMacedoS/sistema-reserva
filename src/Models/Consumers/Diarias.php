<?php

namespace App\Models\Consumers;

use App\Models\Traits\UuidTrait;

class Consumo {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_reserva;
    public $id_usuario;
    public $dt_daily;
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
        $consumo->dt_daily = $data['dt_daily'];
        $consumo->id_usuario = $data['id_usuario'] ?? null;
        $consumo->quantity = $data['quantity'];        
        $consumo->created_at = $data['created_at'] ?? null;
        $consumo->updated_at = $data['updated_at'] ?? null;
        return $consumo;
    }
}