<?php

class DiariaController extends \Controller{
    use DateTrait;

    protected $diarias_model;

    public function __construct() {
        $this->diarias_model = new DiariasModel();      
    }

    public function addDiaria($id)
    {
        $dados = $this->diarias_model->inserirDiaria($_POST, $id);
        echo json_encode($dados);
    }

    public function getDiariaPorId($id)
    {
        $consumo = $this->diarias_model->findById($id);
        echo json_encode($consumo);
    }

}