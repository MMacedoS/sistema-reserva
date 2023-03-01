 <!-- Page Heading -->
          
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Painel de Controle <?= $_SESSION['code']?></h1>
          
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Earnings (Monthly) Card Example -->            
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <a href="<?=ROTA_GERAL?>/Coordenacao">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Turmas <?= date('Y');?>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($this->buscarTurmasPorProfessor($_SESSION['code']))?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>                                        
                                </div>
                            </div>                        
                        </a>    
                    </div>    
                </div>

                <!-- Earnings (Monthly) Card Example -->            
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <a href="<?=ROTA_GERAL?>/Coordenacao">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Estudantes <?= date('Y');?>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($this->buscaEstudantesPorProfessor($_SESSION['code']))?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>                                        
                                </div>
                            </div>                        
                        </a>    
                    </div>    
                </div>

            </div>

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gráficos</h1>
          
            </div>

            <!-- Content Row -->
            <div class="row">

            </div>