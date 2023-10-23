<?php

class AdministrativoController extends \Controller{
    protected $financeiro_controller;
    protected $apartamento_model;
    protected $produto_model;
    
    public function __construct() {
        $this->validPainel();     
        $this->financeiro_controller = new FinanceiroController();       
    }

    private function validPainel() {        
        if ($_SESSION['painel'] != 'Administrador' && $_SESSION['painel'] != 'Recepcao') {   
            session_start();
            session_destroy();            
            return header('Location: '. $this->url .'/Login');            
        }       
    }

    public function index() {        
        $this->viewAdmin('consultas');
    }

    public function apartamentos($request = null) {
        $this->viewAdmin('apartamentos',$request,"");
    }
    
    public function buscaApartamentos($request =  null)
    {
        $apartamento_controller = new ApartamentoController();
        return $apartamento_controller->buscaApartamentos($request);
    }

    public function funcionarios($request = null) {
        $this->viewAdmin('funcionarios',$request,"");
    }

    public function buscaFuncionarios($request =  null)
    {
        $funcionario_controller = new FuncionarioController();
        return $funcionario_controller->buscaFuncionarios($request);
    }

    public function hospedes($request = null) {
        $this->viewAdmin('hospedes',$request,"");
    }

    public function buscaHospedes($request =  null)
    {
        $hospedes_controller = new HospedeController();
        return $hospedes_controller->buscaHospedes($request);
    }

    public function reservas($request = null) {
        $this->viewAdmin('reservas',$request,"");
    }

    public function consultas($request = null) {
        $this->viewAdmin('consultas',$request,"");
    }

    public function mapas($request = null) {
        $this->viewAdmin('mapas',$request,"");
    }

    // public function buscaReservas($request =  null)
    // {        
    //     $reservas_controller = new ReservaController();
    //     return $reservas_controller->buscaReservas($request);
    // }

    public function hospedadas($request = null) {
        $this->viewAdmin('hospedadas',$request,"");
    }

    public function buscaHospedadas($request =  null)
    {        
        $reservas_controller = new ReservaController();
        return $reservas_controller->buscaHospedadas($request);
    }

    public function checkin($request = null) {
        $this->viewAdmin('checkin',$request,"");
    }

    public function financeiro($request = null) {
        $this->viewAdmin('checkin',$request,"");
    }

    public function buscaCheckin($request =  null)
    {        
        $reservas_controller = new ReservaController();
        return $reservas_controller->buscaCheckin($request);
    }

    public function listproduto($request = null)
    {
        $this->produto_model = new ProdutoModel();     
        return $this->produto_model->getProdutos($request);
    }

    public function listApartamento()
    {
        $this->apartamento_model = new ApartamentoModel();     
        return $this->apartamento_model->getApartamento();
    }

    public function checkout($request = null) {
        $this->viewAdmin('checkout',$request,"");
    }

    public function disponiveis($request = null){
        $this->viewAdmin('disponiveis',$request,"");
    }

    public function buscaCheckout($request =  null)
    {        
        $reservas_controller = new ReservaController();
        return $reservas_controller->buscaCheckout($request);
    }

    public function confirmada($request = null) {
        $this->viewAdmin('confirmada',$request,"");
    }

    public function buscaConfirmada($request =  null)
    {        
        $reservas_controller = new ReservaController();
        return $reservas_controller->buscaConfirmada($request);
    }

    public function produtos($request = null) {
        $this->viewAdmin('produtos',$request,"");
    }

    public function buscaProdutos($request =  null)
    {        
        $produto_controller = new ProdutoController();
        return $produto_controller->buscaProduto($request);
    }

    public function buscaEntradaProdutos($request =  null)
    {        
        $produto_controller = new ProdutoController();
        return $produto_controller->buscaEntradaProduto();
    }

    public function entradaEstoque($request = null) {
        $this->viewAdmin('entradaEstoque',$request,"");
    }

    public function estoque($request = null) {
        $this->viewAdmin('estoque',$request,"");
    }

    public function buscaEstoques($request = null) {
        $produto_controller = new ProdutoController();
        return $produto_controller->buscaEstoques($request);
    }

    public function buscaEntradaEstoques($request = null) {
        $request = self::splitString($request);
        $produto_controller = new ProdutoController();
        $dados = $produto_controller->buscaEntradaProduto($request);
  
        if(!empty($dados)){
            return $dados['data'];
        }

        return null;
    }

    public function buscaProdutosSelect()
    {        
        $produto_controller = new ProdutoController();
        return $produto_controller->buscaProdutos();
    }

    public function movimentacoes($request = null)
    {
        $this->viewAdmin('movimentacoes',$request,"");
    }

    public function buscaMovimentos($request = null)
    {
        return $this->financeiro_controller->buscaMovimentos($request);
    }

    public function entrada($request = null)
    {
        $this->viewAdmin('entrada',$request,"");
    }

    public function buscaEntrada($request = null)
    {
        return $this->financeiro_controller->buscaEntrada($request);
    }

    public function saida($request = null)
    {
        $this->viewAdmin('saida',$request,"");
    }

    public function buscaSaida($request = null)
    {
        return $this->financeiro_controller->buscaSaida($request);
    }

    // Site Admin

    public function bannerSite($request = null)
    {
        $this->viewAdmin('Site/banner',$request,"");
    }

    public function cardSite($request = null)
    {
        $this->viewAdmin('Site/cardApt',$request,"");
    }

    public function textSite($request = null)
    {
        $this->viewAdmin('Site/textSite',$request,"");
    }

    public function colorSite($request = null)
    {
        $this->viewAdmin('Site/colorSite',$request,"");
    }

    public function imagesSite($request = null)
    {
        $this->viewAdmin('Site/imagesSite',$request,"");
    }

    public function configSite($request = null)
    {
        $this->viewAdmin('Site/configSite',$request,"");
    }

}