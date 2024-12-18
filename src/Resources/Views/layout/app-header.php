        <!-- App header starts -->
        <div class="app-header d-flex align-items-center">

            <!-- Container starts -->
            <div class="container">
              <!-- Row starts -->
              <div class="row gx-3">
                <div class="col-md-3 col-2">

                  <!-- App brand starts -->
                  <div class="app-brand">
                    <a href="/dashboard/" class="text-light d-md-block fs-1 fw-bold">
                        <img src="<?=URL_PREFIX_APP?>/Public/assets/images/logo-ligth-rm-bg.png" class="img-fluid dash-logo" alt="Admin Dashboard" />
                    </a>
                  </div>
                  <!-- App brand ends -->
                </div>
                <div class="col-md-9 col-10">

                  <!-- App header actions start -->
                  <div class="header-actions col">

                    <!-- Search container start -->
                    <!-- {{-- <div class="search-container d-none d-lg-block">
                      <input type="text" id="search" class="form-control" placeholder="Search" />
                      <i class="icon-search"></i>
                    </div> --}} -->
                    <!-- Search container end -->

                    <div class="d-sm-flex align-items-center gap-2">
                      <? if (isset($_SESSION['balance']->id)) { ?>
                        <div class="alert border border-success fade show text-success mt-2">
                          <b>Caixa:</b>  <?=brCurrency($_SESSION['balance']->current_balance); ?>
                        </div>
                      <? } ?>

                      <? if (!isset($_SESSION['balance']->id)) { ?>
                        <div class="alert border border-success fade show text-success">
                          <button type="button" class="btn btn-info text-light" onclick="openModelBalance()">Abrir Caixa</button>
                        </div>
                      <? } ?>
                      <!-- {{-- <div class="dropdown">
                        <a class="dropdown-toggle header-action-icon" href="#!" role="button" data-bs-toggle="dropdown"
                          aria-expanded="false">
                          <i class="icon-warning fs-4 lh-1 text-white"></i>
                          <span class="count">7</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md">
                          <h5 class="fw-semibold px-3 py-2 text-primary">
                            Notifications
                          </h5>
                          <div class="dropdown-item">
                            <div class="d-flex py-2">
                              <div class="icons-box md bg-success rounded-circle me-3">
                                <i class="icon-shopping-bag text-white fs-4"></i>
                              </div>
                              <div class="m-0">
                                <h6 class="mb-1 fw-semibold">Rosalie Deleon</h6>
                                <p class="mb-1 text-secondary">
                                  You have new order.
                                </p>
                                <p class="small m-0 text-secondary">
                                  30 mins ago
                                </p>
                              </div>
                            </div>
                          </div>
                          <div class="d-grid mx-3 my-1">
                            <a href="javascript:void(0)" class="btn btn-outline-primary">View all</a>
                          </div>
                        </div>
                      </div> --}} -->
                    </div>
                    <div class="dropdown ms-3">
                      <a id="userSettings" class="dropdown-toggle d-flex py-2 align-items-center text-decoration-none"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         <img src="<?=URL_PREFIX_APP?>/Public/assets/images/user2.png" class="rounded-2 img-3x" alt="Bootstrap Gallery" />
                        <div class="ms-2 text-truncate d-lg-block d-none text-white">
                          <span class="d-flex opacity-50 small">
                            <?=$_SESSION['user']->name?>
                          </span>
                          <span>
                          <?=$_SESSION['user']->email?>
                          </span>
                        </div>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <div class="header-action-links">
                          <a class="dropdown-item" href="/perfil/<?=$_SESSION['user']->uuid?>"><i
                              class="icon-user border border-primary text-primary"></i>Perfil</a>
                          <!-- <a class="dropdown-item" href="settings.html"><i
                              class="icon-settings border border-danger text-danger"></i>Settings</a> -->
                        </div> 
                        <div class="mx-3 mt-2 d-grid">
                          <a href="/logout" class="btn btn-outline-danger">Logout</a>
                        </div>
                      </div>
                    </div>

                    <!-- Toggle Menu starts -->
                    <button class="btn btn-warning btn-sm ms-3 d-lg-none d-md-block" type="button"
                      data-bs-toggle="offcanvas" data-bs-target="#MobileMenu">
                      <i class="icon-menu"></i>
                    </button>
                    <!-- Toggle Menu ends -->

                  </div>
                  <!-- App header actions end -->

                </div>
              </div>
              <!-- Row ends -->

            </div>
            <!-- Container ends -->

          </div>
          <!-- App header ends -->
