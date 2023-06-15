<style>
    .hide{
        visibility: hidden;
    }

    .select2 {
        width:100%!important;
    }

    .fs{
        font-size: 21px;
    }
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Reservas</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">
            <!-- <div class="col-sm-12 mb-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<=$request?>" aria-describedby="basic-addon2">
            </div> -->
            <div class="col-sm-3 mb-2">
                <input type="date" name="" id="busca_entrada" class="form-control" value="<?=$entrada?>">
            </div>
            <div class="col-sm-3 mb-2">
                <input type="date" name="" id="busca_saida" class="form-control" value="<?=$saida?>">
            </div>
            <!-- <div class="col-sm-3">
                <select name="" id="" class="form-control">
                    <option value="">Selecione uma empresa</option>
                    <option value="">Confirmada</option>
                    <option value="">Hospedadas</option>
                </select>
            </div>     -->
            <div class="col-sm-3">
                <select name="" id="busca_status" class="form-control">
                    <option value="">Selecione o status</option>
                    <option <?=$status == 1 ? 'selected': '';?> value="1">Reservada</option>
                    <option <?=$status == 2 ? 'selected': '';?> value="2">Confirmada</option>
                    <option <?=$status == 3 ? 'selected': '';?> value="3">Hospedadas</option>
                    <option <?=$status == 4 ? 'selected': '';?> value="4">Finalizada</option>
                    <option <?=$status == 5 ? 'selected': '';?> value="5">Cancelada</option>
                </select>
            </div>  
            
            <div class="col-sm-11 mt-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<?=$texto?>" aria-describedby="basic-addon2">
            </div>

            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
    <div class="row">
        <div class="table-responsive ml-3">
            <table class="table table-sm mr-4" id="lista">     
                <?php
                    $reservas = $this->buscaReservas($request);
                    // var_dump($reservas);
                    if(!empty($reservas)) {
                ?>
                <thead>
                    <tr>
                        <th scope="col" class="d-none d-sm-table-cell">COD</th>
                        <th>Apartamento</th>
                        <th scope="col">Hospede</th>
                        <th scope="col">Data Entrada</th>
                        <th scope="col" class="d-none d-sm-table-cell">Data Saida</th>
                        <th scope="col" class="d-none d-sm-table-cell">Tipo</th>
                        <th scope="col" class="d-none d-sm-table-cell">Situação</th>
                        <th scope="col" class="d-none d-sm-table-cell">Valor</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($reservas as $key => $value) {
                            $data_entrada = explode(' ', $value['dataEntrada']);
                            $data_saida = explode(' ', $value['dataSaida']);
                            echo '
                                <tr>
                                    <td class="d-none d-sm-table-cell">' . $value['id'] . '</td>
                                    <td>' . $value['numero'] . '</td>
                                    <td>' . $value['nome'] . '</td>
                                    <td>' . implode('/', array_reverse(explode('-', $data_entrada[0]))) . '</td>
                                    <td class="d-none d-sm-table-cell">' . implode('/', array_reverse(explode('-', $data_saida[0])))  . '</td>
                                    <td class="d-none d-sm-table-cell">' . self::prepareTipoReserva($value['tipo']) . '</td>
                                    <td class="d-none d-sm-table-cell">' . self::prepareStatusReserva($value['status']) . '</td>
                                    <td class="d-none d-sm-table-cell">' . $value['valor'] . '</td>
                                    <td><button type="button" class="btn btn-outline-primary mb-2 view_data" id="'.$value['id'].'" >Editar</button> &nbsp;';                        
                                    if($value['status'] == "5"){
                                        echo '<button type="button" class="btn btn-outline-primary view_Ativo" id="'.$value['id'].'" >Ativar</button> &nbsp;';
                                    } 
                                    if($value['status'] == '1'){
                                        echo '<button type="button" class="btn btn-outline-danger view_Ativo" id="'.$value['id'].'" >Inativar</button> &nbsp;';
                                    }
                                    '</td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
                <?php } else{
                    'echo <td>não possui reservas cadastradas</td>';
                }
                ?>
            </table>
        </div>
        <ul class="pagination">
            <!-- Declare the item in the group -->
            <li class="page-item">
                <!-- Declare the link of the item -->
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/reservas/page=<?= $chave == 0 ? 0 : $chave + (-12)?>">Anterior</a>
            </li>
            <!-- Rest of the pagination items -->
          
            <li class="page-item">
                <a class="page-link" href="<?=ROTA_GERAL?>/Administrativo/reservas/page=<?=$chave + 12?>">proxima</a>
            </li>
        </ul>
    </div>    

<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <input type="hidden" disabled id="id" >
                        <input type="hidden" disabled id="opcao" value="" >
                        <div class="col-sm-5">
                            <label for="">Data Entrada</label>
                            <input type="date" name="entrada" id="entrada" class="form-control">
                        </div>

                        <div class="col-sm-5">
                            <label for="">Data Saida</label>
                            <input type="date" name="saida" id="saida" class="form-control">
                        </div>

                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-primary mt-2" type="button" id="buscar">
                                <i class="fas fa-search fa-sm"></i>
                            </button>   
                        </div>                    
                    </div>
                    <div class="form-row hide" id="div_apartamento">

                        <div class="col-sm-8">
                            <label for="">Hospede</label><br>
                            <select class="form-control js-example-basic-single" name="hospedes" id="hospedes">
                                <?php
                                $hospedes = $this->buscaHospedes();
                                    foreach ($hospedes as $hospede) { 
                                        ?>
                                            <option value="<?=$hospede['id']?>"><?=$hospede['nome'] . " CPF: " . $hospede['cpf']?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="">Apartamentos</label><br>
                            <select class="form-control js-example-basic-single" name="apartamento" id="apartamento">
                               
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Tipo</label><br>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value="1">Diária</option>
                                <option value="2">Pacote</option>
                                <option value="3">Promocao</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="">Status</label><br>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Reservada</option>
                                <option value="2">Confirmada</option>
                                <option value="5">Cancelada</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="">Valor</label>
                            <input type="number" class="form-control" onchange="valores()" name="valor" step="0.01" min="0.00" value="" id="valor">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Qtde Hospedes</label>
                            <input type="number" class="form-control" name="qtde_hosp" step="1" min="1" value="2" id="inp-qtdeHosp">
                        </div>

                        <div class="col-sm-12">
                            <label for="">observação</label><br>
                            <textarea name="observacao" class="form-control" id="observacao" cols="30" rows="5"> &nbsp;</textarea>
                        </div>
                    </div>   

                    <small>
                        <div align="center" class="mt-1" id="mensagem"></div>
                        <div align="right" class="mt-1 fs" id="valores"></div>
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->

</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    let url = "<?=ROTA_GERAL?>/";

      function valores(){
        var dias = moment($('#saida').val()).diff(moment($('#entrada').val()), 'days');
         var valor = $("#valor").val();
            $('#valores').removeClass('text-success');
            $('#valores').addClass('text-success');
            $('#valores').text("Valor Total da Estadia: R$" + valor * dias);
      }
      
      function envioRequisicaoPostViaAjax(controle_metodo, dados) {
          $.ajax({
              url: url+controle_metodo,
              method:'POST',
              data: dados,
              dataType: 'JSON',
              contentType: false,
	          cache: false,
	          processData:false,
              success: function(data){
                  if(data.status === 422){
                      $('#mensagem').removeClass('text-danger');
                      $('#mensagem').addClass('text-success');
                      $('#mensagem').text(data.message);
                  }
              }
          })
          .done(function(data) {
              if(data.status === 201){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                  }).then(()=>{
                    window.location.reload();    
                })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                  })
          });
      }

    function envioRequisicaoGetViaAjax(controle_metodo) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json     ',
            success: function(data){
                if(data.status === 201){
                    preparaModalEditarReserva(data.data);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalEditarReserva(data) 
    {
        $('#entrada').val(data[0].dataEntrada);
        $('#saida').val(data[0].dataSaida);
        $('#buscar').click();
        $('#hospedes').val(data[0].hospede_id);
        var newHosp= $('<option selected value="' + data[0].hospede_id + '">mesmo Hóspede</option>');
        $("#hospedes").append(newHosp);
        $('#tipo').val(data[0].tipo);
        $('#valor').val(data[0].valor);
        $('#status').val(data[0].status);
        $('#observacao').val(data[0].obs);
        $('#id').val(data[0].id);
        $("#apartamento").append('<option selected value="'+data[0].apartamento_id+'">mesmo Apartamento</option>');
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Reservas");
        $('#modal').modal('show');   

        return ;
    }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        var entrada = $('#busca_entrada').val();
        var saida  = $('#busca_saida').val();
        var status  = $('#busca_status').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/reservas/"+texto + '_@_' + status + '_@_' + entrada + '_@_' + saida;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Reservas");
        $('#modal').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modal').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());

            if(dataSaida > dataEntrada){
                return envioRequisicaoPostViaAjax('Reserva/salvarReservas', new FormData(document.getElementById("form")));
            }
        });

        $(document).on('click','.view_data',function(){
            var id = $(this).attr("id");
            $('#btnSubmit').removeClass('Salvar');
            $('#opcao').val('atualiza')
            envioRequisicaoGetViaAjax('Reserva/buscaReservaPorId/' + id);
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            // $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());

            if(dataSaida > dataEntrada){
                return envioRequisicaoPostViaAjax('Reserva/atualizarReserva/' + id, new FormData(document.getElementById("form")));   
            }
            return  Swal.fire({
                        icon: 'warning',
                        title: 'Datas invalidas',
                        text: "Possui verifique as datas!",
                    });
        });

        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            Swal.fire({
                title: 'Deseja cancelar esta reserva?',
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    envioRequisicaoGetViaAjax('Reserva/changeStatusReservas/'+ code);
                } else if (result.isDenied) {
                    Swal.fire('nenhuma mudança efetuada', '', 'info')
                }
            })
        });    

        $('.js-example-basic-single').select2();    
    
        $(document).on('click', '#buscar', function(){
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());
            
            var opcao = $('#opcao').val();            

            if(dataSaida >= dataEntrada){
                $.ajax({
                    url: '<?=ROTA_GERAL?>/Reserva/reservaBuscaPorData/',
                    method:'POST',
                    data: {
                        dataEntrada: dataEntrada._i,
                        dataSaida: dataSaida._i
                    },
                    dataType: 'JSON',
                    success: function(data){
                        if(opcao == '')
                            $('#apartamento option').detach();
                        if(data.status === 200){
                            Swal.fire({
                                icon: 'success',
                                title: 'Apartamentos Disponiveis',
                                text: "Possui " + data.data.length + " apartamento(s)!",
                            });

                            $('#div_apartamento').removeClass('hide');
                            data.data.map(element => {
                                var newOption = $('<option value="' + element.id + '">' + element.numero + '</option>');
                                $("#apartamento").append(newOption);
                            })
                            
                        }
                    }
                })
            }            
        });        
    });
</script>