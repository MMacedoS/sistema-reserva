<?php

namespace App\Models\Reservate;

use App\Models\Traits\UuidTrait;

class Reserva_hospede {
    
    use UuidTrait;

    public $id_reserva;
    public $id_hospede;
    public $is_primary;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Reserva_hospede {
        $reserva_hospede = new Reserva_hospede();
        $reserva_hospede->id_reserva = $data['id_reserva'];
        $reserva_hospede->id_hospede = $data['id_hospede']; 
        $reserva_hospede->is_primary = $data['is_primary'];        
        $reserva_hospede->created_at = $data['created_at'] ?? null;
        $reserva_hospede->updated_at = $data['updated_at'] ?? null;
        return $reserva_hospede;
    }
}