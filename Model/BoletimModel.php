<?php

class BoletimModel extends ConexaoModel
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

    public function notasBimestrePorAlunoAnoDisciplinaBimestre($disciplina, $aluno, $bimestre, $ano)
    {
        $dados = [];
        try {
            
            $dados = $this->conexao->query(
                "SELECT 
                    nota
                FROM 
                    notas_bimestres
                WHERE 
                    id_disciplinas = {$disciplina} 
                AND
                    code = {$aluno} 
                AND 
                    bimestre = {$bimestre} 
                AND 
                    ano_letivo = {$ano}"
            );

            if($dados->rowCount() > 0) {
                $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
                var_dump($dados);
                return $dados;
            }
            return [];          
        //code...
        } catch (\Throwable $th) {
            return null;
        }
      
    }

    // public function notasBimestrePorAlunoAnoDisciplinaBimestre($disciplina, $aluno, $bimestre, $ano)
    // {
    //     $dados = [];
    //     $dados = $this->conexao->query(
    //         "SELECT 
    //             nota
    //         FROM 
    //             notas_bimestres
    //         WHERE 
    //             id_disciplinas = {$disciplina} 
    //         AND
    //             code = {$aluno} 
    //         AND 
    //             bimestre = {$bimestre} 
    //         AND 
    //             ano_letivo = {$ano}"
    //     );

    //     if($dados->rowCount() > 0){
    //         $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
    //     }
    //     return $dados;
    // }
}