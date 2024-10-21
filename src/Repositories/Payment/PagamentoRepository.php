<?php

namespace App\Repositories\Payment;

use App\Config\Database;
use App\Models\Payment\Pagamento;
use App\Repositories\Traits\FindTrait;

class PagamentoRepository {
    const CLASS_NAME = Pagamento::class;
    const TABLE = 'pagamentos';

    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Pagamento();
    }

    public function all($params = []) {
        $sql = "
        SELECT 
           p.*           
        FROM " . self::TABLE . " p 
        ";
        
        $conditions = [];
        $bindings = [];

        if (isset($params['type_payment'])) {
            $conditions[] = "type_payment = :type_payment";
            $bindings[':type_payment'] = $params['type_payment'];
        }

        if (isset($params['reserve_id'])) {
            $conditions[] = "id_reserva = :reserve_id";
            $bindings[':reserve_id'] = $params['reserve_id'];
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
        $pagamento = $this->model->create(
            $data
        );
        
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    id_reserva,
                    id_usuario,
                    type_payment,
                    payment_amount,
                    dt_payment,
                    id_venda,
                    id_caixa
                ) VALUES (
                    :uuid,
                    :id_reserva,
                    :id_usuario,
                    :type_payment,
                    :payment_amount,
                    :dt_payment,
                    :venda_id,
                    :id_caixa
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_reserva' => $pagamento->id_reserva,
                ':id_usuario' => $pagamento->id_usuario,
                ':type_payment' => $pagamento->type_payment,
                ':payment_amount' => $pagamento->payment_amount,
                ':dt_payment' => $pagamento->dt_payment,
                ':venda_id' => $pagamento->id_venda,
                ':id_caixa' => $pagamento->id_caixa,
                ':uuid' => $pagamento->uuid
            ]);
    
            $pagamentoId = (int) $this->conn->lastInsertId();

            $this->conn->commit();

            return $this->findById($pagamentoId);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());die;
            $this->conn->rollBack();
            return null;
        }
    }

    public function update(array $data, int $id)
    {
        $pagamento = $this->model->create(
            $data
        );

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    id_reserva = :id_reserva,
                    id_usuario = :id_usuario,
                    type_payment = :type_payment,
                    payment_amount = :payment_amount,
                    dt_payment = :dt_payment,
                    id_venda = :venda_id,
                    id_caixa = :id_caixa
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                ':id_reserva' => $pagamento->id_reserva,
                ':id_usuario' => $pagamento->id_usuario,
                ':type_payment' => $pagamento->type_payment,
                ':payment_amount' => $pagamento->payment_amount,
                ':dt_payment' => $pagamento->dt_payment,
                ':venda_id' => $pagamento->id_venda,
                ':id_caixa' => $pagamento->id_caixa,
                ':id' => $id
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