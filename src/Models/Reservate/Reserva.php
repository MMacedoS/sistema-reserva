<?php

namespace App\Models\Reservate;

use App\Models\Traits\UuidTrait;

class Reserva {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_apartamento;
    public $apartament;
    public $id_usuario;
    public $dt_checkin;
    public $dt_checkout;
    public $status;
    public $customers;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Reserva {
        $reserva = new Reserva();
        $reserva->id = $data['id'] ?? null;
        $reserva->uuid = $data['uuid'] ?? $this->generateUUID();
        $reserva->id_apartamento = $data['apartament'];
        $reserva->id_usuario = $data['id_usuario'] ?? null;
        $reserva->apartament = $data['apartament'] ?? null;
        $reserva->dt_checkin = $data['dt_checkin'];
        $reserva->status = $data['status']; 
        $reserva->dt_checkout = $data['dt_checkout'];                      
        $reserva->customers = $data['customers'] ?? null;  
        $reserva->created_at = $data['created_at'] ?? null;
        $reserva->updated_at = $data['updated_at'] ?? null;
        return $reserva;
    }
}