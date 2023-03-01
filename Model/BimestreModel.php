<?php

class BimestreModel extends ConexaoModel {

    protected $conexao;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function bimestres()
    {    
        $dados=$this->conexao->query(
            "SELECT 
                * 
            FROM 
                bimestres 
            ORDER BY 
                bimestre 
            ASC"
        );

        $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        return $dados;
    }

    public function preparaInsertBimestre($dados){
        if(empty($dados)){
            return "Não possui dados";
        }

        if($this->verificaBimestre($dados)){
            return "dados não inseridos";
        }

        return $this->insertBimestre((int)$dados);
    }

    private function verificaBimestre($dados){
        $dados=$this->conexao->query(
            "SELECT 
                * 
            FROM 
                bimestres 
            WHERE
                bimestre = '$dados'
            ORDER BY 
                bimestre 
            ASC"
        );

        $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        return $dados;
    }

    private function insertBimestre($codigo)
    {        
      
        $this->conexao->beginTransaction();
        try {            
                       
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    bimestres 
                        (bimestre)
                    VALUES 
                        (
                           :bimestre
                        )"
                );
              $cmd->bindValue(':bimestre',$codigo);
              $cmd->execute();

            $this->conexao->commit();

            return "dados cadastrados!";
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

}