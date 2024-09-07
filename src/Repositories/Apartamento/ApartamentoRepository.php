<?php

namespace App\Repositories\Apartamento;

use App\Config\Database;
use App\Models\Apartamento\Apartamento;
use App\Repositories\Traits\FindTrait;

class ApartamentoRepository {
    const CLASS_NAME = Apartamento::class;
    const TABLE = 'apartamentos';
    
    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $conn = new Database();
        $this->conn = $conn->getConnection();
        $this->model = new Apartamento();
    }

    public function all()
    {
        $stmt = $this->conn->query("SELECT * FROM " . self::TABLE . " order by name ASC");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);        
    }

    public function create(array $data)
    {   
        $apartamento = $this->model->create(
            $data
        );

        try {
            $stmt = $this->conn
            ->prepare(
                "INSERT INTO " . self::TABLE . " (
                    uuid, 
                    name, 
                    description, 
                    category, 
                    status
                ) VALUES (
                    :uuid, 
                    :name, 
                    :description, 
                    :category, 
                    :status
                )
            ");
            $create = $stmt->execute([
                'uuid' => $apartamento->uuid,
                'name' => $apartamento->name,
                'description' => $apartamento->description,
                'category' => $apartamento->category,
                'status' => $apartamento->status
            ]);
    
            if (is_null($create)) {
                return null;
            }
    
            return $this->findById($this->conn->lastInsertId());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function update(int $id, array $data)
    {
        $apartamento = $this->model->create(
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
                    category = :category, 
                    status = :status 
                WHERE id = :id"
            );
            $updated = $stmt->execute([
                'id' => $id,
                'name' => $apartamento->name,
                'description' => $apartamento->description,
                'category' => $apartamento->category,
                'status' => $apartamento->status
            ]);

            if (!$updated) {                
                $this->conn->rollBack();
                return null;
            }
            $this->conn->commit();
            return $this->findById($id);
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
            die;
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

    public function findAvailableApartments($checkin, $checkout) 
    {
        $stmt = $this->conn
        ->prepare(
            "SELECT a.id, a.name
            FROM apartamentos a
            WHERE a.status != 0
            AND a.id NOT IN (
                SELECT r.id_apartamento
                FROM reservas r
                WHERE r.status <= 3
                AND (
                    (r.dt_checkin >= :checkin and r.dt_checkin < :checkout)
                    OR 
                    (r.dt_checkout > :checkin and r.dt_checkout <= :checkout)
                    OR 
                    (dt_checkin <= :checkin and r.dt_checkout >= :checkout)
                )
            )"
        );
        $stmt->bindParam(':checkin', $checkin);
        $stmt->bindParam(':checkout', $checkout);
        $stmt->bindParam(':reserva_id', $reservaId);

        // Executa a consulta
        $stmt->execute();
        $apartamentos = $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);

        if (is_null($apartamentos)) {
            return null;
        }

        return $apartamentos;
    }

    public function findApartmentByIdReserve(int $idReservation)
    {
        $stmt = $this->conn->prepare(
            "SELECT a.id, a.name
            FROM apartamentos a
            INNER JOIN reservas r ON a.id = r.id_apartamento
            WHERE r.id = :idReservation"
        );
    
        // Vincula o parâmetro :idReservation à consulta
        $stmt->bindParam(':idReservation', $idReservation, \PDO::PARAM_INT);
    
        // Executa a consulta
        $stmt->execute();
    
        // Recupera o resultado como um objeto da classe especificada
        $apartamento = $stmt->fetch(\PDO::FETCH_OBJ);
    
        // Verifica se algum apartamento foi encontrado
        if ($apartamento === false) {
            return null;  // Retorna null se não encontrar o apartamento
        }
    
        return $apartamento;  // Retorna o apartamento encontrado
    }
    
}