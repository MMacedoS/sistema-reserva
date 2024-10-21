<?php

namespace App\Models\Sale;

use App\Models\Traits\UuidTrait;

class ItemVenda {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $id_venda;
    public $id_produto;
    public $id_usuario;
    public $quantity;
    public $amount_item;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): ItemVenda {
        $itemVenda = new ItemVenda();
        $itemVenda->id = $data['id'] ?? null;
        $itemVenda->uuid = $data['uuid'] ?? $this->generateUUID();
        $itemVenda->id_venda = $data['id_venda'];
        $itemVenda->id_produto = $data['id_produto'];
        $itemVenda->id_usuario = $data['id_usuario'] ?? null;
        $itemVenda->amount_item = $data['amount_item']; 
        $itemVenda->quantity = $data['quantity'];        
        $itemVenda->created_at = $data['created_at'] ?? null;
        $itemVenda->updated_at = $data['updated_at'] ?? null;
        return $itemVenda;
    }
}