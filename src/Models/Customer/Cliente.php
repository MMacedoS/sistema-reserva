<?php

namespace App\Models\Customer;

use App\Models\Traits\UuidTrait;

class Cliente {
    
    use UuidTrait;

    public $id;
    public $uuid;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $job;
    public $nationality;
    public $doc;
    public $type_doc;
    public $representative;
    public $company;
    public $cnpj_company;
    public $phone_company;
    public $email_company;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Cliente {
        $cliente = new Cliente();
        $cliente->id = $data['id'] ?? null;
        $cliente->uuid = $data['uuid'] ?? $this->generateUUID();
        $cliente->name = $data['name'];
        $cliente->email = $data['email'] ?? null;
        $cliente->phone = $data['phone']; 
        $cliente->address = $data['address'] ?? null; 
        $cliente->job = $data['job'] ?? null; 
        $cliente->nationality = $data['nationality'] ?? null; 
        $cliente->doc = $data['doc'] ?? null; 
        $cliente->type_doc = $data['type_doc'] ?? null; 
        $cliente->representative = $data['representative'] ?? null; 
        $cliente->company = $data['company'] ?? null; 
        $cliente->cnpj_company = $data['cnpj_company'] ?? null; 
        $cliente->phone_company = $data['phone_company'] ?? null;
        $cliente->email_company = $data['email_company'] ?? null;        
        $cliente->created_at = $data['created_at'] ?? null;
        $cliente->updated_at = $data['updated_at'] ?? null;
        return $cliente;
    }
}