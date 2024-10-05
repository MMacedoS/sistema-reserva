<?php

namespace App\Models\Reservate;

use App\Models\Traits\UuidTrait;

class Diaria {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_reserva;
    public $dt_daily;
    public $id_usuario;
    public $amount;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Diaria {
        $diaria = new Diaria();
        $diaria->id = $data['id'] ?? null;
        $diaria->uuid = $data['uuid'] ?? $this->generateUUID();
        $diaria->id_reserva = $data['id_reserva'];
        $diaria->id_usuario = $data['id_usuario'] ?? null;
        $diaria->dt_daily = $data['dt_daily'];
        $diaria->status = $data['status'] ?? 1;  
        $diaria->amount = $data['amount'];                
        $diaria->created_at = $data['created_at'] ?? null;
        $diaria->updated_at = $data['updated_at'] ?? null;
        return $diaria;
    }
}