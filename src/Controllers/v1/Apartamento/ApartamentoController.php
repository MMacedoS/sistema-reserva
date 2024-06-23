<?php

namespace App\Controllers\v1\Apartamento;

use App\Config\Router;
use App\Repositories\Apartamento\ApartamentoRepository;
use App\Request\Request;
use App\Utils\Paginator;
use App\Utils\Validator;

class ApartamentoController
{
    protected $apartamentoRepository;
    private $router;

    public function __construct()
    {        
        $this->router = new Router();
        $this->apartamentoRepository = new ApartamentoRepository();
    }

    public function index(Request $request) {
        $apartamentos = $this->apartamentoRepository->all();
        $perPage = 10;
        $currentPage = $request->getParam('page') ? (int)$request->getParam('page') : 1;
        $paginator = new Paginator($apartamentos, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        $data = [
            'apartamentos' => $paginatedBoards,
            'links' => $paginator->links()
        ];
        return $this->router->view('apartamento/index', ['active' => 'hospedagens', 'data' => $data]);
    }

    public function create() {
        return $this->router->view('apartamento/create', ['active' => 'hospedagens']);
    }

    public function store(Request $request) {
        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:4',
            'category' => 'required',
            'status' => 'required',
            'description' => 'required',
        ];

        if (!$validator->validate($rules)) {
            return $this->router->view(
                'apartamento/create', 
                [
                    'active' => 'hospedagens', 
                    'errors' => $validator->getErrors()
                ]
            );
        } 
        
        $created = $this->apartamentoRepository->create($data);

        if(is_null($created)) {            
        return $this->router->view('apartamento/create', ['active' => 'hospedagens', 'danger' => true]);
        }

        return $this->router->redirect('apartamento/');
    }

    public function edit(Request $request, $id) {
        $apartamento = $this->apartamentoRepository->findByUuid($id);
        
        if (is_null($apartamento)) {
            return $this->router->view('apartamento/', ['active' => 'hospedagens', 'danger' => true]);
        }

        return $this->router->view('apartamento/edit', ['active' => 'hospedagens', 'apartamento' => $apartamento]);
    }

    public function update(Request $request, $id) {
        $apartamento = $this->apartamentoRepository->findByUuid($id);

        if (is_null($apartamento)) {
            return $this->router->view('apartamento/', ['active' => 'hospedagens', 'danger' => true]);
        }

        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:4',
            'category' => 'required',
            'status' => 'required',
            'description' => 'required',
        ];

        if (!$validator->validate($rules)) {
            return $this->router->view(
                'apartamento/edit', 
                [
                    'active' => 'hospedagens', 
                    'errors' => $validator->getErrors()
                ]
            );
        } 
        
        $updated = $this->apartamentoRepository->update($apartamento->id, $data);
        
        if(is_null($updated)) {            
        return $this->router->view('apartamento/edit', ['active' => 'hospedagens', 'danger' => true]);
        }

        return $this->router->redirect('apartamento/');
    }

    public function delete(Request $request, $id) {
        $apartamento = $this->apartamentoRepository->findByUuid($id);
        
        if (is_null($apartamento)) {
            return $this->router->view('apartamento/', ['active' => 'hospedagens', 'danger' => true]);
        }

        $apartamento = $this->apartamentoRepository->delete($apartamento->id);

        return $this->router->redirect('apartamento/');
    }

}