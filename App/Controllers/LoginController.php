<?php

require_once __DIR__ . "/../Config/autoload.php";
require_once __DIR__ . "/../Trait/ErrorLoggingTrait.php";

class LoginController extends Controller{  
    use ErrorLoggingTrait;

    protected $login_model;
    protected $url;

    public function __construct() 
    {        
        $this->login_model = new LoginModel();
        $this->validLogin();
        $this->url = ROTA_GERAL;
    }

    public function findParamByParam($param) {
        $app_model = new AppModel();     
        return $app_model->buscaParamByParam($param)[0];
    }

    public function index() 
    {       
        require __DIR__ . '/../../View/Login/index.php';
    }

    private function validLogin() 
    {
        if (
            isset($_SESSION['acesso']) || 
            isset($_SESSION['logado'])
        ) {
            return $this->preparePainel();
        }
    }

    private function preparePainel() 
    {
        $painel_session =  $_SESSION['painel'];

        switch ($painel_session) {
           
            case 'Administrador':
                header('Location: '.$this->url.'/Administrativo');
            break;

            case 'Recepcao':
                header('Location: '.$this->url.'/Administrativo');
            break;
            // default: 
            //     header('Location: '.$this->url.'/Login');
            // break;            
        }
    }

    public function logar() {
        
       $login = $this->login_model->logar($_POST);
       if(!is_null(
            $login
            )
        ) {
            $_SESSION['code'] = $login['id'];
            $_SESSION['nome'] = $login['nome'];
            $_SESSION['painel'] = $login['painel'];
            $_SESSION['acesso'] = md5($login['painel'] . date('y-m-d'));
            $_SESSION['logado'] = true;            
        }
        
        return header('Location: '.$this->url.'/Login');
    }

    public function logouf() 
    {
        session_start();
        session_destroy();            
        return header('Location: '.$this->url.'/Login');    
    }


}