<?php

namespace App\Repositories\Product;

use App\Config\Database;
use App\Models\Product\Produto;
use App\Repositories\Traits\FindTrait;

class ProdutoRepository {
    const CLASS_NAME = Produto::class;
    const TABLE = 'produtos';
    
    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Produto();
    }

    public function all($params = [])
    {
        $sql = "
        SELECT 
           p.*,
           estoque.quantity as stock 
        FROM " . self::TABLE . " p 
        left join estoque on estoque.id_produto = p.id
        ";

        $conditions = [];
        $bindings = [];

        if (isset($params['status'])) {
            $conditions[] = "status = :status";
            $bindings[':status'] = $params['status'];
        }

        if (isset($params['search']) && !empty($params['search'])) {
            $conditions[] = "name = :name";
            $bindings[':name'] = $params['search'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY name ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);        
    }

    public function create(array $data)
    {   
        $product = $this->model->create(
            $data
        );
        
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    name,
                    description,
                    price,
                    category,
                    stock,
                    id_usuario
                ) VALUES (
                    :uuid,
                    :name,
                    :description,
                    :price,
                    :category,
                    :stock,
                    :id_usuario
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $product->name,
                ':description' => $product->description,
                ':price' => $product->price,
                ':stock' => $product->stock,
                ':category' => $product->category,
                ':id_usuario' => $product->id_usuario,
                ':uuid' => $product->uuid
            ]);
    
            $productId = (int) $this->conn->lastInsertId();

            $this->conn->commit();

            $this->insertStock($product->stock, $productId);

            return $this->findById($productId);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());die;
            $this->conn->rollBack();
            return null;
        }
    }

    private function insertStock($quantity, $productId) 
    {
        $sql = "
                INSERT INTO estoque (
                    id_produto,
                    quantity
                ) VALUES (
                    :product,
                    :quantity
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':product' => $productId,   
                ':quantity' => $quantity            
            ]);
    
    }

    public function update(array $data, int $id)
    {
        $product = $this->model->create(
            $data
        );

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    name = :name,
                    description = :description,
                    price = :price,
                    category = :category,
                    stock = :stock,
                    id_usuario = :id_usuario
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                'id' => $id,
                ':name' => $product->name,
                ':description' => $product->description,
                ':price' => $product->price,
                ':stock' => $product->stock,
                ':category' => $product->category,
                ':id_usuario' => $product->id_usuario,
            ]);

            if (!$updated) {                
                $this->conn->rollBack();
                return null;
            }
            $this->conn->commit();
            $this->updateStock($product->stock, $id);
            return $this->findById($id);
        } catch (\Throwable $th) {
            $this->conn->rollBack();
            return null;
        }
    }

    private function updateStock($quantity, $productId) 
    {
        $sql = "
                UPDATE estoque set 
                    quantity = :quantity
                where id_produto = :id_product
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_product' => $productId,   
                ':quantity' => $quantity            
            ]);
    
    }

    public function delete(int $id) 
    {
        $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . " SET status = 0 WHERE id = :id"
            );
        $deleted = $stmt->execute(['id' => $id]);
        
        return $deleted;

    }
}