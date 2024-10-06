<?php require_once __DIR__ . '/../layout/top.php'; ?>

<!-- Row start -->
<div class="row gx-3">
    <div class="col-8 col-xl-6">
        <!-- Breadcrumb start -->
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item">
                <i class="icon-house_siding lh-1"></i>
                <a href="\" class="text-decoration-none">Início</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/reserva/" class="text-decoration-none">Reservas</a>
            </li>         
            <li class="breadcrumb-item">Hospedadas</li>
        </ol>
       <!-- Breadcrumb end -->
    </div>
    <?php if (hasPermission('cadastrar clientes')) { ?>
        <div class="col-2 col-xl-6">
            <div class="float-end">
            <a href="\cliente\criar" class="btn btn-outline-primary" > + </a>
            </div>
        </div>
    <? } ?>
</div>
    <!-- Row end -->
<? if(isset($success)){?>
    <div class="alert border border-success alert-dismissible fade show text-success" role="alert">
      <b>Success!</b>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<? }?>
<? if(isset($danger)){?>
    <div class="alert border border-danger alert-dismissible fade show text-danger" role="alert">
       <b>Danger!</b>.
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<? }?>
    <!-- Row start -->
<div class="row gx-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row gx-3">
                    <?php 
                        foreach ($data['reservas'] as $key => $value) {
                    ?>                    
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div onclick="openModelDiaries('<?=$value->uuid?>')" class="d-flex align-items-start">
                                    <img src="https://placehold.co/600x400/EEE/31343C?font=lato&text=APT <?=$value->apartament?>" alt="Bootstrap Dashboards"
                                        class="img-fluid img-5x rounded-3" />
                                    <div class="ms-3">
                                        <p>
                                           Hospedes: <?=getCustomers(json_decode($value->customers))?> <br>
                                           Período de <?=brDate($value->dt_checkin)?> á  <?=brDate($value->dt_checkout)?> <br>
                                        </p>
                                        <span class="small badge rounded-pill bg-success m-0"> Diária R$ <?=number_format($value->amount, 2 ,',' ,'.')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="float-end">
        <?=$data['links']?>
    </div>
</div>

<div class="modal fade" id="modalDiaries" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="modalDiariesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDiariesLabel">
                   Cadastro de Diárias
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 d-flex align-items-end justify-content-between">
                    <h5>Diárias cadastradas</h5>
                    <button class="btn btn-danger" id="get-checked-values" disabled>Delete Selecionados</button>
                </div>
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle m-0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Valor</th>
                              <th>Data</th>                              
                              <th>Ações</th>
                              <th>
                                <input type="checkbox" id="checkAll"/>
                              </th>
                            </tr>
                          </thead>
                          <tbody id="list">
                            
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div class="container mt-2">
                <form action="" id="form_inserir" method="post">
                    <input type="hidden" name="id" id="id" disabled>
                    <div class="row gx-3">
                        <div class="col-6">
                            <input type="date" name="dt_daily" id="dt_daily" class="form-control" value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="col-6">
                            <input type="number" name="amount" id="amount" step="0.01" value="0" min="0" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fechar
                </button>
                <button type="button" disabled id="btn_inserir" class="btn btn-primary">
                    Inserir Diária
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/bottom.php'; ?>

<script>
    function list(data) {  
        if (!data) {
            return;
        }

        $('#list').empty();

        data.forEach((value) => {
            let date_daily = value.dt_daily.split('-');
            let tr = "<tr>";
            tr += `<td>${value.id}</td>`;            
            tr += `<td>${value.amount}</td>`;            
            tr += `<td>${date_daily[2]}/${date_daily[1]}/${date_daily[0]}</td>`;
            tr += `<td>
                <a class="btn btn-primary" onclick="editar('${value.uuid}')"><span class="icon-edit"></span></a>
                <a class="btn btn-danger" onclick="deleteItem('${value.uuid}')"><span class="icon-trash"></span></a>
            </td>`;
            tr += `<td> <input type="checkbox" onclick="checkBox()"; class="form-check m-0" value="${value.uuid}" /></td>`;
            tr += `</tr>`;

            $('#list').append(tr);
        })
    }

    function openModelDiaries(params) {
        showData(`/consumos/reserva/${params}/diarias`)
        .then((result) => {
            $('#checkAll').val(params);
            list(result)
            $('#modalDiaries').modal('show');
        });
    }   

    $('#amount').on('change', function() {
        if($('#amount').val() > 0) {
            $('#btn_inserir').removeAttr('disabled');
        }
    });

    $('#checkAll').click(function() {
        $('#list .form-check').prop('checked', this.checked);
        
        $('#get-checked-values').attr('disabled', !this.checked);
    });

    function getCheckedValues() {
      let selectedValues = [];

      $('#list .form-check:checked').each(function() {
        selectedValues.push($(this).val());
      });

      return selectedValues;
    }

    $('#get-checked-values').click(function() {
      const values = getCheckedValues();
      if (values.length === 0) {
        $('#get-checked-values').attr('disabled', true);
      }

      Swal.fire({
            title: "Tem certeza que deseja deletar estes dados?",
            text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, deletar!",
            cancelButtonText: 'Cancelar',
            }).then((result) => {
            if (result.isConfirmed) {
                let params = $('#checkAll').val();
                deleteData(`/consumos/reserva/${params}/diarias?data=${values}`)
                .then((result) => {
                    showMessage(result, 'success');
                    showData(`/consumos/reserva/${params}/diarias`)
                    .then((result) => {
                        $('#checkAll').val(params);
                        list(result)
                        $('#modalDiaries').modal('show');
                    });
                });
            }
        });
    });

    function checkBox() {
        $('#get-checked-values').attr('disabled', false);
    }

    $('#btn_inserir').click(function() {
        let params = $('#checkAll').val();
        let id = $('#id').val();
        if (id === '') {
            createData(`/consumos/reserva/${params}/diarias`, new FormData(document.getElementById("form_inserir"),))
            .then((result) => {
                showMessage(result, 'success');
                showData(`/consumos/reserva/${params}/diarias`)
                .then((result) => {
                    $('#checkAll').val(params);
                    list(result)
                    $('#modalDiaries').modal('show');
                });
            });
            return;
        }
        updateDataWithData(`/consumos/reserva/${params}/diarias/${id}`, new FormData(document.getElementById("form_inserir"),))
        .then((result) => {
            showMessage(result, 'success');
            showData(`/consumos/reserva/${params}/diarias`)
            .then((result) => {
                $('#checkAll').val(params);
                list(result)
                $('#modalDiaries').modal('show');
            });
        });
    });

    function editar(item) 
    {   
        let params = $('#checkAll').val();
        showData(`/consumos/reserva/${params}/diarias/${item}`)
        .then((result) => {
           $('#id').val(item);
           $('#dt_daily').val(result.dt_daily);
           $('#amount').val(result.amount);
           $('#btn_inserir').text('Atualizar Diária');       
        });
    }

    
    function deleteItem(item) {
      Swal.fire({
            title: "Tem certeza que deseja deletar estes dados?",
            text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, deletar!",
            cancelButtonText: 'Cancelar',
            }).then((result) => {
            if (result.isConfirmed) {
                let params = $('#checkAll').val();
                deleteData(`/consumos/reserva/${params}/diarias/${item}`)
                .then((result) => {
                    showMessage(result, 'success');
                    showData(`/consumos/reserva/${params}/diarias`)
                    .then((result) => {
                        $('#checkAll').val(params);
                        list(result)
                        $('#modalDiaries').modal('show');
                    });
                });
            }
        });
    }

</script>