<?php

namespace App\Models\Apartamento;

use App\Models\Traits\UuidTrait;

class Apartamento {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $name;
    public $description;
    public $id_usuario;
    public $category;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Apartamento {
        $apartamento = new Apartamento();
        $apartamento->id = $data['id'] ?? null;
        $apartamento->uuid = $data['uuid'] ?? $this->generateUUID();
        $apartamento->name = $data['name'];
        $apartamento->id_usuario = $data['id_usuario'] ?? null;
        $apartamento->description = $data['description'];
        $apartamento->category = $data['category'];
        $apartamento->status = $data['status'];        
        $apartamento->created_at = $data['created_at'] ?? null;
        $apartamento->updated_at = $data['updated_at'] ?? null;
        return $apartamento;
    }
}