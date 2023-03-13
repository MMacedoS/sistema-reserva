<?php
setlocale(LC_ALL,'pt_BR.utf8');
date_default_timezone_set('America/Sao_Paulo');
define('SERVIDOR','mysql');
define('BANCO', 'pousada');
define('USUARIO', 'root');
define('SENHA','12345');

class Conexao{
    private $conexaoSql;
    private $charset;
    public $pdo;
    private $conexao;
    
    public function MontarConexao()
    {
        if(!isset($this->pdo)){
            try {
                // $this->charset=array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
                $this->pdo=new PDO("mysql:host=".SERVIDOR.";dbname=".BANCO.";",USUARIO,SENHA);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $th)
            {
                die("ERRO: #".$th->getCode()."<br>
                Mensagem:".$th->getMessage()."<br>
                No Arquivo:".$th->getFile()."<br
                Na linha:".$th->getLine());
            }
        }
        return $this->pdo;
    }
    
}
?>