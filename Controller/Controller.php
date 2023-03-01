<?php
require_once "./Config/autoload.php";

class Controller {
    protected $site_model;
    protected $disciplina_id;
    protected $background;

    public function __construct(){
        $this->site_model = new SiteModel();
    }

    public function viewSite($view) {
        require "View/Site/{$view}.php";
    }

    public function viewAluno($view) {
        require "View/Aluno/{$view}.php";
    }

    public function viewCoordenador($view, $id = null) {
        $this->disciplina_id = $id;
        $this->background();
        require "View/Coordenacao/{$view}.php";
    }
    
    public function viewProfessor($view, $id = null) {
        $this->disciplina_id = $id;
        $this->background();
        require "View/Professor/{$view}.php";
    }

    public function viewAdmin($view, $request = null, $page = null) {
        $sub_view = $page;
        $bg = "Light";
        $this->background();
        require "View/Administrativo/{$view}.php";
    }

    public function background(){
        $background = $this->app_model->buscabackground($_SESSION['code']);
        $this->background = is_null($background) ? 0 : $background[0]['status'];
    }
}