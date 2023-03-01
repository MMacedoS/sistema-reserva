<?php

class TurmaModel extends ConexaoModel
{
    protected $conexao;

    public function __construct() {
        $this->conexao = ConexaoModel::conexao();
    }

    public function buscaTurmasPorCoordenador($code)
    {
        $ano_letivo = date('Y') - 1;
        $cmd = $this->conexao->query(
            "SELECT DISTINCT 
                c.curso,
                c.id_cursos,
                c.ordem 
            FROM 
                cursos c 
            RIGHT JOIN 
                cursos_estudantes ce 
            ON 
                ce.id_cursos = c.id_cursos
            INNER JOIN 
                categoria cat 
            ON 
                cat.id_categoria = c.id_categoria 
            INNER JOIN 
                coordenador coor 
            ON 
                coor.categoria = c.id_categoria 
            WHERE 
                coor.code = '$code' 
            AND 
                c.status = 0 
            AND 
                ce.ano_letivo = $ano_letivo 
            ORDER BY 
                c.ordem 
            ASC"
        );
        
        if($cmd->rowCount()>0) {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $dados = array();
        }
        return $dados;
    }

    public function buscaTurmasPorProfessor($code) 
    {
        $dados = [];
        $dados = $this->conexao->query(
            "SELECT DISTINCT
                c.curso,
                c.id_cursos,
                c.ordem
            FROM 
                cursos c
            INNER JOIN 
                disciplinas d
            ON 
                d.id_cursos = c.id_cursos
            INNER JOIN
                professores p
            ON 
                p.id_professores = d.id_professores
            WHERE 
                p.code = {$code} 
            ORDER BY 
                c.ordem 
            ASC"
        );
        
        if($dados->rowCount() > 0) {
            $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        }

        return $dados;
    }

    public function buscaDisciplinaPorIdTurma($id)
    {
        $dados = [];
        $dados = $this->conexao->query(
            "SELECT 
                l.nome,
                d.id_disciplinas AS disciplina
            FROM
                lista_disc l 
            INNER JOIN 
                disciplinas d
            ON
                d.disciplina = l.id_lista
            WHERE
                d.id_cursos = {$id}
            ORDER BY 
                l.nome 
            ASC"
        );
        if($dados->rowCount() > 0) {
            $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        }
        return $dados;
    }

    public function buscaTurma()
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                turmas 
            ORDER BY 
                ordem 
            ASC"
        );    

        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaTurmaAtivas()
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                turmas
            WHERE 
                status = 1
            ORDER BY 
                ordem 
            ASC"
        );    

        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaTurmaPorNome($curso)
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                turmas 
            WHERE 
                nome 
                like
                '%$curso%' 
            ORDER BY 
                ordem 
            ASC"
        );    

        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaTurmasPorId($curso)
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                turmas 
            WHERE 
                id = $curso
            ORDER BY 
                ordem 
            ASC"
        );    

        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaTurmaPorTurno($turno) {
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                turmas 
            WHERE 
                turno = '$turno'  
            AND
                status = 0
            ORDER BY 
                ordem 
            ASC");        
            if($cmd->rowCount()>0)
            {
                $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
               
            }else{
                $dados = array();
            }
            return $dados;
    }

    public function preparaInsertTurmas($dados)
    {
        if(empty($dados)){
            return "sem registro";
        }
        $nome = $dados['nome'];
        $turno = $dados['turno'];
        $ordem = $dados['ordem'];
        try{
            $existeTurma = $this->verificaTurmas($nome,$turno,$ordem);

            if(count($existeTurma) == 0){
                $dados = $this->inserirTurma($nome,$turno,$ordem); 
                if($dados) 
                    return "Dados cadastrados com sucesso!";
            }else{
                return "Turma existente";
            }
        }catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    public function preparaUpdateTurmas($dados, $id)
    {
        if(empty($dados)){
            return "sem registro";
        }
        $nome = $dados['nome'];
        $turno = $dados['turno'];
        $ordem = $dados['ordem'];
        try{
                $dados = $this->updateTurma($nome,$turno,$ordem,$id); 
                if($dados) {
                    return "Dados atualizados com sucesso!";
                }
        } catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    private function verificaTurmas($nome,$turno,$ordem)
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                turmas 
            WHERE 
                nome = '$nome' 
            AND 
                turno = '$turno' 
            AND 
                ordem = '$ordem' 
            ORDER BY 
                ordem 
            ASC"
        );        

        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);           
        }else{
            $dados=array();
        }
        return $dados;
    }

    private function inserirTurma($nome,$turno,$ordem)
    {
        $dados = [];
        $this->conexao->beginTransaction();
        try {
            
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    turmas 
                SET 
                    nome = :cursos, 
                    turno = :turno,
                    ordem = :ordem"
                );
            $cmd->bindValue(':cursos',$nome);
            $cmd->bindValue(':turno',$turno);
            $cmd->bindValue(':ordem',$ordem);
            $cmd->execute();

            $this->conexao->commit();

            if($cmd){
                $dados=true;
            }

            return $dados;
        //code...
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return false;
        }
    }

    private function updateTurma($nome,$turno,$ordem, $id)
    {
        $dados = [];
        $this->conexao->beginTransaction();
        try {
            
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    turmas 
                SET 
                    nome = :cursos, 
                    turno = :turno,
                    ordem = :ordem
                WHERE
                    id = :id"
                );
            $cmd->bindValue(':cursos',$nome);
            $cmd->bindValue(':turno',$turno);
            $cmd->bindValue(':ordem',$ordem);
            $cmd->bindValue(':id',$id);
            $cmd->execute();

            $this->conexao->commit();

            if($cmd){
                $dados=true;
            }
            
            return $dados;
        //code...
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return false;
        }
    }

    public function changeStatusTurma($codigo)
    {
        $dados = [];
        $this->conexao->beginTransaction();
        try {            
            $cmd = $this->conexao->query("SELECT status FROM turmas where id='$codigo'"); 
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            $status = $dados[0]['status'];
        
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    turmas 
                SET 
                    status = :status 
                WHERE id = :id"
            );

            if( $status != "1") {
                $cmd->bindValue(':status','1');
            } else {
                $cmd->bindValue(':status','0');
            }
                $cmd->bindValue(':id',$codigo);
                $cmd->execute();

                $this->conexao->commit();

            return 'status atualizado!';
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return false;
        }
    }
}