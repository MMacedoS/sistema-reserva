<?php

namespace App\Repositories\Reservate;

use App\Config\Database;
use App\Models\Reservate\Reserva;
use App\Repositories\Traits\FindTrait;
use PDO;

class ReservaRepository {
    const CLASS_NAME = Reserva::class;
    const TABLE = 'reservas';
    
    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Reserva();
    }

    public function all()
    {
        $stmt = $this->conn->query(
            "SELECT 
                r.*, a.name as apartament,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id_guest', h.id,
                            'name', h.name,
                            'is_primary', rh.is_primary
                        )
                    )
                    FROM clientes h
                    JOIN reserva_hospedes rh ON h.id = rh.id_hospede
                    WHERE rh.id_reserva = r.id
                ) AS customers
            FROM 
                " . self::TABLE . " r
            
            LEFT JOIN apartamentos a ON a.id = r.id_apartamento
            WHERE r.status != 'Apagada'
            ORDER BY 
                r.dt_checkin ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);        
    }

    public function allHosted(array $params = [ 'status' => 'Hospedada'])
    {
        $sql = "
        SELECT 
            r.*, a.name as apartament,
            (
                SELECT JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id_guest', h.id,
                        'name', h.name,
                        'is_primary', rh.is_primary
                    )
                )
                FROM clientes h
                JOIN reserva_hospedes rh ON h.id = rh.id_hospede
                WHERE rh.id_reserva = r.id
            ) AS customers
        FROM " . self::TABLE . " r
        LEFT JOIN apartamentos a ON a.id = r.id_apartamento
        ";
        
        $conditions = [];
        $bindings = [];

        if (isset($params['status'])) {
            $conditions[] = "r.status = :status";
            $bindings[':status'] = $params['status'];
        }

        if (isset($params['apartament_id'])) {
            $conditions[] = "r.id_apartamento = :apartament_id";
            $bindings[':apartament_id'] = $params['apartament_id'];
        }

        if (isset($params['date_start']) && isset($params['date_end'])) {
            $conditions[] = "r.dt_checkin BETWEEN :date_start AND :date_end";
            $bindings[':date_start'] = $params['date_start'];
            $bindings[':date_end'] = $params['date_end'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY apartament ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);      
    }

    public function create(array $data)
    {   
        $reserva = $this->model->create(
            $data
        );
        $this->conn->beginTransaction();
        try {
            $sql = "
                INSERT INTO reservas (
                    uuid,
                    id_apartamento,
                    dt_checkin,
                    dt_checkout,
                    amount,
                    status,
                    id_usuario
                ) VALUES (
                    :uuid,
                    :id_apartamento,
                    :data_checkin,
                    :data_checkout,
                    :amount,
                    :status_reserva,
                    :id_usuario
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_apartamento' => $reserva->id_apartamento,
                ':data_checkin' => $reserva->dt_checkin,
                ':data_checkout' => $reserva->dt_checkout,
                ':amount' => $reserva->amount,
                ':status_reserva' => $reserva->status,
                ':id_usuario' => $reserva->id_usuario,
                ':uuid' => $reserva->uuid
            ]);
    
            $reservationId =  (int) $this->conn->lastInsertId();

            if (is_null($reserva->customers)) {
                $this->conn->rollBack();
                return null;
            }

            $this->insertGuests($reserva->customers, $reservationId);

            $this->conn->commit();

            return $this->findById($reservationId);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());die;
            $this->conn->rollBack();
            return null;
        }
    }

    private function insertGuests(array $guestsData, int $reservationId): void
    {
        $sql = "
            INSERT INTO reserva_hospedes (
                id_reserva,
                id_hospede,
                is_primary
            ) VALUES (
                :id_reserva,
                :id_hospede,
                :primary
            )
        ";

        $stmt = $this->conn->prepare($sql);

        foreach ($guestsData as $guest => $value) {
            $stmt->execute([
                ':id_reserva' => $reservationId,
                ':id_hospede' => $value,
                ':primary' => 1,
            ]);
        }
    }

    private function updateGuests(array $guestsData, int $reservationId): void
    {
        $sql = "DELETE FROM reserva_hospedes WHERE id_reserva = :reservation_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':reservation_id' => $reservationId
        ]);

        $this->insertGuests($guestsData, $reservationId);
    }

    public function update(array $data, int $id)
    {
        $reserve = $this->model->create(
            $data
        );

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    id_apartamento = :id_apartamento,
                    dt_checkin = :dt_checkin,
                    dt_checkout = :dt_checkout,
                    amount = :amount,
                    status = :status,
                    id_usuario = :id_usuario
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                'id' => $id,
                ':id_apartamento' => $reserve->id_apartamento,
                ':dt_checkin' => $reserve->dt_checkin,
                ':dt_checkout' => $reserve->dt_checkout,
                ':amount' => $reserve->amount,
                ':status' => $reserve->status,
                ':id_usuario' => $reserve->id_usuario
            ]);

            if (!$updated) {                
                $this->conn->rollBack();
                return null;
            }
            $this->updateGuests($reserve->customers, $id);
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
                "UPDATE " . self::TABLE . " SET status ='Apagada' WHERE id = :id"
            );
        $deleted = $stmt->execute(['id' => $id]);
        
        return $deleted;

    }

    public function findByIdWithCustomers($id)
    {
        $stmt = $this->conn->query(
            "SELECT 
                r.*,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id_guest', h.id,
                            'name', h.name,
                            'is_primary', rh.is_primary
                        )
                    )
                    FROM clientes h
                    JOIN reserva_hospedes rh ON h.id = rh.id_hospede
                    WHERE rh.id_reserva = r.id
                ) AS customers
            FROM 
                " . self::TABLE . " r 
            WHERE r.id = " . $id);
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::CLASS_NAME);
        return $stmt->fetch();
    }

    public function buscaMapaReservas($startDate,$endDate)
    {
        try {      
            $cmd = $this->conn->prepare(
                "SELECT all_dates.date_value AS start, IFNULL(count(r.dt_checkin), 0) AS title
                FROM (
                    SELECT DATE_ADD(:entrada, INTERVAL n.num DAY) AS date_value
                    FROM (
                        SELECT (a.a + (10 * b.a) + (100 * c.a)) num
                        FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
                        CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                        CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) c
                    ) n
                    WHERE DATE_ADD(:entrada, INTERVAL n.num DAY) <= :saida
                ) all_dates
                LEFT JOIN reservas r ON r.dt_checkin <= all_dates.date_value AND r.dt_checkout >= all_dates.date_value
                where r.status NOT IN ('Finalizada', 'Cancelada', 'Apagada')
                GROUP BY all_dates.date_value
                ORDER BY all_dates.date_value                
                "
                );

            $cmd->bindValue(':entrada', $startDate);
            $cmd->bindValue(':saida',$endDate);
            $cmd->execute();
            
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);

            $eventos_fullcalendar = [];
            foreach ($dados as $evento) {
                if($evento['title'] > 0) {
                    $evento_fullcalendar = [
                        'title' => $evento['title'] . " Apt reservados", // Título do evento, com a quantidade de reservas
                        'qtde' => $evento['title'],
                        'start' => $evento['start'], // Data de início da reserva
                    ];
                    $eventos_fullcalendar[] = $evento_fullcalendar;
                }
            }
        return $eventos_fullcalendar;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function allCheckin (array $params = null)
    {
        $stmt = $this->conn->query(
            "SELECT 
                r.*, a.name as apartament,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id_guest', h.id,
                            'name', h.name,
                            'is_primary', rh.is_primary
                        )
                    )
                    FROM clientes h
                    JOIN reserva_hospedes rh ON h.id = rh.id_hospede
                    WHERE rh.id_reserva = r.id
                ) AS customers
            FROM 
                " . self::TABLE . " r
            LEFT JOIN apartamentos a ON a.id = r.id_apartamento
            WHERE r.dt_checkin BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()
              AND r.status IN ('Confirmada', 'Reservada')
            ORDER BY 
                r.dt_checkin ASC"
        );
        
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);        
    }

    public function updateToCheckin($reserve, int $id)
    {
        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . "
                    set 
                    id_apartamento = :id_apartamento,
                    dt_checkin = :dt_checkin,
                    dt_checkout = :dt_checkout,
                    amount = :amount,
                    status = :status,
                    id_usuario = :id_usuario
                WHERE id = :id"
            );

            $updated = $stmt->execute([
                'id' => $id,
                ':id_apartamento' => $reserve->id_apartamento,
                ':dt_checkin' => $reserve->dt_checkin,
                ':dt_checkout' => $reserve->dt_checkout,
                ':amount' => $reserve->amount,
                ':status' => $reserve->status,
                ':id_usuario' => $reserve->id_usuario
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

    public function allByHospedated()
    {
        $stmt = $this->conn->query(
            "SELECT 
                r.*, a.name as apartament,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id_guest', h.id,
                            'name', h.name,
                            'is_primary', rh.is_primary
                        )
                    )
                    FROM clientes h
                    JOIN reserva_hospedes rh ON h.id = rh.id_hospede
                    WHERE rh.id_reserva = r.id
                ) AS customers
            FROM 
                " . self::TABLE . " r
            
            LEFT JOIN apartamentos a ON a.id = r.id_apartamento
            WHERE r.status = 'Hospedada'
            ORDER BY 
                r.dt_checkin ASC
        ");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);        
    }
}