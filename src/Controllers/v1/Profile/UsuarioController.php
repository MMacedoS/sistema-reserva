<?php

namespace App\Controllers\v1\Profile;

use App\Config\Auth;
use App\Controllers\Controller;
use App\Repositories\Permission\PermissaoRepository;
use App\Repositories\Profile\UsuarioRepository;
use App\Request\Request;
use App\Utils\Paginator;
use App\Utils\Validator;

class UsuarioController extends Controller
{
    protected $usuarioRepository;
    protected $permissaoRepository;

    public function __construct()
    {   
        parent::__construct();
        $this->usuarioRepository = new UsuarioRepository();
        $this->permissaoRepository = new PermissaoRepository();
    }

    public function index(Request $request) {
        $usuario = $this->usuarioRepository->all();
        $perPage = 10;
        $currentPage = $request->getParam('page') ? (int)$request->getParam('page') : 1;
        $paginator = new Paginator($usuario, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        $data = [
            'usuarios' => $paginatedBoards,
            'links' => $paginator->links()
        ];

        return $this->router->view('profile/index', ['active' => 'register', 'data' => $data]);
    }

    public function create() {
        return $this->router->view('profile/create', ['active' => 'register']);
    }

    public function store(Request $request) {
        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:45',           
            'email' => 'required',
            'password' => 'required',
        ];

        if (!$validator->validate($rules)) {
            return $this->router->view(
                'usuario/create', 
                [
                    'active' => 'register', 
                    'errors' => $validator->getErrors()
                ]
            );
        } 
        
        $created = $this->usuarioRepository->create($data);

        if(is_null($created)) {            
        return $this->router->view('profile/create', ['active' => 'register', 'danger' => true]);
        }

        return $this->router->redirect('usuario/');
    }

    public function edit(Request $request, $id) {
        $usuario = $this->usuarioRepository->findByUuid($id);
        
        if (is_null($usuario)) {
            return $this->router->view('profile/', ['active' => 'register', 'danger' => true]);
        }

        return $this->router->view('profile/edit', ['active' => 'register', 'usuario' => $usuario]);
    }

    public function update(Request $request, $id) {
        $usuario = $this->usuarioRepository->findByUuid($id);

        if (is_null($usuario)) {
            return $this->router->view('usuario/', ['active' => 'register', 'danger' => true]);
        }

        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:45',
            'email' => 'required'
        ];

        if (!$validator->validate($rules)) {
            return $this->router->view(
                'profile/edit', 
                [
                    'active' => 'register', 
                    'errors' => $validator->getErrors()
                ]
            );
        } 

        $updated = $this->usuarioRepository->update($data, $usuario->id);
        
        if(is_null($updated)) {            
        return $this->router->view('profile/edit', ['active' => 'register', 'danger' => true]);
        }

        return $this->router->redirect('usuario/');
    }

    public function delete(Request $request, $id) {
        $usuario = $this->usuarioRepository->findByUuid($id);
        
        if (is_null($usuario)) {
            return $this->router->view('profile/', ['active' => 'register', 'danger' => true]);
        }

        $usuario = $this->usuarioRepository->delete($usuario->id);

        return $this->router->redirect('usuario/');
    }

    public function login(Request $request) 
    {
        return $this->router->view('login/login');
    }

    public function auth(Request $request) 
    {
        $data = $request->getBodyParams();
        $user = $this->usuarioRepository->getLogin($data['email'], $data['password']);
        $auth = new Auth();
        if ($auth->login($user)) {            
            return $this->router->redirect('dashboard/');
        }

        return $this->router->redirect('login/');
    }

    public function logout() {
        $auth = new Auth();
        $auth->logout();
        return $this->router->view('login/login', ['message' => 'Deslogado', 'success' => true]);
    }

    public function permissao(Request $request, $id) {
        $usuario = $this->usuarioRepository->findByUuid($id);
        
        if (is_null($usuario)) {
            return $this->router->view('profile/', ['active' => 'register', 'danger' => true]);
        }

        $permissoes = $this->permissaoRepository->all();
        $permissao = $this->usuarioRepository->findPermissions($usuario->id);

        $data = [
            'usuario' => $usuario, 
            'permissions_user' => $permissao, 
            'permissions' => $permissoes
        ];

        return $this->router->view('profile/permission', ['active' => 'register', 'data' => $data]);
    }

    public function add_permissao(Request $request, $id) 
    {
        $data = $request->getBodyParams();
        $usuario = $this->usuarioRepository->findByUuid($id);
        
        if (is_null($usuario)) {
            return $this->router->view('profile/', ['active' => 'register', 'danger' => true]);
        }

        $permissao = $this->usuarioRepository->addPermissions($data, $usuario->id);
           
        return $this->router->redirect('usuario/'. $id .'/permissao');
    }
}