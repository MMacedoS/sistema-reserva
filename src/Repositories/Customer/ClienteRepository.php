<?php

namespace App\Repositories\Customer;

use App\Config\Database;
use App\Models\Customer\Cliente;
use App\Repositories\Traits\FindTrait;

class ClienteRepository {
    const CLASS_NAME = Cliente::class;
    const TABLE = 'clientes';
    
    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Cliente();
    }

    public function all()
    {
        $stmt = $this->conn->query("SELECT * FROM " . self::TABLE . " order by name ASC");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);        
    }

    public function create(array $data)
    {   
        $customer = $this->model->create(
            $data
        );

        try {
            $stmt = $this->conn
            ->prepare(
                "INSERT INTO " . self::TABLE . " (
                    uuid,
                    name,
                    email,
                    phone,
                    address,
                    job,
                    nationality,
                    doc,
                    type_doc,
                    representative,
                    company,
                    cnpj_company,
                    phone_company,
                    email_company                   
                ) VALUES (
                    :uuid,
                    :name,
                    :email,
                    :phone,
                    :address,
                    :job,
                    :nationality,
                    :doc,
                    :type_doc,
                    :representative,
                    :company,
                    :cnpj_company,
                    :phone_company,
                    :email_company
                )
            ");
            $create = $stmt->execute([
                ':uuid' => $customer->uuid,
                ':name' => $customer->name,
                ':email' => $customer->email,
                ':phone' => $customer->phone,
                ':address' => $customer->address,
                ':job' => $customer->job,
                ':nationality' => $customer->nationality,
                ':doc' => $customer->doc,
                ':type_doc' => $customer->type_doc,
                ':representative' => $customer->representative === 'on' ? 1 : 0,
                ':company' => $customer->company,
                ':cnpj_company' => $customer->cnpj_company,
                ':phone_company' => $customer->phone_company,
                ':email_company' => $customer->email_company
            ]);
    
            if (is_null($create)) {
                return null;
            }
    
            return $this->findById($this->conn->lastInsertId());
        } catch (\Throwable $th) {
            

        var_dump($th->getMessage());die;
            return null;
        }
    }

    public function update(int $id, array $data)
    {
        $customer = $this->model->create(
            $data
        );

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    name = :name,
                    email = :email,
                    phone = :phone,
                    address = :address,
                    job = :job,
                    nationality = :nationality,
                    doc = :doc,
                    type_doc = :type_doc,
                    representative = :representative,
                    company = :company,
                    cnpj_company = :cnpj_company,
                    phone_company = :phone_company,
                    email_company = :email_company
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                'id' => $id,
                ':name' => $customer->name,
                ':email' => $customer->email,
                ':phone' => $customer->phone,
                ':address' => $customer->address,
                ':job' => $customer->job,
                ':nationality' => $customer->nationality,
                ':doc' => $customer->doc,
                ':type_doc' => $customer->type_doc,
                ':representative' => $customer->representative === 'on' ? 1 : 0,
                ':company' => $customer->company,
                ':cnpj_company' => $customer->cnpj_company,
                ':phone_company' => $customer->phone_company,
                ':email_company' => $customer->email_company
            ]);

            if (!$updated) {                
                $this->conn->rollBack();
                return null;
            }
            $this->conn->commit();
            return $this->findById($id);
        } catch (\Throwable $th) {
            $this->conn->rollBack();
            return null;
        }
    }

    public function delete($id) 
    {
        $stmt = $this->conn
            ->prepare(
                "DELETE FROM " . self::TABLE . " WHERE id = :id"
            );
        $deleted = $stmt->execute(['id' => $id]);
        
        return $deleted;

    }



}