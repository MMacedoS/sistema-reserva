        <!-- App navbar starts -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <div class="offcanvas offcanvas-end" id="MobileMenu">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title semibold">Navegação</h5>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="offcanvas">
                            <i class="icon-clear"></i>
                        </button>
                    </div>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown <?=$active === 'dashboard' ? 'active-link': ''?>">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-stacked_line_chart"></i> Dashboards
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item current-page" href="{{route('dashboard')}}">
                                        <span>Analytics</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown <?=$active === 'hospedagens' ? 'active-link': ''?>">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-add_task"></i>Hospedagens
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="\apartamento\">
                                        <span>Apartamentos</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.board')}}">
                                        <span>Consumos</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.informations')}}">
                                        <span>Mapas</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.partner')}}">
                                        <span>Reservas</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.informations')}}">
                                        <span>Nota Cliente</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-add_task"></i>Cadastro
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{route('users')}}">
                                        <span>Usuário</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=""><i class="icon-supervised_user_circle"></i> Afiliados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{route('logout')}}">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- App Navbar ends -->