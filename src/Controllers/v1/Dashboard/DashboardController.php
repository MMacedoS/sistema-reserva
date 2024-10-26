<?php

namespace App\Controllers\v1\Dashboard;

use App\Controllers\Controller;
use App\Repositories\Product\ProdutoRepository;
use App\Repositories\Reservate\ReservaRepository;
use App\Request\Request;
use App\Utils\Paginator;

class DashboardController extends Controller
{
    protected $reservaRepository;
    protected $produtoRepository;

    public function __construct() {
        parent::__construct();
          
        $this->reservaRepository = new ReservaRepository(); 
        $this->produtoRepository = new ProdutoRepository();  
    }
    
    public function index(Request $request) {
        $this->indexFacility($request);
        // return $this->router->view('dashboard/index', ['active' => 'dashboard']);
    }

    public function indexFacility(Request $request) {
        $reserva = $this->reservaRepository->allHosted();   
        $product = $this->produtoRepository->all(['status' => 1]);    

        $data = [
            'reservas' => $reserva,
            'products' => $product
        ];

        return $this->router->view('dashboard/facility', ['active' => 'dashboard', 'data' => $data]);
    }
}