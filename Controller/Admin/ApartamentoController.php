<?php

class ApartamentoController extends \Controller{

    protected $apartamento_model;

    public function __construct() {
        $this->apartamento_model = new ApartamentoModel();      
    }

    public function buscaApartamentos($request =  null)
    {
        return $this->apartamento_model->findApartamentos($request);
    }

    public function buscaApartamentoPorId($id)
    {
        echo json_encode($this->apartamento_model->findById($id));
    }

    public function salvarApartamentos() {
        $apartamento = $this->apartamento_model->prepareInsertApartamento($_POST);
        echo json_encode($apartamento);
    }

    public function atualizarApartamentos($id)
    {
        $apartamento = $this->apartamento_model->prepareUpdateApartamento($_POST, $id);
        echo json_encode($apartamento);
    }

    public function changeStatusApartamentos($id)
    {
        $apartamento = $this->apartamento_model->prepareChangedApartamento($id);
        echo json_encode($apartamento);
    }
}