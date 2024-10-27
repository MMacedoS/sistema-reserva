<?php

namespace App\Repositories\Balance;

use App\Config\Database;
use App\Models\Balance\Caixa;
use App\Repositories\Traits\FindTrait;

class CaixaRepository {
    const CLASS_NAME = Caixa::class;
    const TABLE = 'caixas';

    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Caixa();
    }

    public function all($params = []) 
    {
        $sql = "
        SELECT 
           p.*           
        FROM " . self::TABLE . " c 
        inner join pagamentos p on c.id = p.id_caixa
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

        if (isset($params['id_usuario'])) {
            $conditions[] = "id_usuario = :id_usuario";
            $bindings[':id_usuario'] = $params['id_usuario'];
        }

        if (isset($params['status'])) {
            $conditions[] = "p.status = :status";
            $bindings[':status'] = $params['status'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);  
    }

    public function findBalanceByUserId($userId)
    {
        if (is_null($userId)) {
            return null;
        }

        $stmt = $this->conn->prepare('SELECT * FROM caixas WHERE id_usuario = :user_id AND status = :status');
        $stmt->execute([
            ':status' => 1,
            ':user_id' => $userId,
        ]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::CLASS_NAME);
        $caixa = $stmt->fetch();  
        if (is_null($caixa)) {
            return null;
        }
    
        return $caixa;
    }

    public function create(array $data)
    {   
        $caixa = $this->model->create(
            $data
        );
        
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    id_usuario,
                    opening_date,
                    starting_balance,
                    current_balance
                ) VALUES (
                    :uuid,
                    :id_usuario,
                    :opening_date,
                    :starting_balance,
                    :current_balance
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':uuid' => $caixa->uuid,
                ':id_usuario' => $caixa->id_usuario,
                ':opening_date' => $caixa->opening_date,
                ':starting_balance' => $caixa->starting_balance,
                ':current_balance' => $caixa->current_balance
            ]);
    
            $caixaId = (int) $this->conn->lastInsertId();

            $this->conn->commit();

            return $this->findById($caixaId);
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
                    id_usuario = :id_usuario,
                    opening_date = :opening_date,
                    starting_balance = :starting_balance,
                    current_balance = :current_balance
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                ':id_usuario' => $pagamento->id_usuario,
                ':opening_date' => $pagamento->opening_date,
                ':starting_balance' => $pagamento->starting_balance,
                ':current_balance' => $pagamento->current_balance,
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