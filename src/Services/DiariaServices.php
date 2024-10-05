<?php

namespace App\Services;

use App\Repositories\Reservate\DiariaRepository;
use App\Utils\LoggerHelper;

class DiariaServices
{
    protected $diariaRepository;

    public function __construct()
    {       
        $this->diariaRepository = new DiariaRepository(); 
    }

    public function generateDaily() 
    {
        LoggerHelper::logInfo('Iniciando a ação no SomeController');
        $this->diariaRepository->generateDaily();
    }
}