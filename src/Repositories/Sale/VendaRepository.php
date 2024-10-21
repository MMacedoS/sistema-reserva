<?php

namespace App\Repositories\Sale;

use App\Config\Database;
use App\Models\Sale\Venda;
use App\Repositories\Traits\FindTrait;

class VendaRepository {
    const CLASS_NAME = Venda::class;
    const TABLE = 'vendas';
    
    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Venda();
    }
    
    public function all(array $params)
    {
        $sql = "
        SELECT 
           v.*,           
        (
            SELECT COALESCE(SUM(iv.amount_item * iv.quantity), 0)
            FROM itens_Venda iv
            WHERE iv.id_venda = v.id and iv.status = 1
        ) AS total_consumptions,
        (
            SELECT COALESCE(SUM(p.payment_amount), 0)
            FROM pagamentos p
            WHERE p.id_venda = v.id and p.status = 1
        ) AS total_payments 
        FROM " . self::TABLE . " v 
        ";
        
        $conditions = [];
        $bindings = [];

        if (isset($params['quantity'])) {
            $conditions[] = "quantity = :quantity";
            $bindings[':quantity'] = $params['quantity'];
        }

        if (isset($params['reserve_id'])) {
            $conditions[] = "id_reserva = :reserve_id";
            $bindings[':reserve_id'] = $params['reserve_id'];
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
        $sale = $this->model->create(
            $data
        );
        
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    name,
                    description,
                    amount_sale,
                    id_usuario,
                    dt_sale,
                    id_reserva
                ) VALUES (
                    :uuid,
                    :name,
                    :description,
                    :amount_sale,
                    :id_usuario,
                    :dt_sale,
                    :id_reserva
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $sale->name,
                ':description' => $sale->description,
                ':amount_sale' => $sale->amount_sale,
                ':id_usuario' => $sale->id_usuario,
                ':id_reserva' => $sale->id_reserva,
                ':dt_sale' => $sale->dt_sale,
                ':uuid' => $sale->uuid
            ]);
    
            $saleId = (int) $this->conn->lastInsertId();

            $this->conn->commit();

            return $this->findById($saleId);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());die;
            $this->conn->rollBack();
            return null;
        }
    }

    public function update(array $data, int $id)
    {
        $sale = $this->model->create(
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
                    amount_sale = :amount_sale,
                    dt_sale = :dt_sale,
                    id_usuario = :id_usuario,
                    id_reserva = :id_reserva
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                'id' => $id,
                ':name' => $sale->name,
                ':description' => $sale->description,
                ':amount_sale' => $sale->amount_sale,
                ':dt_sale' => $sale->dt_sale,
                ':id_usuario' => $sale->id_usuario,
                ':id_reserva' => $sale->id_reserva,
            ]);

            if (!$updated) {                
                $this->conn->rollBack();
                return null;
            }
            $this->conn->commit();
            return $this->findById($id);
        } catch (\Throwable $th) {
            

        var_dump($th->getMessage());
        die();
            $this->conn->rollBack();
            return null;
        }
    }

    public function allItems(array $params)
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

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);   
    }
}