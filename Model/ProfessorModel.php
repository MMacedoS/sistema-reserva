<?php


class ProfessorModel extends ConexaoModel
{
    protected $conexao;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function buscaProfessorPorTurmaEAno($turma)
    {
        $dados = [];
        // $ano = (int)$ano - 1;
        $cmd = $this->conexao->query(
            "SELECT 
                p.nome as professor, 
                c.curso,
                p.id_professores,
                l.nome as disciplina 
            FROM 
                professores p 
            INNER JOIN 
                disciplinas d 
            ON 
                d.id_professores = p.id_professores 
            INNER JOIN 
                lista_disc l 
            ON 
                l.id_lista = d.disciplina
            INNER JOIN 
                cursos c 
            ON 
                c.id_cursos = d.id_cursos 
            WHERE 
                c.id_cursos = '$turma' 
            AND 
                c.status = 0");

        if($cmd->rowCount() > 0) {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return $dados;
    }

    public function buscaProfessor($param)
    {      
        $$dados = [];
        // $ano = (int)$ano - 1;
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                professores 
            WHERE 
                nome 
            LIKE 
                '%$param%'  
            ORDER BY 
                nome 
            ASC");        
        if($cmd->rowCount()>0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
        
        }else{
            $dados = array();
        }
        return $dados;
    }

    public function buscaTodosProfessores()
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
            id,
            nome,
            email,
            matricula
            status 
            FROM 
                professores 
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

    public function buscaProfessorPorId($params)
    {      
       $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
               *
            FROM 
                professores 
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

    public function preparaInsertProfessor($dados)
    {
        if(empty($dados)){
            return "sem registro";
        }

        $codigo = $dados['codigo'];
        $nome = $dados['nome'];  
        $graduacao=$dados['graduacao'];
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
            $existeTurma = $this->verificaProfessor($nome,$cpf,$email);

            if(count($existeTurma) == 0){

                $dados = $this->inserirProfessor(
                    $codigo,
                    $nome,
                    $graduacao,
                    $email,
                    $telefone
                ); 

                if($dados == 1) {
                    return "Dados cadastrados com sucesso!";
                } else {
                    return $dados;
                }
            }else{
                return "Professor existente!";
            }
        }catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    private function verificaProfessor($nome,$cpf,$email)
    {      
        $dados = [];
        $cmd = $this->conexao->query(
            "SELECT 
                * 
            FROM 
                professores 
            WHERE 
                nome = '$nome' 
            AND 
               email = '$email' 
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

    private function inserirProfessor (
        $codigo,
        $nome,
        $graduacao,
        $email,
        $telefone
    )
    {
        $dados = [];
        $d=Date('d');
        $this->conexao->beginTransaction();
        try {
           
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    professores 
                SET 
                    matricula = :codigo,
                    nome = :nome,
                    graduacao = :graduacao,
                    email = :email,
                    telefone = :telefone,
                    status = :status"
            );
            $cmd->bindValue(':codigo',$codigo);
            $cmd->bindValue(':nome',$nome);
            $cmd->bindValue(':graduacao',$graduacao);
            $cmd->bindValue(':email',$email);
            $cmd->bindValue(':telefone',$telefone);
            $cmd->bindValue(':status',1);
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
                        status = :status,
                        painel = :painel
                    ");

                $cmd2->bindValue(':codigo',$codigo);
                $cmd2->bindValue(':nome',$nome);
                $cmd2->bindValue(':senha',md5($codigo.$d));
                $cmd2->bindValue(':email',$email);
                $cmd2->bindValue(':status',1);
                $cmd2->bindValue(':painel',"Professor");
                $dados = $cmd2->execute();
                if($dados){            
                    $dados=$this->prepareEmail($nome,$email,$cpf);
                    if(!$dados){
                        $dados="erro ao enviar email";
                    } else{
                        $dados="Cadastro efetuado com sucesso!";
                    }
                } else{
                    $dados="Professor e Login não foi criado";
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

    public function preparaUpdateProfessor($dados, $id)
    {
        if(empty($dados)){
            return "sem registro";
        }
        $codigo = $dados['codigo'];
        $nome = $dados['nome'];
        $graduacao=$dados['graduacao'];
        $email = $dados['email'];
        $telefone = $dados['telefone'];

        if(empty($email)){
            return "verifique seu email!";
        }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email: '$email' não é valido.\n ex: ist@escolaist.com.br ";
        }

        if(empty($id)){
            return "não encontrado !";
        }

        try{
                $dados = $this->updateProfessor(
                    $codigo,
                    $nome,
                    $graduacao,
                    $email,
                    $telefone,
                    $id
                ); 
                if($dados) {
                    return "Dados atualizados com sucesso!";
                }
        } catch(Exception $th){
            return "<ERRO> Dados não cadastrados". $th;
        }
    }

    private function updateProfessor(
        $codigo,
        $nome,
        $graduacao,
        $email,
        $telefone,
        $id
    ) {
        $dados = [];
        $this->conexao->beginTransaction();
        try {
            
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    professores 
                SET 
                    matricula = :codigo,
                    nome = :nome,
                    graduacao = :graduacao,
                    email = :email,
                    telefone = :telefone
                WHERE 
                    id = :id"
                );
                $cmd->bindValue(':codigo',$codigo);
                $cmd->bindValue(':nome',$nome);
                $cmd->bindValue(':graduacao',$graduacao);
                $cmd->bindValue(':email',$email);
                $cmd->bindValue(':telefone',$telefone);
                $cmd->bindValue(':id',$id);
                $cmd->execute();

                if($cmd){
                    $cmd2 = $this->conexao->prepare(
                        "UPDATE 
                            usuarios 
                        SET 
                            nome = :nome,
                            email = :email 
                        WHERE 
                            code = :codigo"
                    );
                    $cmd2->bindValue(':nome',$nome);  
                    $cmd2->bindValue(':email',$email);    
                    $cmd2->bindValue(':codigo',$codigo);
                    $dados=$cmd2->execute();
                    if($dados){            
                        // $dados = $this->prepareUpdateEmail($nome,$email,$cpf);
                        // if(!$dados){
                        //     $dados="erro ao enviar email";
                        // } else{
                        //     $dados="efetuado com sucesso!";
                        // }
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
            return $th->getMessage();
        }
    }

    public function changeStatusProfessor(int $codigo)
    {
        $dados = [];
        $this->conexao->beginTransaction();
        try {            
            $cmd = $this->conexao->query("SELECT status, matricula FROM professores where id = '$codigo'"); 
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            $status = $dados[0]['status'];
            $codigo = $dados[0]['matricula'];
        
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    professores 
                SET 
                    status = :status 
                WHERE id = :id"
            );

            if($status != 1) {
                $cmd->bindValue(':status',1);
            } else {
                $cmd->bindValue(':status',0);
            }
                $cmd->bindValue(':id',$codigo);
                $cmd->execute();

                $cmd2 = $this->conexao->prepare(
                "UPDATE 
                    usuarios 
                SET 
                    status = :status
                WHERE 
                    code = :codigo"
            );
            if($status != 1) {
                $cmd2->bindValue(':status',1);
            } else {
                $cmd2->bindValue(':status',0);
            }

            $cmd2->bindValue(':codigo',$codigo);
            $dados=$cmd2->execute();

                $this->conexao->commit();

            return "sucesso";
        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return false;
        }
    }


    private function prepareEmail ($nome_usu,$email_usu,$senha_usu){
        $url_sistema = "https:://escolaisttucano.com.br/login";
        $mensagem = "
    
        Olá $nome_usu!! 
        <br><br> Sua senha é <b>$senha_usu </b>
    
        <br><br> Ir Para o Sistema -> <a href='$url_sistema'  target='_blank'> Clique Aqui </a>
    
        ";
        $subject = 'Acesso EscolaIST';

        ConexaoModel::enviarEmail($email_usu, $subject, $mensagem);
    }

    private function prepareUpdateEmail ($nome_usu,$email_usu,$senha_usu){
        $url_sistema = "https:://escolaisttucano.com.br/login";
        $mensagem = "
    
        Olá $nome_usu!! 
        <br><br> Seu email de acessor foi alterado  <b>$email_usu </b>
    
        <br><br> Ir Para o Sistema -> <a href='$url_sistema'  target='_blank'> Clique Aqui </a>
    
        ";
        $subject = 'Acesso EscolaE';

        ConexaoModel::enviarEmail($email_usu,$subject ,$mensagem);
    }
}