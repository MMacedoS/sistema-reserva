<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';

class HospedeModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    
    protected $conexao;

    protected $model = 'hospede';

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function prepareInsertHospede($dados)
    {

        // $validation = self::requiredParametros($dados);
        $validation = null;
        if(empty($dados['nome']))
        {
            return self::message(422, 'preencha o nome do Hóspede');
        }

        if(is_null($validation)){
            
            if($this->verificaHospedesSeExiste($dados))
            {   
                return $this->insertHospede($dados); 
            }

            return self::message(422, 'Hospedes existente!');
        }

        return $validation;
    }

    private function verificaHospedesSeExiste($dados)
    {
        $email = (string)$dados['email'];
        $nome = (string)$dados['nome'];
        
        if (empty($email)) {
            return true;    
        }
        
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM
                $this->model
            WHERE
                email = '$email'"
        );

        if($cmd->rowCount()>0)
        {
            return false;
        }

        return true;
    }

    private function insertHospede($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    nome = :nome, 
                    email = :email, 
                    cpf = :cpf,
                    status = :status,
                    telefone = :telefone,
                    tipo = :tipo,
                    endereco = :endereco
                    "
                );

            $cmd->bindValue(':nome',$dados['nome']);
            $cmd->bindValue(':email',$dados['email']);
            $cmd->bindValue(':status',$dados['status']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':endereco',$dados['endereco']);
            $cmd->bindValue(':telefone',$dados['telefone']);
            $cmd->bindValue(':cpf',$dados['cpf']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareUpdateHospede($dados, $id)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){            
            return $this->updateHospede($dados, $id); 
        }

        return $validation;
    }

    private function updateHospede($dados, int $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    nome = :nome, 
                    email = :email, 
                    cpf = :cpf,
                    status = :status,
                    telefone = :telefone,
                    tipo = :tipo,
                    endereco = :endereco
                WHERE 
                    id = :id"
                );

            $cmd->bindValue(':nome',$dados['nome']);
            $cmd->bindValue(':email',$dados['email']);
            $cmd->bindValue(':status',$dados['status']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':endereco',$dados['endereco']);
            $cmd->bindValue(':telefone',$dados['telefone']);
            $cmd->bindValue(':cpf',$dados['cpf']);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados Atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function findHospedes($request)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                * 
            FROM
                $this->model
            WHERE 
                email
            LIKE
                '%$request%'
            OR
                nome
            LIKE
                '%$request%'"
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
        
    }

    public function prepareChangedHospede($id)
    {
        $hospedes = self::findById($id);

        if(is_null($hospedes)) {
            return self::messageWithData(422, 'hospedes não encontrado', []);
        }

        $hospedes['data'][0]['status'] == '1' ? $status = 0 : $status = 1;
        return $this->updateStatusHospede(
            $status,
            $id);
    }

    private function updateStatusHospede($status, $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    status = :status
                WHERE 
                    id = :id"
                );
            $cmd->bindValue(':status',$status);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }
}