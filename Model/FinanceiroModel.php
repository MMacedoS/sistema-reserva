<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';
require_once 'Trait/DateModelTrait.php';

class FinanceiroModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    use DateModelTrait;
    
    protected $conexao;

    protected $model = 'movimento';

    public function __construct() 
    {
        $this->model = 'movimento';
        $this->conexao = ConexaoModel::conexao();
    }

    public function buscaMovimentos($dados)
    {
        
        if(is_null($dados) || empty($dados)){
            return $this->buscaMovimentoComOffset(0);    
        }
        $dados = explode('_@_', $dados);   
        
        if(count($dados) == 1){
            $chave = str_replace('page=', '', $dados[0]);
            return $this->buscaMovimentoComOffset($chave);    
        }
        
        return $this->buscaMovimentoComParams(
            $dados[1],
            $dados[2], 
            $dados[3]
        );
    }

    private function buscaMovimentoComParams($texto, $entrada, $saida)
    {
        $entrada = date($entrada . ' 00:00:00');
        $saida = date($saida . ' 23:59:00');
        $sql = "SELECT 
                    * 
                FROM
                    $this->model
                WHERE
                    created_at between '$entrada' and '$saida' ";


        if(!empty($texto)){
            if($texto == 1){
                
                $sql.= "
                AND
                  entrada_id != ''                 
                ";
            }

            if($texto == 2){
                $sql.= "
                AND
                  saida_id != ''                 
                ";
            }
        }
        
        $cmd  = $this->conexao->query(
            $sql
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    private function buscaMovimentoComOffset($off = 0)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                * 
            FROM
                $this->model
            ORDER BY
                id DESC

            LIMIT 12 offset $off "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function buscaSaida($off = 0)
    {
        if(is_null($off) || empty($off))
        {
            return $this->saida($off = 0);
        } 
        $dados = explode('_@_', $off);   

        if(count($dados) == 1)
        {
            $chave = str_replace('page=', '', $dados[0]);
            return $this->saida($chave);
        }

        return $this->saidaComParams(
            $dados[1],
            $dados[2], 
            $dados[3]
        );
    }

    public function saida($off = 0)
    {
       
        $cmd  = $this->conexao->query(
            "SELECT 
                * 
            FROM
                saida
            ORDER BY
                id DESC
            LIMIT 12 offset $off "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function saidaComParams($texto = 0, $entrada, $saida)
    {
        $entrada = date($entrada . ' 00:00:00');
        $saida = date($saida . ' H:i:s');
       
        $sql  = "SELECT 
                    * 
                FROM
                    saida
                WHERE
                    created_at between '$entrada' and '$saida' 
                ";

        if(!empty($texto)){
                
            $sql.= "
            AND
               tipoPagamento = $texto                 
            ";
        }

        $cmd  = $this->conexao->query(
            $sql
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function insertSaida($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    saida
                SET 
                    valor = :valor, 
                    descricao = :descricao, 
                    tipoPagamento = :tipopagamento,
                    tipo = :tipo,
                    funcionario = :funcionario"
                );

            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':descricao',$dados['descricao']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':tipopagamento',$dados['pagamento']);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function deleteSaida($id)
    {
        $this->model = 'saida';

        $dados = self::findById($id)['data'][0];

        $dados['tabela'] = "saida";

        $appModel = new AppModel();
        
        $appModel->insertApagados($dados);

        $cmd  = $this->conexao->query(
            "DELETE 
                FROM 
                saida
                WHERE
                    id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::message(200, 'Saida deletado');
        }

        return self::message(422, 'Nenhum dado encontrado');
    }

    public function findMovimentosByParams(
        $descricao = '', 
        $dataEntrada = '', 
        $dataSaida = '', 
        $situacao = '' )
    {
        try {
            $sql = "SELECT 
                m.id,
                m.descricao,
                m.valor,
                m.tipo,
                m.entrada_id,
                m.saida_id
            FROM 
                movimento m                 
            WHERE
                m.descricao LIKE '%$descricao%'
            AND
                m.created_at BETWEEN '$dataEntrada' AND '$dataSaida' 
            ";


            if(!empty($situacao)){
                if($situacao == 1){
                    
                    $sql.= "
                    AND
                    m.entrada_id != ''                 
                    ORDER BY
                    m.id DESC   
                    ";
                }

                if($situacao == 2){
                    $sql.= "
                    AND
                    m.saida_id != '' 
                    ORDER BY
                    m.id DESC                 
                    ";
                }
            }
            $cmd = $this->conexao->query($sql);
    
            if($cmd->rowCount() > 0)
            {
                return $cmd->fetchAll(PDO::FETCH_ASSOC);
            }
    
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
     }


     public function findPagamentosByParams(
        $descricao = '', 
        $dataEntrada = '', 
        $dataSaida = ''
    ) {
        try {
            $sql = "SELECT 
                r.id,
                r.dataEntrada,
                r.dataSaida,
                r.valor,
                h.nome, 
                a.numero,
                COALESCE((SELECT sum(valorUnitario * quantidade) FROM consumo c where c.reserva_id = r.id), 0) as consumos,
                COALESCE((SELECT sum(valor) FROM diarias d where d.reserva_id = r.id), 0) as diarias,
                COALESCE((SELECT sum(p.valorPagamento) FROM pagamento p where p.reserva_id = r.id), 0) as pag
            FROM 
                `reserva` r 
            INNER JOIN 
                hospede h 
            on 
                r.hospede_id = h.id 
            INNER JOIN 
                apartamento a 
            on 
                a.id = r.apartamento_id 
            WHERE 
            r.status = 4 
            AND
            h.nome LIKE '%$descricao%'
            AND
            (
                (r.dataEntrada BETWEEN '$dataEntrada' and '$dataSaida')
                OR
                (r.dataSaida BETWEEN '$dataEntrada' and '$dataSaida')
            ); 
            ";
            $cmd = $this->conexao->query($sql);
    
            if($cmd->rowCount() > 0)
            {
                return $cmd->fetchAll(PDO::FETCH_ASSOC);
            }
    
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
     }
}