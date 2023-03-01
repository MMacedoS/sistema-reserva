<?php

class DisciplinaModel extends ConexaoModel
{
    protected $conexao;

    public function __construct() {
        $this->conexao = ConexaoModel::conexao();
    }

    public function buscaDisciplinasPorIdAndCodeCoordernador($id, $code)
    {
        $dados = array();          
        $dados = $this->conexao->query(
            "SELECT 
                c.curso,
                cat.categoria,
                c.id_cursos,
                c.turno,
                d.id_disciplinas,
                l.nome as disciplinas 
            FROM
                disciplinas d 
            INNER JOIN 
                lista_disc l 
            ON 
                l.id_lista = d.disciplina 
            INNER JOIN 
                cursos c 
            ON 
                d.id_cursos = c.id_cursos 
            INNER JOIN 
                coordenador p 
            ON 
                p.categoria = c.id_categoria 
            INNER JOIN 
                categoria cat 
            ON 
                cat.id_categoria = c.id_categoria 
            WHERE 
                p.code = '$code' 
            AND 
                c.id_cursos = '$id' 
            AND 
                d.status = 0 
            ORDER BY c.ordem 
            ASC");

        if($dados->rowCount() > 0){
            $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        }
        return $dados;
    }

    public function buscaDisciplinasPorIdAndCodeProfessor($id, $code)
    {
        $dados = array();          
        $dados = $this->conexao->query(
            "SELECT 
                c.curso,
                cat.categoria,
                c.id_cursos,
                c.turno,
                d.id_disciplinas,
                l.nome as disciplinas 
            FROM 
                disciplinas d 
            INNER JOIN 
                lista_disc l 
            ON 
                l.id_lista = d.disciplina 
            INNER JOIN 
                cursos c 
            ON 
                d.id_cursos = c.id_cursos 
            INNER JOIN 
                categoria cat 
            ON 
                cat.id_categoria = c.id_categoria 
            INNER JOIN 
                professores p 
            ON 
                p.id_professores = d.id_professores 
            WHERE 
                p.code = '$code' 
            AND 
                c.id_cursos = '$id' 
            AND 
                d.status = 0 
            ORDER BY 
                c.ordem 
            ASC");

        if($dados->rowCount() > 0){
            $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        }
        return $dados;
    }

    public function buscaDisciplinas() {
        $dados = array();          
        $cmd = $this->conexao->query(
            "SELECT 
                td.*,
                d.nome as disciplina, 
                t.nome as turma,
                p.nome as professor 
            FROM 
                turma_disciplina td 
            INNER JOIN 
                disciplinas d 
            ON 
                d.id = td.disciplinas_id
            INNER JOIN 
                turmas t 
            ON 
                t.id = td.turmas_id 
            INNER JOIN 
                disciplina_professor dp
            ON
                dp.turma_disciplinas_id = td.id
            INNER JOIN 
                professores p 
            ON 
                dp.professores_id = p.id 
            ORDER BY 
                t.ordem 
            DESC 
            LIMIT 36"
        );
        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);        
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaDisciplinasPorParams($params) {
        $dados = array();          
        $cmd = $this->conexao->query(
            "SELECT 
                td.*,
                d.nome as disciplina, 
                t.nome as turma,
                p.nome as professor 
            FROM 
                turma_disciplina td 
            INNER JOIN 
                disciplinas d 
            ON 
                d.id = td.disciplinas_id
            INNER JOIN 
                turmas t 
            ON 
                t.id = td.turmas_id 
            INNER JOIN 
                disciplina_professor dp
            ON
                dp.turma_disciplinas_id = td.id
            INNER JOIN 
                professores p 
            ON 
                dp.professores_id = p.id 
            WHERE 
                d.nome like '%$params%'
            OR 
                t.nome like '%$params%'
            OR 
                p.nome like '%$params%'
            ORDER BY 
                t.ordem 
            DESC 
            LIMIT 36"
        );
        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);        
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaDisciplinaPorId($id)
    {
        $dados = array();          
        $cmd = $this->conexao->query(
            "SELECT 
                td.*,
                d.nome as disciplina, 
                t.nome as turma,
                p.nome as professor,
                p.id as professor_id
            FROM 
                turma_disciplina td 
            INNER JOIN 
                disciplinas d 
            ON 
                d.id = td.disciplinas_id
            INNER JOIN 
                turmas t 
            ON 
                t.id = td.turmas_id 
            INNER JOIN 
                disciplina_professor dp
            ON
                dp.turma_disciplinas_id = td.id
            INNER JOIN 
                professores p 
            ON 
                dp.professores_id = p.id 
            WHERE 
                td.id = '$id' ");

        if($cmd->rowCount() > 0){
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        }
        return $dados;
    }

    public function buscaDisciplinasPorNomeOuProfessorOuTurma($params) {
        $dados = array();          
        $cmd = $this->conexao->query(
            "SELECT 
                d.id_disciplinas,
                l.nome as disciplinas, 
                d.status, 
                c.curso,
                p.nome as professor 
            FROM 
                disciplinas d 
            INNER JOIN 
                lista_disc l 
            ON 
                d.disciplina = l.id_lista
            INNER JOIN 
                cursos c 
            ON 
                c.id_cursos = d.id_cursos 
            INNER JOIN 
                professores p 
            ON 
                p.id_professores = d.id_professores 
            WHERE 
                d.id_disciplinas = '$params'
            OR 
                l.nome like '%$params%' 
            OR 
                c.curso like '%$params%' 
            OR 
                p.nome like '%$params%'
            ORDER BY 
                c.ordem 
            DESC 
            LIMIT 36"
        );
        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);        
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaDisciplina() {
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                disciplinas 
            ORDER BY 
                nome 
            asc");

        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        
        }else{
            $dados = array();
        }
        return $dados;
    }

    public function buscaCargaParaSelect() {
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                carga_horaria
            ORDER BY 
                carga 
            ASC");

        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        
        }else{
            $dados = array();
        }
        return $dados;
    }

    public function prepareInsertDisciplinas($dados)
    {
        if(empty($dados)){
            return "sem registro";
        }

        $disciplina = $dados['disciplinas'];
        $turma = $dados['turma'];
        $professor = $dados['professor'];
        $carga = $dados['carga'];
        $status = $dados['status'];
        $semestre = $dados['semestre'];
        $ano_letivo = Date('Y');

        if(empty($disciplina)){
            return "verifique sua disciplina!";
        }else if (empty($professor)) {
            return "verifique o professor!";
        }else if (empty($turma)) {
            return "verifique a turma!";
        }

        $dados = "dados não Cadastrados, verifique se a disciplina está cadastrada";
        
        $registro = $this->verificaDisciplinas($turma,$disciplina);

        if(empty($registro))
        {    
            return $this->inserirDisciplina($disciplina,$turma,$professor,$carga,$status,$semestre,$ano_letivo); 
        }

        return $dados;
    }

    public function prepareInsertCarga($dados)
    {
        if(empty($dados)){
            return "sem registro";
        }

        $carga = $dados['carga'];

        if(empty($carga)){
            return "verifique a carga!";
        }

        $dados = "dados não salvos";
        
        $registro = $this->verificaCarga($carga);

        if(empty($registro))
            $dados = $this->inserirCarga($carga); 

        return $dados;
    }

    public function prepareInsertListaDisciplinas($dados)
    {
        $disciplina = $dados['disciplina'];

        if(empty($disciplina)){
            return "verifique sua disciplina!";
        }

        $dados = array();

        $registro = $this->verificaDisciplinasInLista($disciplina);

        if(empty($registro))
            return $dados = $this->inserirDisciplinaInLista($disciplina); 

        return "Disciplinas ja existe!";
    }


    private function verificaDisciplinas($turma,$disciplina)
    {
        $cmd = $this->conexao->query(
            "SELECT * 
            FROM 
                turma_disciplina 
            WHERE 
                disciplinas_id = '$disciplina' 
            AND 
                turmas_id = '$turma'             
            ");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados = array();
        }
        return $dados;
    }

    private function verificaCarga($carga)
    {
        $cmd = $this->conexao->query(
            "SELECT * 
            FROM 
                carga_horaria
            WHERE 
                carga = '$carga' 
            ");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados = array();
        }
        return $dados;
    }

    private function verificaDisciplinasInLista($disciplina)
    {
        $cmd = $this->conexao->query(
            "SELECT * 
            FROM 
                disciplinas 
            WHERE 
                nome = '$disciplina' 
            ");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados = array();
        }
        return $dados;
    }

    private function inserirCarga($carga)
    {
        $this->conexao->beginTransaction();
        try {
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    carga_horaria 
                SET 
                    carga = :carga"
                );
            $cmd->bindValue(':carga',$carga);
            $cmd=$cmd->execute();
            
            $dados = "Cadastro efetuado com sucesso!";
                         
            $this->conexao->commit();
            return $dados;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    private function inserirDisciplina($disciplina,$turma,$professor,$carga,$status,$semestre,$ano_letivo)
    {
        $this->conexao->beginTransaction();
        try {
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    turma_disciplina 
                SET 
                    disciplinas_id = :disciplina,
                    carga_horaria_id = :carga,
                    turmas_id = :turma,
                    ano_letivo = :ano_letivo,
                    semestre = :semestre
                    "
                );
            $cmd->bindValue(':disciplina',$disciplina);
            $cmd->bindValue(':turma',$turma);
            $cmd->bindValue(':semestre',$semestre ?? 0);
            $cmd->bindValue(':carga',$carga);            
            $cmd->bindValue(':ano_letivo',$ano_letivo);
            $cmd = $cmd->execute();
            $id_gerado  = $this->conexao->lastInsertId();
            if($cmd) {
                $cmd = $this->conexao->prepare(
                    "INSERT INTO 
                        disciplina_professor
                    SET 
                        turma_disciplinas_id = :disciplina,
                        ano_letivo = :ano_letivo,
                        professores_id = :professor
                        "
                    );
                $cmd->bindValue(':disciplina',$id_gerado);               
                $cmd->bindValue(':professor',$professor);        
                $cmd->bindValue(':ano_letivo',$ano_letivo);
                $cmd = $cmd->execute();
            }
            
            $dados = "Cadastro efetuado com sucesso!";
                         
            $this->conexao->commit();
            return $dados;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    private function inserirDisciplinaInLista($disciplina)
    {
        $this->conexao->beginTransaction();
        try {
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    disciplinas 
                SET 
                    nome = :disciplina"
                );
            $cmd->bindValue(':disciplina',$disciplina);
            $cmd=$cmd->execute();
            
            $dados = "Cadastro efetuado com sucesso!";
                         
            $this->conexao->commit();
            return $dados;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    public function preparaUpdateDisciplinas($dados, $id)
    {
        if(empty($dados)){
            return "sem registro";
        }

        $disciplina = $dados['disciplinas'];
        $turma = $dados['turma'];
        $professor = $dados['professor'];
        $carga = $dados['carga'];
        $status = $dados['status'];
        $semestre = $dados['semestre'];

        if(empty($disciplina)){
            return "verifique sua disciplina!";
        }
        if (empty($professor)) {
            return "verifique o professor!";
        }
        if (empty($turma)) {
            return "verifique a turma!";
        }

        try{
            $dados = $this->updateDisciplina($disciplina,$turma,$professor,$carga,$status,$semestre,$id); 
            
            if($dados) {
                return "Dados atualizados com sucesso!";
            }
        } catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    private function updateDisciplina($disciplina,$turma,$professor,$carga,$status,$semestre,$id)
    {

        $ano_letivo = Date('Y');
        $this->conexao->beginTransaction();
        try {
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    turma_disciplina 
                SET 
                    disciplinas_id = :disciplina,
                    carga_horaria_id = :carga,
                    turmas_id = :turma,
                    ano_letivo = :ano_letivo,
                    semestre = :semestre
                WHERE 
                    id = :id
                    "
                );
            $cmd->bindValue(':disciplina',$disciplina);
            $cmd->bindValue(':turma',$turma);
            $cmd->bindValue(':semestre',$semestre);
            $cmd->bindValue(':carga',$carga);            
            $cmd->bindValue(':ano_letivo',$ano_letivo);
            $cmd->bindValue(':id',$id);
            $cmd = $cmd->execute();            
                
            if($cmd) {
                $cmd = $this->conexao->prepare(
                    "UPDATE 
                        disciplina_professor
                    SET 
                        ano_letivo = :ano_letivo,
                        professores_id = :professor
                    WHERE 
                        turma_disciplinas_id = :disciplina
                        "
                    );
                $cmd->bindValue(':disciplina',$id);               
                $cmd->bindValue(':professor',$professor);        
                $cmd->bindValue(':ano_letivo',$ano_letivo);
                $cmd = $cmd->execute();
            }
            
            $this->conexao->commit();
            
            $dados = "Cadastro atualizados com sucesso!";
                         
            return $dados;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    public function diaSemanaTurma(int $turma)
    {
        try {            
            $cmd = $this->conexao->query(
                "SELECT 
                    nome, 
                    id, 
                    turma_disciplina_id,
                    hora
                FROM 
                    dias_semana 
                WHERE 
                    turma_disciplina_id = {$turma} 
                ORDER BY
                    id
                ASC
                "
                ); 
            
                $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
                return $dados;
            
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function prepareDiasSemanaAulasDisciplina($dados)
    {
        if(empty($dados)){
            return "sem registro";
        }

        if($this->verificaDiaSemanaTurma($dados['listaSemana'], $dados['hora'], (int)$dados['id'])){
            return "ja possui um horario definido";
        }

        return $this->insertDiasAulasDisciplina($dados['listaSemana'], $dados['hora'], (int)$dados['id']);             
    }

    private function insertDiasAulasDisciplina(string $dia, string $hora , int $turma)
    {
        if(empty($dia)){
            return "verifique!";
        }
         if (empty($hora)) {
            return "verifique sua hora!";
        }
         if (empty($turma)) {
            return "verifique a turma!";
        }

        $this->conexao->beginTransaction();
        try {            
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    dias_semana
                SET     
                    turma_disciplina_id = :id,
                    nome = :dia,
                    hora = :hora"
                ); 
            $cmd->bindValue(':id', $turma);
            $cmd->bindValue(':dia', $dia);
            $cmd->bindValue(':hora', $hora);
            $cmd = $cmd->execute();

            $this->conexao->commit();

            return "dados inseridos";
        } catch(\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    public function removeDiaSemanaTurma($id) 
    {
        $this->conexao->beginTransaction();
        try {            
            $cmd = $this->conexao->prepare(
                "DELETE FROM  
                    dias_semana
                WHERE     
                    id = :id"
                ); 
            $cmd->bindValue(':id', $id);
            $cmd = $cmd->execute();

            $this->conexao->commit();

            return "dados deletados";
        } catch(\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    private function verificaDiaSemanaTurma(string $dia, string $hora , int $turma)
    {
        try {            
            $cmd = $this->conexao->prepare(
                "SELECT
                    *
                FROM 
                    dias_semana
                WHERE    
                    turma_disciplina_id = :id
                AND
                    nome = :dia
                AND
                    hora = :hora"
                ); 
            $cmd->bindValue(':id', $turma);
            $cmd->bindValue(':dia', $dia);
            $cmd->bindValue(':hora', $hora);
            $cmd->execute();
            if(empty($cmd->fetchAll(PDO::FETCH_ASSOC)))
            {
                return false;
            }
            return true;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function changeStatusDisciplina($codigo)
    {
        $dados = [];
        $this->conexao->beginTransaction();
        try {            
            $cmd = $this->conexao->query("SELECT status FROM turma_disciplina where id ='$codigo'"); 
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            $status = $dados[0]['status'];
        
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    turma_disciplina 
                SET 
                    status = :status 
                WHERE id = :id"
            );

            if( $status != "1") {
                $cmd->bindValue(':status','1');
                $cmd->bindValue(':id',$codigo);
                $cmd->execute();
                $this->conexao->commit();
                return 'status atualizado!';
            }
                $cmd->bindValue(':status','0');
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