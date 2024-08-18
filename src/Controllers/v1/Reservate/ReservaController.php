<?php

namespace App\Controllers\v1\Customer;

use App\Controllers\Controller;
use App\Repositories\Reservate\ReservaRepository;
use App\Request\Request;
use App\Utils\Paginator;
use App\Utils\Validator;

class ReservaController extends Controller
{
    protected $reservaRepository;
    protected $permissaoRepository;

    public function __construct()
    {   
        parent::__construct();
        $this->reservaRepository = new ReservaRepository();
    }

    public function index(Request $request) {
        $reserva = $this->reservaRepository->all();
        $perPage = 10;
        $currentPage = $request->getParam('page') ? (int)$request->getParam('page') : 1;
        $paginator = new Paginator($reserva, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        $data = [
            'reservas' => $paginatedBoards,
            'links' => $paginator->links()
        ];

        return $this->router->view('reservate/index', ['active' => 'register', 'data' => $data]);
    }

    public function create() {
        return $this->router->view('reservate/create', ['active' => 'register']);
    }

    public function store(Request $request) {
        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'dt_checkin' => 'required',
            'dt_checkout' => 'required',
            'id_apartamento' => 'required',
            'status' => 'required',
            'customers' => 'required',
        ];

        if (!$validator->validate($rules)) {
            return $this->router->view(
                'reservate/create', 
                [
                    'active' => 'register', 
                    'errors' => $validator->getErrors()
                ]
            );
        } 
        
        $created = $this->reservaRepository->create($data);

        if(is_null($created)) {            
        return $this->router->view('reservate/create', ['active' => 'register', 'danger' => true]);
        }

        return $this->router->redirect('reserva/');
    }

    public function edit(Request $request, $id) {
        $cliente = $this->reservaRepository->findByUuid($id);
        
        if (is_null($cliente)) {
            return $this->router->view('customer/', ['active' => 'register', 'danger' => true]);
        }

        return $this->router->view('customer/edit', ['active' => 'register', 'cliente' => $cliente]);
    }

    public function update(Request $request, $id) {
        $cliente = $this->reservaRepository->findByUuid($id);

        if (is_null($cliente)) {
            return $this->router->view('cliente/', ['active' => 'register', 'danger' => true]);
        }

        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:45'
        ];

        if (!$validator->validate($rules)) {
            return $this->router->view(
                'customer/edit', 
                [
                    'active' => 'register', 
                    'errors' => $validator->getErrors()
                ]
            );
        } 
        
        // $updated = $this->reservaRepository->update($cliente->id, $data);
        
        // if(is_null($updated)) {            
        // return $this->router->view('customer/edit', ['active' => 'register', 'danger' => true]);
        // }

        return $this->router->redirect('cliente/');
    }

    public function delete(Request $request, $id) {
        $cliente = $this->reservaRepository->findByUuid($id);
        
        if (is_null($cliente)) {
            return $this->router->view('customer/', ['active' => 'register', 'danger' => true]);
        }

        $cliente = $this->reservaRepository->delete($cliente->id);

        return $this->router->redirect('cliente/');
    }
}