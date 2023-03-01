<?php

class NotasModel extends ConexaoModel {
    protected $conexao;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    # paralela

    public function filtrarAprendizagem($id,$bimestre,$ano)
    {
            $dados=array(); 
            $dados=$this->conexao->query(
            "SELECT 
            e.nome,
            e.matricula,
            c.curso,
            l.nome as disciplina,
            (
                select nota from paralela 
                where id_disciplina='$id' 
                and bimestre='$bimestre' 
                and ano_letivo='$ano' 
                and code=e.matricula limit 1
            ) as paralela,
            (
                select id_paralela from paralela 
                where id_disciplina='$id' 
                and bimestre='$bimestre' 
                and ano_letivo='$ano' 
                and code=e.matricula limit 1
            ) as id_paralela,
            (
                select nota from notas_bimestres 
                where id_disciplinas='$id' 
                and ano_letivo='$ano' 
                and bimestre='$bimestre' 
                and code=e.matricula
            ) as media 
            FROM disciplinas d 
            INNER JOIN professores p on p.id_professores=d.id_professores 
            INNER JOIN cursos_estudantes ce on ce.id_cursos=d.id_cursos 
            INNER JOIN estudantes e on e.id_estudantes=ce.id_estudantes 
            INNER JOIN cursos c on ce.id_cursos=c.id_cursos
            INNER JOIN lista_disc l on l.id_lista=d.disciplina 
            WHERE 
                d.id_disciplinas='$id' 
                and ce.ano_letivo='$ano'"
            );

            $dados=$dados->fetchAll(PDO::FETCH_ASSOC);
            return $dados;
    }

    public function insertAprendizagem($id,$bimestre,$ano,$at1,$code)
    {
        
        $dados1='';
        if(!empty($at1)){
            if(($at1)>2){
                return false;
                die;
            }
        }
        
        if(!empty($at1))
        {
            $verifica3=$this->conexao->query(
                "SELECT nota from paralela 
                where bimestre='$bimestre' and id_disciplina='$id' 
                and ano_letivo='$ano' and code='$code'"
            );

            $verifica3=$verifica3->fetchAll(PDO::FETCH_ASSOC);
       
            if(count($verifica3)==0){
                $this->conexao->beginTransaction();
                
                try {      
                    $dados1=$this->conexao->prepare(
                        'INSERT INTO paralela(id_disciplina,nota,bimestre,ano_letivo,code) 
                        VALUES (:id,:nota,:bimestre,:ano,:code)'
                    );
                    $dados1->bindValue(':id',$id);
                    $dados1->bindValue(':nota',$at1);
                    $dados1->bindValue(':bimestre',$bimestre);
                    $dados1->bindValue(':ano',$ano);
                    $dados1->bindValue(':code',$code);
                    $dados1->execute();
                    
                    $this->conexao->commit();
                } catch (\Throwable $th) {
                    $this->conexao->rollback();
                    return $th->getMessage();
                }
            } else {
                return false;
                exit;
            }
        }   

        if($dados1){
            return true;
        }
    }

    public function deleteAprendizagem($id)
    {
        $this->conexao->beginTransaction();
        try {      
            $dados1=$this->conexao->prepare('DELETE FROM paralela WHERE id_paralela=:id');
            $dados1->bindValue(':id',$id);
            $dados1->execute();
            
            $this->conexao->commit();
        
            if($dados1){
                return true;
            }
            //code...
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }


    # notas bimestre

    public function filtrarNotasBimestre($id,$bimestre,$ano)
    {
        $dados=array();
            $dados=$this->conexao->query(
                "SELECT 
                    e.nome,
                    e.matricula,
                    c.id_categoria,
                    c.curso,
                    l.nome as disciplina,
                    (select 
                        nota 
                    from 
                        notas_atividades 
                    where 
                        id_disciplina = '$id' 
                        and bimestre = '$bimestre' 
                        and ano_letivo = '$ano' 
                        and code = e.matricula 
                        limit 1
                    ) as nota1,
                    (select 
                        id_notas_atividades 
                    from 
                        notas_atividades 
                    where 
                        id_disciplina = '$id' 
                        and bimestre = '$bimestre' 
                        and ano_letivo = '$ano' 
                        and code = e.matricula 
                        limit 1
                    ) as idnota1,
                    (select 
                        nota 
                    from 
                        notas_ava_coc 
                    where 
                        id_disciplina = '$id' 
                        and ano_letivo = '$ano' 
                        and bimestre = '$bimestre' 
                        and code = e.matricula 
                    limit 1
                    ) as nota2,
                    (select 
                        id_notas_ava_coc 
                    from 
                        notas_ava_coc 
                    where 
                        id_disciplina = '$id' 
                        and bimestre = '$bimestre' 
                        and ano_letivo = '$ano' 
                        and code = e.matricula 
                        limit 1
                    ) as idnota2,
                    (select 
                        nota 
                    from 
                        notas_ava_teste 
                    where 
                        id_disciplina = '$id' 
                        and ano_letivo = '$ano' 
                        and bimestre = '$bimestre' 
                        and code = e.matricula 
                    limit 1
                    ) as nota3,
                    (select 
                        id_notas_ava_teste 
                    from 
                        notas_ava_teste 
                    where 
                        id_disciplina = '$id' 
                        and ano_letivo = '$ano' 
                        and bimestre = '$bimestre' 
                        and code = e.matricula 
                    limit 1
                    ) as idnota3,
                    (select 
                        nota 
                    from 
                        notas_ava_prova 
                    where 
                        id_disciplina = '$id' 
                        and ano_letivo = '$ano' 
                        and bimestre = '$bimestre' 
                        and code = e.matricula 
                    limit 1
                    ) as nota4,
                    (select 
                        id_notas_ava_prova 
                    from 
                        notas_ava_prova 
                    where 
                        id_disciplina = '$id' 
                        and ano_letivo = '$ano' 
                        and bimestre = '$bimestre' 
                        and code = e.matricula 
                    limit 1
                    ) as idnota4,
                    (select 
                        nota 
                    from 
                        notas_bimestres 
                    where 
                        id_disciplinas = '$id' 
                        and ano_letivo = '$ano' 
                        and bimestre = '$bimestre' 
                        and code = e.matricula
                    ) as media 
                FROM 
                    disciplinas d 
                INNER JOIN 
                    professores p 
                on 
                    p.id_professores = d.id_professores 
                INNER JOIN 
                    cursos_estudantes ce 
                on 
                    ce.id_cursos = d.id_cursos 
                INNER JOIN 
                    lista_disc l 
                on 
                    l.id_lista = d.disciplina
                INNER JOIN 
                    estudantes e 
                on 
                    e.id_estudantes = ce.id_estudantes 
                INNER JOIN 
                    cursos c 
                on 
                    ce.id_cursos = c.id_cursos 
                where 
                    d.id_disciplinas = '$id' 
                and 
                    ce.ano_letivo = '$ano'"
            );

            $dados=$dados->fetchAll(PDO::FETCH_ASSOC);
        
            return $dados;
    }

    public function inserirNotaBimestre( array $data)
    {     
        $dados = '';

        foreach ($data as $key => $value) {
            
            foreach ($value['notas'] as $index => $nota) {                  
                if(!empty($nota['at1']) && $nota['at1'] < 10) {                   
                    $verifica1 = $this->conexao->query(
                        "SELECT 
                            nota 
                        FROM 
                            notas_atividades 
                        WHERE 
                            bimestre = {$value['bimestre']} 
                            and id_disciplina = {$value['id']} 
                            and ano_letivo = {$value['ano']} 
                            and code = {$value['code']}"
                    );                     

                    $verifica1 = $verifica1->fetchAll(PDO::FETCH_ASSOC);

                    if( count($verifica1) == 0 ) {
                        $dados1 = $this->insertNotasBimestre(
                            'notas_atividades',
                            $value['id'], 
                            (float)$nota['at1'],
                            $value['bimestre'],
                            $value['ano'],
                            $value['code']
                        );
                    } 
                }

                else if(!empty($nota['at2']) && $nota['at2'] < 10) {  
                    $verifica2 = $this->conexao->query(
                        "SELECT 
                            nota 
                        FROM 
                            notas_ava_coc 
                        WHERE 
                            bimestre = {$value['bimestre']} 
                            and id_disciplina = {$value['id']} 
                            and ano_letivo = {$value['ano']}
                            and code = {$value['code']}"
                    );

                    $verifica2=$verifica2->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($verifica2) == 0) {
                        $dados1 = $this->insertNotasBimestre(
                            'notas_ava_coc',
                            $value['id'], 
                            (float)$nota['at2'],
                            $value['bimestre'],
                            $value['ano'],
                            $value['code']
                        );
                    } 
                }
                if (!empty($nota['at3']) && $nota['at3'] < 10)
                {
                    $verifica3 = $this->conexao->query(
                        "SELECT 
                            nota 
                        FROM 
                            notas_ava_teste 
                        WHERE 
                            bimestre = {$value['bimestre']} 
                            and id_disciplina = {$value['id']} 
                            and ano_letivo = {$value['ano']}
                            and code = {$value['code']}"
                    );

                    $verifica3=$verifica3->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($verifica3) == 0) {
                        $dados1 = $this->insertNotasBimestre(
                            'notas_ava_teste',
                            $value['id'], 
                            (float)$nota['at3'],
                            $value['bimestre'],
                            $value['ano'],
                            $value['code']
                        );
                    } else {
                        return false;
                        exit;
                    }
                }
                if(!empty($nota['at4']) && $nota['at4'] < 10)
                {
                    $verifica4 = $this->conexao->query(
                        "SELECT 
                            nota 
                        FROM 
                            notas_ava_prova 
                        WHERE 
                            bimestre = {$value['bimestre']} 
                            and id_disciplina = {$value['id']} 
                            and ano_letivo = {$value['ano']}
                            and code = {$value['code']}"
                    );

                    $verifica4 = $verifica4->fetchAll(PDO::FETCH_ASSOC);

                    if(count($verifica4) == 0) {
                    $dados1 = $this->insertNotasBimestre(
                        'notas_ava_prova',
                        $value['id'], 
                        (float)$nota['at4'],
                        $value['bimestre'],
                        $value['ano'],
                        $value['code']
                    );
                    } else {
                        return false;
                        exit;
                    }
                }
            }
        }
        return $dados1;
    }

    private function insertNotasBimestre($table, $id, $nota, $bimestre, $ano, $code) 
    {  
        $this->conexao->beginTransaction();
        try {            
            $dados1 = $this->conexao->prepare(
                "INSERT IGNORE INTO 
                    {$table} 
                        (
                            id_disciplina,
                            nota,
                            bimestre,
                            ano_letivo,
                            code,
                            prova
                        ) 
                VALUES 
                    (
                        :id,
                        :nota,
                        :bimestre,
                        :ano,
                        :code,
                        :prova
                    )"
                );
            $dados1->bindValue(':id',$id);
            $dados1->bindValue(':nota',$nota);
            $dados1->bindValue(':bimestre',$bimestre);
            $dados1->bindValue(':ano',$ano);
            $dados1->bindValue(':code',$code);
            $dados1->bindValue(':prova','professor -'.$_SESSION['code']);
            $dados1->execute();
            $this->conexao->commit();
            
            return true;
        
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        } 
    }


    public function deletarNotaBimestre($id,$at1)
    {        
        
        switch ($at1) {
            case 1:
                $dados1 = $this->conexao->prepare('DELETE FROM notas_atividades WHERE id_notas_atividades=:id');                
                break;
            case 2:
                $dados1 = $this->conexao->prepare('DELETE FROM notas_ava_coc WHERE id_notas_ava_coc=:id');
                break;
            case 3:
                $dados1 = $this->conexao->prepare('DELETE FROM notas_ava_teste WHERE id_notas_ava_teste=:id');
                break;
            case 4:
                $dados1 = $this->conexao->prepare('DELETE FROM notas_ava_prova WHERE id_notas_ava_prova=:id');
                break;

            default:    
                return null;
                break;
        }
        
        $this->conexao->beginTransaction();
        try {      
            $dados1->bindValue(':id',$id);
            $dados1->execute();        
           
            $this->conexao->commit();
            
            return true;
        
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }


    public function filtrarRecuperacao($id,$ano)
    {
        $dados = array();
        // $code=$_SESSION['code'];          
        $permissoes = $this->conexao->query(
            'SELECT 
                recuperacao 
            FROM 
                permissao 
            limit 1'
        );

        $permissao= $permissoes->fetchAll(PDO::FETCH_ASSOC);

        if( $permissao[0]['recuperacao'] == 1 ) {
            $dados = $this->conexao->query(
                "SELECT 
                    e.nome,
                    e.matricula,
                    c.curso,
                    l.nome as disciplina,
                    (
                        select 
                            media 
                        from 
                            resultado_final 
                        where 
                            id_disciplinas = '$id' 
                        and 
                            ano_letivo='$ano' 
                        and 
                            code=e.matricula
                    ) as media,
                    (
                        select 
                            recuperacao 
                        from 
                            resultado_final 
                        where 
                            id_disciplinas = '$id' 
                        and 
                            ano_letivo='$ano' 
                        and 
                            code=e.matricula 
                   ) as recuperacao,
                   (
                        select 
                            situacao 
                        from 
                            resultado_final 
                        where 
                            id_disciplinas='$id' 
                        and 
                            ano_letivo='$ano' 
                        and code=e.matricula
                    ) as situacao, 
                    (
                        select 
                            id_resultado 
                        from 
                            resultado_final 
                        where 
                            id_disciplinas='$id' 
                        and 
                            ano_letivo='$ano' 
                        and 
                            code=e.matricula
                    ) as idRes 
                FROM 
                    disciplinas d 
                INNER JOIN 
                    professores p 
                ON 
                    p.id_professores = d.id_professores 
                INNER JOIN 
                    cursos_estudantes ce 
                ON 
                    ce.id_cursos = d.id_cursos 
                INNER JOIN 
                    estudantes e 
                ON 
                    e.id_estudantes = ce.id_estudantes 
                INNER JOIN 
                    cursos c 
                ON 
                    ce.id_cursos = c.id_cursos
                INNER JOIN 
                    lista_disc l 
                ON 
                    l.id_lista = d.disciplina 
                WHERE 
                    d.id_disciplinas = '$id' 
                AND 
                    ce.ano_letivo = '$ano'"
            );

        $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        } else {            
            return false;
            // exit;
        }

        return $dados;
    }

    public function updateRecuperacao($id,$nota)
    {        
        $dados1='';
        $situacao='reprovado';
       
        if(($nota) > 10) {
            return false;            
        } elseif($nota >= 6) {
            $situacao = "aprovado na Final";
        } elseif($nota == '') {
            $nota = null;
        }
        $this->conexao->beginTransaction();
        try {
            
            $dados1 = $this->conexao->prepare(
                "UPDATE 
                    resultado_final 
                set 
                    recuperacao = :nota,
                    situacao = :situacao 
                where 
                    id_resultado = :id"
                );
            $dados1->bindValue(':id',$id);
            $dados1->bindValue(':nota',$nota);
            $dados1->bindValue(':situacao',$situacao);
            $dados1->execute();
            
            $this->conexao->commit();

            if($dados1) {
                return true;
            }
        //code...
        } catch (\Throwable $th) {
            //throw $th;
            $this->conexao->rollback();
            return false;
        }
    }

    public function recalcularNotaFinal($id, $ano)
    {
        $verifica4 = $this->conexao->query(
            "SELECT 
                nb.ano_letivo, 
                nb.id_disciplinas, 
                nb.code, 
                sum(nb.nota) as nota 
            FROM 
                `resultado_final` r 
            INNER JOIN 
                notas_bimestres nb 
            on 
                r.code=nb.code 
            WHERE 
                r.id_resultado = '{$id}' 
            and 
                nb.ano_letivo = '{$ano}' 
            group by 
                nb.id_disciplinas, 
                nb.ano_letivo, 
                nb.id_disciplinas, 
                nb.code"
        );

        $verifica4 = $verifica4->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($verifica4) > 0) { 
            $i = 0;     
            foreach ($verifica4 as $key => $value) {
                # code...            
                $this->conexao->beginTransaction();
                try {
                    
                    $dados1 = $this->conexao->prepare(
                        "UPDATE 
                            resultado_final 
                        set 
                            media = :nota,
                            situacao = :situacao 
                        where 
                            id_disciplinas = :id
                        and    
                            ano_letivo = :ano
                        "
                        );
                    $dados1->bindValue(':id',$value->id);
                    $dados1->bindValue(':nota',$value->nota);
                    $dados1->bindValue(':situacao',$value->nota >= 2.7 ? 'Aprovado' : '');
                    $dados1->bindValue(':ano',$ano);
                    $dados1->execute();
                    
                    $this->conexao->commit();

                    $i++;
                //code...
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->conexao->rollback();
                    return false;
                }
            }
            return $i;
        } else {
            return false;
            exit;
        }
    }

}


// SELECT RF.media as Total ,RF.ano_letivo as "Ano Letivo", RF.code, NA.bimestre, NA.nota as atividades, NC.nota as coc, NT.nota as teste, NP.nota as Prova, P.nota as paralela, NB.nota as Media FROM resultado_final RF ,notas_atividades NA,notas_ava_coc NC, notas_ava_teste NT ,notas_ava_prova NP ,paralela P, notas_bimestres NB WHERE RF.ano_letivo = '2022' and RF.id_disciplinas = 225;