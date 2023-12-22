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
                and status = 1
            order by id DESC
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::messageWithData(200, 'diarias encontrados', $dados);
        }

        return self::messageWithData(200, 'nenhum dado encontrado', []);
    }

    public function findDiariasById($id)
    {
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM
                diarias
            WHERE
                id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {

            return self::messageWithData(200,'Dados encontrados', $cmd->fetchAll(PDO::FETCH_ASSOC));
        }

        return false;
    }

    public function updateDiarias($dados, $id)
    {
        $valor = $dados['valor'];
        $data =  $dados['data'];
        
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    diarias
                SET 
                    data = :data, 
                    valor = :valor
                WHERE 
                    id = :id
                    "
                );

            $cmd->bindValue(':data',$data);
            $cmd->bindValue(':valor',$valor);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(200, "dados atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    private function removerValoresDiaria($idReserva) {
        $cmd = $this->conexao->prepare(
            "DELETE FROM
                diarias
            WHERE   
                reserva_id = :reserva_id"
            );

        $cmd->bindValue(':reserva_id', $idReserva);
        $cmd->execute();
    }

    public function calculeReserva(int $id)
    {
        $reserva = self::findById($id);
            
        if($reserva['status'] == 3) {
            $this->removerValoresDiaria($id);

            $this->inserirValoresDiaria(
                $reserva['dataEntrada'], 
                $reserva['dataSaida'], 
                $reserva['valor'] ,
                 $id
             );

         }
    }

    public function inserirValoresDiaria($dataInicial, $dataFinal, $valor, $idReserva) {
        $dataAtual = new DateTime($dataInicial);
        $dataFim = new DateTime($dataFinal);

        while ($dataAtual < $dataFim) {
            $valorData = $dataAtual->format('Y-m-d');

            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    diarias
                SET 
                    reserva_id = :reserva_id, 
                    data = :data, 
                    valor = :valor
                    "
                );

            $cmd->bindValue(':reserva_id', $idReserva);
            $cmd->bindValue(':data', $valorData);
            $cmd->bindValue(':valor', $valor);
            $cmd->execute();
            $dataAtual->modify('+1 day');
        }
    }

    public function getRemoveDiarias($id, $motivo)
    {
        $this->conexao->beginTransaction();
        try {      
            $dados = $this->findById($id);
            $dados = $dados ?? null;

            if (is_null($dados)) {              
                $this->conexao->rollback();
                return null;
            }

            $this->prepareStatusTable('diarias', 0," id = $id");
            
            $appModel = new AppModel();        
            if(!$appModel->insertApagados($dados, $motivo, 'diarias', $id)) {
                $this->conexao->rollback();
                return null;
            };

            $this->conexao->commit();
            return true;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }
    
    // public function gerarDiarias()
    // {
    //     $cmd  = $this->conexao->query(
    //         "SELECT 
    //             *
    //         FROM 
    //             configuracao
    //         WHERE 
    //             parametro = 'gerar_diaria'
    //         "
    //     );

    //     if($cmd->rowCount() > 0)
    //     {
    //         $dados = $cmd->fetchAll(PDO::FETCH_ASSOC)[0]['valor'];
    //         // var_dump(strtotime(Date('Y-m-d 17:00:00')), strtotime(Date('Y-m-d H:i:s')));
    //         if (strtotime($dados) < strtotime(Date('Y-m-d H:i:s'))) {
    //             $this->verificaGerarDiarias($dados);
    //         }

    //         return true;
    //     }
        
    // }

    // private function verificaGerarDiarias($param)
    // {
    //     $cmd  = $this->conexao->query(
    //         "SELECT 
    //             id,
    //             valor,
    //             gerarDiaria
    //         FROM 
    //             reserva
    //         WHERE 
    //             status = 3
    //         AND
    //             tipo = 1
    //         AND
    //             gerarDiaria <= '$param'
    //         "
    //     );
       
    //     if($cmd->rowCount() > 0)
    //     {           
    //         $data = $cmd->fetchAll(PDO::FETCH_ASSOC);
    //         return $this->prepareGerarDiarias($data, $param);
    //     }

    //     return $this->updateConfiguracaoGerarDiaria(self::addDayInDate(Date('Y-m-d 16:00:00'), 1));
    // }

    // private function prepareGerarDiarias($dados, $param)
    // {
    //     if(empty($dados))
    //     {
    //         return null;
    //     }

    //     foreach ($dados as $key => $value) {
    //         $dias = round((strtotime(Date('Y-m-d H:i:s')) - strtotime($value['gerarDiaria']))/86400);

    //         if($dias < 1){
    //             $dias = 1;
    //         }

    //         for ($i=1; $i <= $dias; $i++) { 
    //             $this->insertDiariaConsumo($value, self::addDayInDate($param, $i -1 ));
    //             $this->updateGerarDiaria($value['id'], self::addDayInDate($param, $i));

    //             $this->updateConfiguracaoGerarDiaria(self::addDayInDate($param, $i));
    //         }            
    //     }

    //     return "atalizações consumos feitas";
    // }

    private function updateConfiguracaoGerarDiaria($data)
    {
        $cmd  = $this->conexao->prepare(
            "UPDATE 
                configuracao
            SET 
              valor = :data 
            WHERE 
                parametro = :param
            "
        );

        $cmd->bindValue(':data',$data);
        $cmd->bindValue(':param',"gerar_diaria");

        $cmd->execute();

        return "atualizado configurações";
    }
}