<?php

use App\Utils\LoggerHelper;

require 'vendor/autoload.php';

use App\Services\DiariaServices;

LoggerHelper::init();

try {
    $reservaService = new DiariaServices();
    
    // Chama o método para gerar as diárias
    $reservaService->generateDaily();

    LoggerHelper::logInfo('Diárias atualizadas com sucesso.');

} catch (\Exception $e) {
    LoggerHelper::logError('Erro ao atualizar diárias: ' . $e->getMessage());
}
