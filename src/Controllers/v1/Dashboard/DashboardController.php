<?php

namespace App\Controllers\v1\Dashboard;

use App\Config\Router;
use App\Controllers\Controller;
use App\Request\Request;

class DashboardController
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
    }
    public function index(Request $request) {
        return $this->router->view('dashboard/index', ['active' => 'dashboard']);
    }
}