<?php

namespace App\Repositories\Reservate;

use App\Config\Database;
use App\Models\Reservate\Diaria;
use App\Repositories\Traits\FindTrait;
use App\Utils\LoggerHelper;

class DiariaRepository {
    const CLASS_NAME = Diaria::class;
    const TABLE = 'diarias';
    
    use FindTrait;
    protected $conn;
    protected $model;
    protected $reservaRepository;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Diaria();
        $this->reservaRepository = new ReservaRepository();
    }

    public function all(array $params)
    {
        $sql = "
        SELECT 
           *
        FROM " . self::TABLE . " 
        ";
        
        $conditions = [];
        $bindings = [];

        if (isset($params['status'])) {
            $conditions[] = "status = :status";
            $bindings[':status'] = $params['status'];
        }

        if (isset($params['reserve_id'])) {
            $conditions[] = "id_reserva = :reserve_id";
            $bindings[':reserve_id'] = $params['reserve_id'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY  dt_daily DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);   
    }

    public function create(array $data)
    {   
        $diaria = $this->model->create(
            $data
        );
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO " . self::TABLE . " (
                    uuid,
                    id_reserva,
                    dt_daily,
                    amount,
                    status,
                    id_usuario
                ) VALUES (
                    :uuid,
                    :id_reserva,
                    :dt_daily,
                    :amount,
                    :status_reserva,
                    :id_usuario
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_reserva' => $diaria->id_reserva,
                ':dt_daily' => $diaria->dt_daily,
                ':amount' => $diaria->amount,
                ':status_reserva' => $diaria->status,
                ':id_usuario' => $diaria->id_usuario,
                ':uuid' => $diaria->uuid
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
        $diaria = $this->model->create(
            $data
        );

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    id_reserva = :id_reserva,
                    dt_daily = :dt_daily,
                    amount = :amount,
                    status = :status,
                    id_usuario = :id_usuario
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                'id' => $id,
                ':id_reserva' => $diaria->id_reserva,
                ':dt_daily' => $diaria->dt_daily,
                ':amount' => $diaria->amount,
                ':status' => $diaria->status,
                ':id_usuario' => $diaria->id_usuario
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
                "UPDATE " . self::TABLE . " SET status = 0 WHERE id = :id"
            );
        $deleted = $stmt->execute(['id' => $id]);
        
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
    

    public function generateDaily() 
    {
        $reservates = $this->reservaRepository->allByHospedated();

        if (empty($reservates)) {
            return null;
        }

        try {            
            $now = new \DateTime();
            $diaries_generate = 0;
            foreach ($reservates as $reserve) {
                $daily_last =  $this->getLastDaily($reserve->id);
                $dt_daily_last = $daily_last ? new \DateTime($daily_last->dt_daily) : new \DateTime($reserve->dt_checkin);

                while ($dt_daily_last <= $now) {
                    if (!$this->hasExistDaily($reserve->id, $dt_daily_last->format('Y-m-d'))) {
                        $data = [
                            'id_reserva' => $reserve->id,
                            'amount' => $reserve->amount,
                            'dt_daily' => $dt_daily_last->format('Y-m-d'),
                            'id_usuario' => 1 
                        ];
                
                        $created = $this->create($data);
                
                        if (is_null($created)) {
                            break; 
                        }
                            
                        $diaries_generate++;
                    }
                    
                    $dt_daily_last->modify("+1 day");
                }
            }
            return $diaries_generate . " diarias geradas ";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }     
    }

    private function getLastDaily(int $daily)
    {
        $stmt = $this->conn
            ->prepare(
                "SELECT * FROM " . self::TABLE . "  WHERE id_reserva = :id ORDER BY dt_daily"
            );
        $stmt->bindParam(':id', $daily, \PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::CLASS_NAME);
        $result = $stmt->fetch();  
        return $result ? $result : null;
    }

    private function hasExistDaily(int $id_reserve, $dt_daily)
    {
        $sqlCheck = "SELECT COUNT(*) as count
                     FROM " . self::TABLE . " 
                     WHERE id_reserva = :id_reserva
                     AND dt_daily = :data_diaria";
        
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':id_reserva', $id_reserve, \PDO::PARAM_INT);
        $stmtCheck->bindParam(':data_diaria', $dt_daily, \PDO::PARAM_STR);
        $stmtCheck->execute();

        $resultCheck = $stmtCheck->fetch(\PDO::FETCH_ASSOC);
        
        if ($resultCheck['count'] > 0) {
            return true;
        }

        return false;
    }
}