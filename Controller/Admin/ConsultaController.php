<?php

class ConsultaController extends Controller {

    private $reservaModel;
    
    public function __construct()
    {
        $this->reservaModel = new ReservaModel();
    }

    public function hospedadas(){
        echo json_encode(
            $this->reservaModel->buscaHospedadas('')
        );
    }

    public function checkin(){
        echo json_encode(
            $this->reservaModel->buscaCheckin('')
        );
    }

    public function checkout(){
        echo json_encode(
            $this->reservaModel->buscaCheckout('')
        );
    }

    public function confirmada(){
        echo json_encode(
            $this->reservaModel->buscaConfirmada('')
        );
    }

    public function reservada(){
        echo json_encode(
            $this->reservaModel->buscaReservadas('')
        );
    }
}