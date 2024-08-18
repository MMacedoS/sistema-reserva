<?php

namespace App\Models\Profile;

use App\Models\Traits\UuidTrait;

class Produto {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Produto {
        $product = new Produto();
        $product->id = $data['id'] ?? null;
        $product->uuid = $data['uuid'] ?? $this->generateUUID();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price']; 
        $product->stock = $data['stock'];        
        $product->created_at = $data['created_at'] ?? null;
        $product->updated_at = $data['updated_at'] ?? null;
        return $product;
    }
}