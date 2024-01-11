<?php
setlocale(LC_ALL,'pt_BR.utf8');
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . "/../Trait/ErrorLoggingTrait.php";

class Conexao {
    
    private const SERVIDOR = 'mysql';
    private const BANCO = 'pousada';
    private const USUARIO = 'root';
    private const SENHA ='12345';
    public $pdo;

    use ErrorLoggingTrait;

    public function MontarConexao()
    {
        if(!isset($this->pdo)){
            try {
                // $this->charset=array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
                $this->pdo=new PDO("mysql:host=" . self::SERVIDOR . ";dbname=" . self::BANCO . ";", self::USUARIO, self::SENHA);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $th)
            {
                $this->logError("ERRO: #".$th->getCode()."<br>
                Mensagem:".$th->getMessage()."<br>
                No Arquivo:".$th->getFile()."<br
                Na linha:".$th->getLine());
                return ;
            }
        }
        return $this->pdo;
    }    
}
