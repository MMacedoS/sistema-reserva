<?php

namespace App\Repositories\Sale;

use App\Config\Database;
use App\Models\Sale\ItemVenda;
use App\Repositories\Traits\FindTrait;

class ItemVendaRepository {
    const CLASS_NAME = ItemVenda::class;
    const TABLE = 'itens_Venda';
    
    use FindTrait;
    protected $conn;
    protected $model;
    protected $vendaRepository;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new ItemVenda();
        $this->vendaRepository = new VendaRepository();
    }

    public function all(array $params)
    {
        $sql = "
        SELECT 
           iv.*,
           (
            SELECT 
               JSON_OBJECT(
                   'id', p.id,
                   'name', p.name,
                   'price', p.price
                )
            FROM produtos p
            WHERE p.id = iv.id_produto
        ) AS products
        FROM itens_Venda iv
        ";
        
        $conditions = [];
        $bindings = [];

        if (isset($params['quantity'])) {
            $conditions[] = "quantity = :quantity";
            $bindings[':quantity'] = $params['quantity'];
        }

        if (isset($params['id_venda'])) {
            $conditions[] = "id_venda = :id_venda";
            $bindings[':id_venda'] = $params['id_venda'];
        }

        if (isset($params['status'])) {
            $conditions[] = "status = :status";
            $bindings[':status'] = $params['status'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);   
    }

    public function create(array $data)
    {   
        $consumo = $this->model->create(
            $data
        );
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    id_venda,
                    quantity,
                    amount_item,
                    id_produto,
                    id_usuario
                ) VALUES (
                    :uuid,
                    :id_venda,
                    :quantity,
                    :amount_item,
                    :id_produto,
                    :id_usuario
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_venda' => $consumo->id_venda,
                ':quantity' => $consumo->quantity,
                ':amount_item' => $consumo->amount_item,
                ':id_produto' => $consumo->id_produto,
                ':id_usuario' => $consumo->id_usuario,
                ':uuid' => $consumo->uuid
            ]);
    
            $itemVendaId =  (int) $this->conn->lastInsertId();

            $this->conn->commit();

            return $this->findById($itemVendaId);
        } catch (\Throwable $e) {
            var_dump($e->getMessage()); die;
            $this->conn->rollBack();
            return null;
        }
    }

    public function update(array $data, int $id)
    {
        $consumo = $this->model->create(
            $data
        );

        $consumoOld = $this->findById($id);

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    id_produto = :id_produto,
                    amount_item = :amount_item,
                    id_usuario = :id_usuario,
                    quantity = :quantity
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                ':id' => $id,
                ':id_produto' => $consumo->id_produto,
                ':amount_item' => $consumo->amount_item,
                ':quantity' => $consumo->quantity,
                ':id_usuario' => $consumo->id_usuario
            ]);

            if (!$updated) {                
                $this->conn->rollBack();
                return null;
            }

            $this->conn->commit();

            return $this->findById($id);
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
            $this->conn->rollBack();
            return null;
        }
    }

    public function delete($id) 
    {
        $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . " SET status = :status WHERE id = :id"
            );
            
        $deleted = $stmt->execute(
            [
                'status'=> '0',
                'id' => $id
            ]
        );
        
        return $deleted;

    }

    public function deleteAll(array $params) 
    {
        if (empty($params)) {
            return false; 
        }

        $placeholders = rtrim(str_repeat('?,', count($params)), ','); 
    
        $stmt = $this->conn->prepare(
            "UPDATE " . self::TABLE . " SET status = 0 WHERE uuid IN ($placeholders)"
        );
    
        $deleted = $stmt->execute($params);
        
        return $deleted; 
    }
}