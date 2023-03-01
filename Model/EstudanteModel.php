<?php


class EstudanteModel extends ConexaoModel
{
    protected $conexao;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function buscaEstudantesPorCoordenador($code)
    {
        $ano_letivo = date('Y');
        $cmd=$this->conexao->query(
            "SELECT 
                e.* 
            FROM 
                estudantes e 
            INNER JOIN 
                cursos_estudantes ce 
            ON 
                e.id_estudantes = ce.id_estudantes 
            INNER JOIN 
                cursos c 
            ON 
                c.id_cursos=ce.id_cursos
            INNER JOIN 
                categoria cat 
            ON 
                cat.id_categoria = c.id_categoria 
            INNER JOIN 
                coordenador coor 
            ON 
                coor.categoria=c.id_categoria 
            WHERE 
                coor.code = $code  
            AND 
                c.status=0 
            AND 
                e.status='Ativo' 
            AND 
                ce.ano_letivo = $ano_letivo 
            ORDER BY 
                e.nome 
            asc"
        );

        if($cmd->rowCount()>0) {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $dados=array();
        }
        return $dados;
    }

    public function listaAvancaEstudantesPorCurso($curso, $ano)
    {
        $dados=array();                                                   
        $curso2 = '';
        $dados = $this->conexao->query(
            "SELECT 
                ordem 
            FROM 
                cursos 
            WHERE 
                id_cursos = '$curso'
            ");
        $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        $curso2 = $dados[0]['ordem'];
        $anoAnterior = $ano-1;
        $dados = $this->conexao->query(
                "SELECT 
                    e.nome,
                    e.id_estudantes,
                    c.curso,
                    c.id_cursos,
                    c.ordem,
                    c.id_categoria 
                FROM 
                    estudantes e 
                INNER JOIN 
                    cursos_estudantes ce 
                ON 
                    ce.id_estudantes = e.id_estudantes 
                INNER JOIN 
                    cursos c 
                ON 
                    c.id_cursos = ce.id_cursos 
                WHERE 
                    c.id_cursos = '$curso' 
                AND 
                    ano_letivo = '$anoAnterior' 
                AND 
                    c.id_categoria <= 4 
                AND 
                    ce.id_estudantes 
                NOT IN ( 
                    SELECT 
                        ce.id_estudantes 
                    FROM 
                        cursos_estudantes ce 
                    INNER JOIN 
                        cursos c 
                    ON 
                        ce.id_cursos = c.id_cursos
                    WHERE 
                        c.ordem >= '$curso2' 
                    AND 
                        ce.ano_letivo = '$ano'
                    ) 
                AND 
                    e.status = 'Ativo' 
                ORDER BY 
                    e.nome ASC
                "
            );
        if($dados->rowCount() > 0) {
            $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $dados=array();
        }
        return $dados;
    }    

    public function buscaTurmaPorOrdem($ordem)
    {
        $ordem = $ordem + 1;
        $dados = array();
        $ano = Date('Y');                                
        $dados = $this->conexao->query(
            "SELECT 
                id_cursos,
                curso 
            FROM 
                cursos 
            WHERE 
                ordem = '$ordem' 
            AND 
                status = 0 
            ORDER BY 
                curso 
            ASC"
        );
        $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        return $dados;
    }

    public function vinculoTurmaEstudantePorIdEstudante($id) {
        $dados = array();
        $ano = Date('Y');                                
        $cmd = $this->conexao->query(
            "SELECT 
                te.* 
            FROM 
                turma_estudante te
            INNER JOIN 
                estudantes e
            ON
                e.id = te.estudantes_id 
            WHERE 
                te.estudantes_id = '$id' 
            AND 
                te.ano_letivo = '$ano' 
            ORDER BY 
                te.ano_letivo 
            DESC 
            LIMIT 1"
        );

        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function avancarEstudante($id,$curso)
    {
        $dados=array();
        $ano=Date('Y');         
        $dados = $this->conexao->query(
            "SELECT 
                id_estudantes 
            FROM 
                cursos_estudantes 
            WHERE 
                id_estudantes = '$id' 
            AND 
                ano_letivo = '$ano' 
            LIMIT 1"
        );
        $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        
        $curso2 = count($dados);

        if($curso2 == 0) {
        
            $this->conexao->beginTransaction();
            try {
                $dados=$this->conexao->prepare(
                    "INSERT INTO 
                        cursos_estudantes(
                            id_estudantes,
                            id_cursos,
                            ano_letivo
                        ) 
                    VALUES(
                        :estudante,
                        :curso,
                        :ano
                        )"
                );
                $dados->bindValue(':estudante',$id);
                $dados->bindValue(':curso',$curso);
                $dados->bindValue(':ano',$ano);
                $dados = $dados->execute();
                $this->conexao->commit();

                return $dados;
            } catch (\Throwable $th) {
                //throw $th;
                $this->conexao->rollback();
                return false;
            }
        }
    }

    public function buscaEstudantesPorProfessor($code)
    {
        $dados = [];
        $dados = $this->conexao->query(
            "SELECT DISTINCT 
                e.id_estudantes
            FROM 
                estudantes e
            INNER JOIN 
                cursos_estudantes ce
            ON
                ce.id_estudantes = e.id_estudantes
            INNER JOIN 
                disciplinas d
            ON  
                d.id_cursos = ce.id_cursos
            INNER JOIN
                professores p
            ON
                p.id_professores = d.id_professores
            WHERE 
                p.code = '{$code}' 
            AND 
                e.status = 'Ativo'
            ");

        if($dados->rowCount() > 0) {
            $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
        }

        return $dados;
    }

    public function buscaEstudantePorTurmaEAno($turma, $ano)
    {
        $dados = [];
        // $ano = (int)$ano - 1;
        $dados = $this->conexao->query(
            "SELECT 
                e.nome,
                e.matricula,
                c.curso,
                c.id_categoria
            FROM 
                estudantes e 
            INNER JOIN 
                cursos_estudantes ce 
            ON 
                e.id_estudantes = ce.id_estudantes 
            INNER JOIN 
                cursos c 
            ON 
                ce.id_cursos = c.id_cursos 
            WHERE 
                c.id_cursos = $turma 
            AND 
                ce.ano_letivo = '$ano'");

            if($dados->rowCount() > 0) {
                $dados = $dados->fetchAll(PDO::FETCH_ASSOC);
            }

            return $dados;
    }

    public function buscaEstudantes()
    {      
       $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                matricula,
                nome,
                email,
                id,
                status 
            FROM 
                estudantes 
            ORDER BY 
                status 
            ASC 
            LIMIT 36");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaEstudantePorNome($params)
    {      
       $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                matricula,
                nome,
                cpf,
                email,
                id_estudantes,
                status 
            FROM 
                estudantes 
            WHERE
                nome like '%$params%' 
            ORDER BY 
                status 
            ASC 
            LIMIT 36");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaEstudantePorId($params)
    {      
       $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
               *
            FROM 
                estudantes 
            WHERE
                id = '$params' 
            ORDER BY 
                status 
            ASC 
            LIMIT 1");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function buscaTodosEstudantes()
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                matricula,
                nome,
                email,
                id,
                status 
            FROM 
                estudantes 
            ORDER BY 
                id
            DESC");        
        if($cmd->rowCount()>0)
        {
            $dados=$cmd->fetchAll(PDO::FETCH_ASSOC);
           
        }else{
            $dados=array();
        }
        return $dados;
    }

    public function preparaVinculoTurmaEstudante($dados)
    {
        if(empty($dados)){
            return "sem registro";
        }
        
        $id = $dados['id'];
        $turma = $dados['turma'];

        if(empty($turma)){
            return "verifique a turma!";
        }
        
        $existeVinculo = $this->verificaVinculoEstudante($id);

        try{
            if(count($existeVinculo) == 0){
                $dados = $this->inserirVinculoEstudante(
                   $id,
                   $turma
                ); 

                if($dados == 1) {
                    return "Dados cadastrados com sucesso!";
                } else {
                    return $dados;
                }
            }

            $dados = $this->updateVinculoEstudante(
                $id,
                $turma
            ); 

            return $dados;
        }catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    private function verificaVinculoEstudante($id){
        return $this->vinculoTurmaEstudantePorIdEstudante($id);
    }

    private function inserirVinculoEstudante($id, $turma){
        $this->conexao->beginTransaction();
        try {
      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    turma_estudante 
                SET 
                    estudantes_id = :id, 
                    turmas_id = :turma, 
                    ano_letivo = :ano"
                );

            $cmd->bindValue(':id',$id);
            $cmd->bindValue(':turma',$turma);
            $cmd->bindValue(':ano',Date('Y'));
            $dados = $cmd->execute();
            if($dados){
                $dados = "Vinculo efetuado!";
            }
            $this->conexao->commit();
            return $dados;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return null;
        }
    }

    private function updateVinculoEstudante($id, $turma){
        $this->conexao->beginTransaction();
        try {
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    turma_estudante 
                SET 
                    turmas_id=:turma 
                WHERE 
                    estudantes_id=:id 
                AND 
                    ano_letivo = :ano"
            );
            $cmd->bindValue(':id',$id);
            $cmd->bindValue(':turma',$turma);
            $cmd->bindValue(':ano',Date('Y'));
            $dados = $cmd->execute();
            if($dados){
                $dados="Vinculo Atualizado!";
            }
            $this->conexao->commit();
            return $dados;
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return null;
        }
    }

    public function preparaInsertEstudante($dados)
    {
        if(empty($dados)){
            return false;
        }
        
        $codigo = $dados['codigo'];
        $nome = $dados['nome'];
        $endereco = $dados['endereco'];
        $email = $dados['email'];
        $telefone = $dados['telefone'];

        if(empty($email)){
            return "verifique seu email!";
        }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email: '$email' não é valido.\n ex: ist@escolaist.com.br ";
        }

        if(empty($nome)){
            return "verifique seu nome!";
        }

        try{
            $existeTurma = $this->verificaEstudante($nome,$cpf,$codigo);

            if(count($existeTurma) == 0){
                $dados = $this->inserirEstudante(
                    $codigo,
                    $nome,                    
                    $email,
                    $telefone,
                    $endereco,
                ); 

                if($dados == 1) {
                    return "Dados cadastrados com sucesso!";
                } else {
                    return $dados;
                }
            }else{
                return "Estudante existente";
            }
        }catch(Exception $th){
            return false;
        }
    }

    private function verificaEstudante($nome,$codigo)
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                estudantes 
            WHERE 
                nome = '$nome' 
            AND 
                matricula = '$codigo' 
            ORDER BY
                nome 
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

    private function inserirEstudante (
        $codigo,
        $nome,
        $email,
        $telefone,
        $endereco
    )
    {
        $dados = [];
        $d=Date('d');
        $this->conexao->beginTransaction();
        try {
           
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    estudantes 
                SET 
                    matricula = :codigo,
                    nome = :nome,                    
                    endereco = :endereco,
                    email = :email,
                    telefone = :telefone"
            );
            $cmd->bindValue(':codigo',$codigo);
            $cmd->bindValue(':nome',$nome);          
            $cmd->bindValue(':endereco',$endereco);
            $cmd->bindValue(':email',$email);
            $cmd->bindValue(':telefone',$telefone);
            $cmd->execute();
            
            if($cmd){
                $cmd2 = $this->conexao->prepare(
                    "INSERT INTO 
                        usuarios 
                    SET
                        code = :codigo,
                        nome = :nome,
                        email = :email,                        
                        senha = :senha,
                        painel = :painel
                    ");

                $cmd2->bindValue(':codigo',$codigo);
                $cmd2->bindValue(':senha',md5($codigo.$d));
                $cmd2->bindValue(':nome',$nome); 
                $cmd2->bindValue(':email',$email);
                $cmd2->bindValue(':painel',"Estudante");
                $dados = $cmd2->execute();
                if($dados){            
                    $dados=$this->prepareEmail($nome,$email,$cpf);
                    if(!$dados){
                        $dados="erro ao enviar email";
                    } else{
                        $dados="Cadastro efetuado com sucesso!";
                    }
                } else{
                    $dados="Estudante e Login não foi criado";
                    $this->conexao->rollback();
                    return $dados;
                }
                
            }

            $this->conexao->commit();

            if($dados){
                $dados=true;
            }

            return $dados;
        //code...
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return $th->getMessage();
        }
    }

    public function preparaUpdateEstudante($dados, $id)
    {
        if(empty($dados)){
            return "sem registro";
        }
        $codigo = $dados['codigo'];
        $nome = $dados['nome'];
        $endereco = $dados['endereco'];
        $email = $dados['email'];
        $telefone = $dados['telefone'];

        if(empty($email)){
            return "verifique seu email!";
        }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email: '$email' não é valido.\n ex: teste@escolae.com ";
        }

        try{
                $dados = $this->updateEstudante(
                    $codigo,
                    $nome,                 
                    $email,
                    $telefone,                 
                    $endereco,               
                    $id
                ); 
                if($dados) {
                    return "Dados atualizados com sucesso!";
                }
        } catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    private function updateEstudante(
        $codigo,
        $nome,       
        $email,
        $telefone,        
        $endereco,
        $id
    ) {
        $dados = [];
        $this->conexao->beginTransaction();
        try {
            
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    estudantes 
                SET 
                    matricula = :codigo,
                    nome = :nome,                   
                    endereco = :endereco,
                    email = :email,
                    telefone = :telefone 
                WHERE 
                    id = :id"
                );
                $cmd->bindValue(':codigo',$codigo);
                $cmd->bindValue(':nome',$nome);           
                $cmd->bindValue(':endereco',$endereco);
                $cmd->bindValue(':email',$email);
                $cmd->bindValue(':telefone',$telefone);
                $cmd->bindValue(':id',$id);
                $cmd->execute();

                if($cmd){
                    $cmd2 = $this->conexao->prepare(
                        "UPDATE 
                            usuarios 
                        SET 
                            email = :email 
                        WHERE 
                            code = :codigo"
                    );
                    $cmd2->bindValue(':email',$email);    
                    $cmd2->bindValue(':codigo',$codigo);
                    $dados=$cmd->execute();
                    if($dados){            
                        $dados=$this->prepareUpdateEmail($nome,$email,$cpf);
                        if(!$dados){
                            $dados="erro ao enviar email";
                        } else{
                            $dados="efetuado com sucesso!";
                        }
                    }
                }

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

    public function changeStatusEstudante($codigo)
    {
        $dados = [];
        $this->conexao->beginTransaction();
        try {            
            $cmd = $this->conexao->query("SELECT status FROM estudantes where id = '$codigo'"); 
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            $status = $dados[0]['status'];
        
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    estudantes 
                SET 
                    status = :status 
                WHERE id = :id"
            );

            if($status != "1") {
                $cmd->bindValue(':status','1');
            } else {
                $cmd->bindValue(':status','0');
            }
                $cmd->bindValue(':id',$codigo);
                $cmd->execute();

                if($cmd){
                    $cmd2 = $this->conexao->prepare(
                        "UPDATE 
                            usuarios 
                        SET 
                            status = :status 
                        WHERE 
                            code = :codigo"
                    );

                    if($status != "1") {
                        $cmd2->bindValue(':status','1');
                    } else {
                        $cmd2->bindValue(':status','0');
                    }  
                    $cmd2->bindValue(':codigo',$codigo);
                    $dados = $cmd2->execute();
                    if($dados){            
                        $dados=$this->prepareUpdateEmail($nome,$email,$cpf);
                        if(!$dados){
                            $dados="erro ao enviar email";
                        } else{
                            $dados="efetuado com sucesso!";
                        }
                    }
                }

                $this->conexao->commit();

            return 'status mudado';
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return false;
        }
    }

    private function prepareEmail ($nome_usu,$email_usu,$senha_usu){
        $url_sistema = "https:://centroeducacionaldetucano.edu.br/login";
        $mensagem = "
    
        Olá $nome_usu!! 
        <br><br> Sua senha é <b>$senha_usu </b>
    
        <br><br> Ir Para o Sistema -> <a href='$url_sistema'  target='_blank'> Clique Aqui </a>
    
        ";
        $subject = 'Acesso EscolaE';

        ConexaoModel::enviarEmail($email_usu, $subject, $mensagem);
    }

    private function prepareUpdateEmail ($nome_usu,$email_usu,$senha_usu){
        $url_sistema = "https:://centroeducacionaldetucano.edu.br/login";
        $mensagem = "
    
        Olá $nome_usu!! 
        <br><br> Seu email de acessor foi alterado  <b>$email_usu </b>
    
        <br><br> Ir Para o Sistema -> <a href='$url_sistema'  target='_blank'> Clique Aqui </a>
    
        ";
        $subject = 'Acesso EscolaE';

        ConexaoModel::enviarEmail($email_usu,$subject ,$mensagem);
    }
}