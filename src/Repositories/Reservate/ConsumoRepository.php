<?php

namespace App\Repositories\Reservate;

use App\Config\Database;
use App\Models\Consumers\Consumo;
use App\Repositories\Traits\FindTrait;

class ConsumoRepository {
    const CLASS_NAME = Consumo::class;
    const TABLE = 'consumos';
    
    use FindTrait;
    protected $conn;
    protected $model;
    protected $reservaRepository;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Consumo();
        $this->reservaRepository = new ReservaRepository();
    }

    public function all(array $params)
    {
        $sql = "
        SELECT 
           c.*,
           (
            SELECT 
               JSON_OBJECT(
                   'id', p.id,
                   'name', p.name,
                   'price', p.price
                )
            FROM produtos p
            WHERE p.id = c.id_produto
        ) AS products
        FROM " . self::TABLE . " c 
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
        $consumo = $this->model->create(
            $data
        );
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    id_reserva,
                    quantity,
                    amount,
                    id_produto,
                    id_usuario
                ) VALUES (
                    :uuid,
                    :id_reserva,
                    :quantity,
                    :amount,
                    :id_produto,
                    :id_usuario
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_reserva' => $consumo->id_reserva,
                ':quantity' => $consumo->quantity,
                ':amount' => $consumo->amount,
                ':id_produto' => $consumo->id_produto,
                ':id_usuario' => $consumo->id_usuario,
                ':uuid' => $consumo->uuid
            ]);
    
            $reservationId =  (int) $this->conn->lastInsertId();

            $this->conn->commit();

            return $this->findById($reservationId);
        } catch (\Throwable $e) {
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
                    amount = :amount,
                    id_usuario = :id_usuario,
                    quantity = :quantity
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                ':id' => $id,
                ':id_produto' => $consumo->id_produto,
                ':amount' => $consumo->amount,
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
            "DELETE FROM " . self::TABLE . " WHERE uuid IN ($placeholders)"
        );
    
        $deleted = $stmt->execute($params);
        
        return $deleted; 
    }
}