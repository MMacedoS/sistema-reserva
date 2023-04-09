<?php

class AppModel extends ConexaoModel {

    protected $conexao;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function insertApagados($dados)
    {
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    apagado 
                SET 
                    funcionario = :funcionario, 
                    dados = :dados
                    "
                );

            $cmd->bindValue(':dados', json_encode($dados));
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $dados = $cmd->execute();

           
            return true;

        } catch (\Throwable $th) {
            $this->conexao->rollback();
        }
    }
}