<?php

class ImpressaoController extends \Controller{
    
    protected $reserva_model;
    protected $consumo_model;
    protected $pagamento_model;

    public function __construct() 
    {        
        $this->reserva_model = new ReservaModel();   
        $this->consumo_model = new ConsumoModel();    
        $this->pagamento_model = new PagamentoModel();       
    }
    
    public function cliente($request = null)
    {       
        $dados = (object)$this->reserva_model->getDadosReservas($request)['data'][0];
        $consumos = (object)$this->consumo_model->getDadosConsumos($request)['data'];
        $pagamentos = $this->pagamento_model->getDadosPagamentos($request)['data'];
        $dados->lista_consumos = $consumos;
        $dados->pagamentos = $pagamentos;
        $this->viewImpressao('nota_cliente',$dados);
    }   

}