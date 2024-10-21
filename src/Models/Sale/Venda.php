<?php

namespace App\Models\Sale;

use App\Models\Traits\UuidTrait;

class Venda {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $name;
    public $description;
    public $amount_sale;
    public $dt_sale;
    public $status;
    public $items;
    public $id_usuario;
    public $id_reserva;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Venda {
        $sale = new Venda();
        $sale->id = $data['id'] ?? null;
        $sale->uuid = $data['uuid'] ?? $this->generateUUID();
        $sale->name = $data['name'];
        $sale->description = $data['description'];
        $sale->dt_sale = $data['dt_sale'] ?? date('Y-m-d');
        $sale->amount_sale = $data['amount_sale'] ?? 0;          
        $sale->id_usuario = $data['id_usuario']; 
        $sale->id_reserva = $data['reserve_id'] ?? null;
        $sale->status = $data['status'];
        $sale->items = $data['items'] ?? null;
        $sale->created_at = $data['created_at'] ?? null;
        $sale->updated_at = $data['updated_at'] ?? null;
        return $sale;
    }
}