<?php

namespace App\Repositories\Reservate;

use App\Config\Database;
use App\Models\Reservate\Reserva;
use App\Repositories\Traits\FindTrait;

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
                r.*,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id_hospede', h.id_hospede,
                            'nome', h.nome,
                            'email', h.email,
                            'telefone', h.telefone,
                            'cpf', h.cpf,
                            'data_nascimento', h.data_nascimento,
                            'endereco', h.endereco,
                            'is_primary', rh.primary
                        )
                    )
                    FROM clientes h
                    JOIN reserva_hospede rh ON h.id_hospede = rh.id_hospede
                    WHERE rh.id_reserva = r.id_reserva
                ) AS customers
            FROM 
                " . self::TABLE . " r
            ORDER BY 
                r.dt_checkin ASC
        ");
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
                    id_apartamento,
                    dt_checkin,
                    dt_checkout,
                    status_reserva,
                    id_usuario
                ) VALUES (
                    :id_apartamento,
                    :data_checkin,
                    :data_checkout,
                    :status_reserva,
                    :id_usuario
                )
            ";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_apartamento' => $reserva['id_apartamento'],
                ':data_checkin' => $reserva['data_checkin'],
                ':data_checkout' => $reserva['data_checkout'],
                ':status_reserva' => $reserva['status_reserva'],
                ':id_usuario' => $reserva['id_usuario'] ?? null,
            ]);
    
            $reservationId =  (int) $this->conn->lastInsertId();

            if (is_null($reserva['customers'])) {
                $this->conn->rollBack();
                return null;
            }

            $this->insertGuests($reserva['customers'], $reservationId);

            $this->conn->commit();

            return $this->findById($reservationId);
        } catch (\Throwable $e) {
            $this->conn->rollBack();
            return null;
        }
    }

    private function insertGuests(array $guestsData, int $reservationId): void
    {
        $sql = "
            INSERT INTO reserva_rospede (
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

        foreach ($guestsData as $guest) {
            $stmt->execute([
                ':id_reserva' => $reservationId,
                ':id_hospede' => $guest['id_hospede'],
                ':primary' => $guest['primary'],
            ]);
        }
    }

    // public function update(int $id, array $data)
    // {
    //     $customer = $this->model->create(
    //         $data
    //     );

    //     $this->conn->beginTransaction();
    //     try {
    //         $stmt = $this->conn
    //         ->prepare(
    //             "UPDATE " . self::TABLE . "
    //                 set 
    //                 name = :name,
    //                 email = :email,
    //                 phone = :phone,
    //                 address = :address,
    //                 job = :job,
    //                 nationality = :nationality,
    //                 doc = :doc,
    //                 type_doc = :type_doc,
    //                 representative = :representative,
    //                 company = :company,
    //                 cnpj_company = :cnpj_company,
    //                 phone_company = :phone_company,
    //                 email_company = :email_company
    //             WHERE id = :id"
    //         );

    //         $updated = $stmt->execute([
    //             'id' => $id,
    //             ':name' => $customer->name,
    //             ':email' => $customer->email,
    //             ':phone' => $customer->phone,
    //             ':address' => $customer->address,
    //             ':job' => $customer->job,
    //             ':nationality' => $customer->nationality,
    //             ':doc' => $customer->doc,
    //             ':type_doc' => $customer->type_doc,
    //             ':representative' => $customer->representative === 'on' ? 1 : 0,
    //             ':company' => $customer->company,
    //             ':cnpj_company' => $customer->cnpj_company,
    //             ':phone_company' => $customer->phone_company,
    //             ':email_company' => $customer->email_company
    //         ]);

    //         if (!$updated) {                
    //             $this->conn->rollBack();
    //             return null;
    //         }
    //         $this->conn->commit();
    //         return $this->findById($id);
    //     } catch (\Throwable $th) {
    //         $this->conn->rollBack();
    //         return null;
    //     }
    // }

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