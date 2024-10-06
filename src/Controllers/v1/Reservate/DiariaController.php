<?php

namespace App\Controllers\v1\Reservate;

use App\Controllers\Controller;
use App\Repositories\Reservate\DiariaRepository;
use App\Repositories\Reservate\ReservaRepository;
use App\Request\Request;
use App\Utils\LoggerHelper;
use App\Utils\Paginator;
use App\Utils\Validator;

class DiariaController extends Controller
{
    protected $diariaRepository;
    protected $clienteRepository;    
    protected $apartamentoRepository;
    protected $reservaRepository;

    public function __construct()
    {   
        parent::__construct();
        $this->diariaRepository = new DiariaRepository();    
        $this->reservaRepository = new ReservaRepository();      
    }

    public function index(Request $request) {
        $reserva = $this->reservaRepository->allHosted();
        $perPage = 10;
        $currentPage = $request->getParam('page') ? (int)$request->getParam('page') : 1;
        $paginator = new Paginator($reserva, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        $data = [
            'reservas' => $paginatedBoards,
            'links' => $paginator->links()
        ];

        return $this->router->view('diaries/index', ['active' => 'consumos', 'data' => $data]);
    }

    public function indexJsonByReservaUuid(Request $request, $id) 
    {
        $reserva = $this->reservaRepository->findByUuid($id);

        if (!$reserva) {
            http_response_code(404); 
            echo json_encode(['error' => 'Reserva não encontrada.']);
            return;
        }

        $diarias = $this->diariaRepository->all(['reserve_id' => $reserva->id, 'status' => '1']);  
        
        echo json_encode($diarias);
        exit();
    }

    // public function create() {
    //     $clientes = $this->clienteRepository->all();
    //     return $this->router->view('reservate/create', ['active' => 'register', 'customers' => $clientes]);
    // }

    // public function store(Request $request) {
    //     $data = $request->getBodyParams();

    //     $validator = new Validator($data);
    //     $rules = [
    //         'dt_checkin' => 'required',
    //         'dt_checkout' => 'required',
    //         'apartament' => 'required',
    //         'status' => 'required',           
    //         'amount' => 'required',
    //         'customers' => 'required',
    //     ];

    //     if (!$validator->validate($rules)) {
    //         return $this->router->view(
    //             'reservate/create', 
    //             [
    //                 'active' => 'register', 
    //                 'errors' => $validator->getErrors()
    //             ]
    //         );
    //     } 

    //     $data['id_usuario'] = 1;
        
    //     $created = $this->reservaRepository->create($data);

    //     if(is_null($created)) {            
    //     return $this->router->view('reservate/create', ['active' => 'register', 'danger' => true]);
    //     }

    //     return $this->router->redirect('reserva/');
    // }

    public function storeByJson(Request $request, $reserva_id) 
    {
        $data = $request->getBodyParams();
        $validator = new Validator($data);
        $rules = [
            'dt_daily' => 'required',           
            'amount' => 'required',
        ];
    
        if (!$validator->validate($rules)) {
            http_response_code(404); 
            echo json_encode(['error' => 'dados invalidos.']);
            exit();
       } 
    
        $reserve = $this->reservaRepository->findByUuid($reserva_id);
        
        if (!$reserve) {
            http_response_code(404); 
            echo json_encode(['error' => 'Reserva não encontrada.']);
            return;
        }     

        $data['id_reserva'] = $reserve->id;
        $data['id_usuario'] = 1;
           
        $created = $this->diariaRepository->create($data);
    
        if(is_null($created)) {            
            http_response_code(404); 
            echo json_encode(['error' => 'Reserva não encontrada.']);
            return;
        }
    
        echo json_encode(['title' => "sucesso!" ,'message' => 'diaria criada']);
        exit();
    }

    public function showByJson(Request $request, $reserve ,$id) 
    {
        $reserve = $this->reservaRepository->findByUuid($reserve);
        
        if (!$reserve) {
            http_response_code(404); 
            echo json_encode(['error' => 'Reserva não encontrada.']);
            return;
        }     
        
        $diaria = $this->diariaRepository->findByUuid($id);
        
        if (!$diaria) {
            http_response_code(404); 
            echo json_encode(['error' => 'diaria não encontrada.']);
            return;
        }    
        
        echo json_encode($diaria);
        exit();        
    }

    public function updateByJson(Request $request, $reserva_id, $id) 
    {
        $data = $request->getBodyParams();
        $validator = new Validator($data);
        $rules = [
            'dt_daily' => 'required',           
            'amount' => 'required'
        ];
    
        if (!$validator->validate($rules)) {
            http_response_code(404); 
            echo json_encode(['error' => 'dados invalidos.']);
            return;
       } 

       $reserve = $this->reservaRepository->findByUuid($reserva_id);
        
       if (!$reserve) {
           http_response_code(404); 
           echo json_encode(['error' => 'Reserva não encontrada.']);
           return;
       }     
    
        $diaria = $this->diariaRepository->findByUuid($id);
        
        if (!$diaria) {
            http_response_code(404); 
            echo json_encode(['error' => 'diaria não encontrada.']);
            return;
        }     

        $data['id_usuario'] = $_SESSION['user']->id;
           
        $updated = $this->diariaRepository->update($data, $diaria->id);
    
        if(is_null($updated)) {            
            http_response_code(404); 
            echo json_encode(['error' => 'diaria não atualizada.']);
            return;
        }
    
        echo json_encode(['title' => "sucesso!" ,'message' => 'diaria atualizada']);
        exit();
    }

    // public function edit(Request $request, $id) {
    //     $reserva = $this->reservaRepository->findByUuid($id);
        
    //     if (is_null($reserva)) {
    //         return $this->router->view('reservate/', ['active' => 'register', 'danger' => true]);
    //     }

    //     $reserva = $this->reservaRepository->findByIdWithCustomers($reserva->id);        

    //     $clientes = $this->clienteRepository->all();

    //     return $this->router->view('reservate/edit', [
    //         'active' => 'register', 
    //         'data' => [
    //             'reserve' => $reserva, 
    //             'customers' => $clientes
    //         ]
    //     ]);
    // }

    // public function update(Request $request, $id) {
    //     $reserve = $this->reservaRepository->findByUuid($id);
    //     $data = $request->getBodyParams();

    //     $validator = new Validator($data);
    //     $rules = [
    //         'dt_checkin' => 'required',
    //         'dt_checkout' => 'required',
    //         'apartament' => 'required',
    //         'status' => 'required',            
    //         'amount' => 'required',
    //         'customers' => 'required',
    //     ];

    //     if (!$validator->validate($rules)) {
    //         return $this->router->redirect('reserva/');
    //     } 
        
    //     $data['id_usuario'] = 1;
        
    //     $updated = $this->reservaRepository->update($data, $reserve->id);

    //     if(is_null($updated)) {            
    //     return $this->router->view('reservate/edit', ['active' => 'register', 'danger' => true]);
    //     }

    //     return $this->router->redirect('reserva/');
    // }

    public function destroyAll(Request $request, $id) {
        $reserve = $this->reservaRepository->findByUuid($id);
        $data = $request->getQueryParams();
        
        if (!$reserve) {
            http_response_code(404); 
            echo json_encode(['error' => 'Reserva não encontrada.']);
            return;
        }        
        
        $params = explode(',', $data['data']);
        $deleted = $this->diariaRepository->deleteAll($params);

        echo json_encode($deleted);
        exit();
    }

    public function destroy(Request $request, $reserva_id, $id) {

        $reserve = $this->reservaRepository->findByUuid($reserva_id);
        if (!$reserve) {
            http_response_code(404); 
            echo json_encode(['error' => 'Reserva não encontrada.']);
            return;
        }        
        
        $diaria = $this->diariaRepository->findByUuid($id);
        
        if (!$diaria) {
            http_response_code(404); 
            echo json_encode(['error' => 'diaria não encontrada.']);
            return;
        }     

        $deleted = $this->diariaRepository->delete($diaria->id);

        if (!$deleted) {
            http_response_code(422); 
            echo json_encode(['title' => 'Erro ao deleletar', 'message' => 'diaria não apagada.']);
            return;
        }     

        echo json_encode($deleted);
        exit();
    }

    // public function findAvailableApartments(Request $request) 
    // {
    //     $data = $request->getBodyParams();
        
    //     $checkin = $data['dt_checkin'];
    //     $checkout = $data['dt_checkout'];
    //     $reservaId = isset($data['reserve']) ? $data['reserve'] : null;

    //     $errors = [];

    //     // Verifica se o check-in está presente e é uma data válida
    //     if (empty($checkin) || !strtotime($checkin)) {
    //         $errors['dt_checkin'] = 'A data de check-in é obrigatória e deve ser uma data válida.';
    //     }
    
    //     // Verifica se o check-out está presente e é uma data válida
    //     if (empty($checkout) || !strtotime($checkout)) {
    //         $errors['dt_checkout'] = 'A data de check-out é obrigatória e deve ser uma data válida.';
    //     }
    
    //     // Verifica se a data de check-out é posterior à data de check-in
    //     if (!empty($checkin) && !empty($checkout) && strtotime($checkout) < strtotime($checkin)) {
    //         $errors['dt_checkout'] = 'A data de check-out deve ser posterior à data de check-in.';
    //     }
    
    //     // Verifica se o reservaId é um número inteiro, caso esteja presente
    //     if ($reservaId !== '' && !filter_var($reservaId, FILTER_VALIDATE_INT)) {
    //         $errors['reserva_id'] = 'O ID da reserva deve ser um número inteiro válido.';
    //     }
    
    //     // Se houver erros de validação, retorna os erros
    //     if (!empty($errors)) {
    //         echo json_encode(['errors' => $errors]);
    //         return;
    //     }

    //     $apartamentosDisponiveis = $this->apartamentoRepository->findAvailableApartments($checkin, $checkout);

    //     if ($reservaId !== '') {
    //         $apartament =  $this->apartamentoRepository->findApartmentByIdReserve($reservaId);

    //         if (!is_null($apartament)) {
    //             $apartamentosDisponiveis[] = $apartament;
    //         }
    //     }
        
    //     echo json_encode($apartamentosDisponiveis);
    //     exit;
    // }

    // public function checkin(Request $request) 
    // {
    //     $reservas = $this->reservaRepository->allCheckin();
    //     $perPage = 10;
    //     $currentPage = $request->getParam('page') ? (int)$request->getParam('page') : 1;
    //     $paginator = new Paginator($reservas, $perPage, $currentPage);
    //     $paginatedBoards = $paginator->getPaginatedItems();

    //     $data = [
    //         'reservas' => $paginatedBoards,
    //         'links' => $paginator->links()
    //     ];

    //     return $this->router->view('reservate/checkin', ['active' => 'register', 'data' => $data]);
    // }

    // public function executeCkeckin(Request $request, string $id) 
    // {
    //     $reserve = $this->reservaRepository->findByUuid($id);
        
    //     if (is_null($reserve)) {
    //         echo json_encode(["status" => 422, "message" => "not found reserve!"]);
    //     }

    //     $reserve->status = 'Hospedada';

    //     $updated = $this->reservaRepository->updateToCheckin($reserve, $reserve->id);

    //     if (is_null($updated)) {
    //         echo json_encode(["status" => 422, "message" => "not Sucessfully updated"]);
    //     }

    //     echo json_encode(["status" => 200, "message" => "Sucessfully updated"]);
    //     exit;
    // }

    // public function checkout(Request $request) 
    // {

    // }

    // public function maps() {
    //     return $this->router->view('reservate/maps', ['active' => 'register']);
    // }

    // public function reserve_by_maps(Request $request) {
    //     $data = $request->getBodyParams();
    //     $dados = $this->reservaRepository->buscaMapaReservas($data['start'], $data['end']);        
    //     echo json_encode(
    //         $dados
    //     );
    //     exit;
    // }

    public function generateDaily(Request $request, $token) 
    {
        LoggerHelper::logInfo('Iniciando a ação no SomeController');
        if ($token !== SECRET_KEY_DAYLI) {
            echo json_encode(["status" => 401, "message" => "params invalid"]);
            die;
        }       
        
        $genetrate = $this->diariaRepository->generateDaily();
        LoggerHelper::logInfo($genetrate);
        echo json_encode(["status" => 200, "message" => json_encode($genetrate)]);
        exit();
    }
}