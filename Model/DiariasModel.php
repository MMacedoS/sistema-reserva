<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';

class DiariasModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    
    protected $conexao;

    protected $model = 'diarias';

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function inserirDiaria($dados, $reserva_id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    reserva_id = :reserva_id, 
                    data = :data, 
                    valor = :valor
                    "
                );

            $cmd->bindValue(':reserva_id',$reserva_id);
            $cmd->bindValue(':data',$dados['data']);
            $cmd->bindValue(':valor',$dados['valor']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(200, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }
    
    public function getDadosDiarias($id){
        $cmd  = $this->conexao->query(
            "SELECT 
                *
            FROM 
                diarias
            WHERE 
                reserva_id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::messageWithData(200, 'diarias encontrados', $dados);
        }

        return self::messageWithData(200, 'nenhum dado encontrado', []);
    }
}