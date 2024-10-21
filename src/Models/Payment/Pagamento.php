<?php

namespace App\Models\Payment;

use App\Models\Traits\UuidTrait;

class Pagamento {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_reserva;
    public $id_usuario;
    public $type_payment;
    public $payment_amount;
    public $dt_payment;
    public $id_venda;
    public $id_caixa;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Pagamento {
        $pagamento = new Pagamento();
        $pagamento->id = $data['id'] ?? null;
        $pagamento->uuid = $data['uuid'] ?? $this->generateUUID();
        $pagamento->id_reserva = $data['id_reserva'];
        $pagamento->type_payment = $data['type_payment'];
        $pagamento->id_usuario = $data['id_usuario'] ?? null;
        $pagamento->dt_payment = $data['dt_payment']; 
        $pagamento->payment_amount = $data['payment_amount'];   
        $pagamento->id_venda = $data['id_venda'] ?? null;       
        $pagamento->id_caixa = $data['id_caixa'] ?? null;     
        $pagamento->created_at = $data['created_at'] ?? null;
        $pagamento->updated_at = $data['updated_at'] ?? null;
        return $pagamento;
    }
}