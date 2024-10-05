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
                                    <a class="dropdown-item current-page" href="/dashboard/analytics">
                                        <span>Analytics</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if (hasPermission('visualizar hospedagens')) { ?>
                        <li class="nav-item dropdown <=$active === 'hospedagens' ? 'active-link': ''?>">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-add_task"></i>Hospedagens
                            </a>
                            <ul class="dropdown-menu">
                            <?php if (hasPermission('visualizar apartamentos')) { ?>
                                <li>
                                    <a class="dropdown-item" href="\apartamento\">
                                        <span>Apartamentos</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (hasPermission('visualizar clientes')) { ?>
                                <li>
                                    <a class="dropdown-item" href="\cliente\">
                                        <span>Clientes</span>
                                    </a>
                                </li>

                            <?php } if (hasPermission('visualizar mapas')) { ?>
                                <li>
                                    <a class="dropdown-item" href="\maps\">
                                        <span>Mapa de Reservas</span>
                                    </a>
                                </li>
                            <?php } if (hasPermission('visualizar reservas')) { ?>
                                <li>
                                    <a class="dropdown-item" href="\reserva\">
                                        <span>Reservas</span>
                                    </a>
                                </li>
                            <?php } if (hasPermission('visualizar reservas')) { ?>
                                <li>
                                    <a class="dropdown-item" href="\checkin\reserva\">
                                        <span>Checkin</span>
                                    </a>
                                </li>
                            <?php } if (hasPermission('visualizar nota Cliente')) { ?>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.informations')}}">
                                        <span>Nota Cliente</span>
                                    </a>
                                </li>
                            <?php } ?>
                            </ul>
                        </li>

                        <?php } ?>
                        <?php if (hasPermission('visualizar cadastro')) { ?>
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-add_task"></i>Cadastro
                            </a>
                            <ul class="dropdown-menu">
                                
                            <?php if (hasPermission('criar usuários')) { ?>
                                <li>
                                    <a class="dropdown-item" href="/usuario/">
                                        <span>Usuário</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/permissao/">
                                        <span>Permissões</span>
                                    </a>
                                </li>
                            <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if (hasPermission('visualizar afiliados')) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href=""><i class="icon-supervised_user_circle"></i> Afiliados
                            </a>
                        </li>
                        <? } ?>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="/logout">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- App Navbar ends -->