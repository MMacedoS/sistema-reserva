
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

    @media screen and (max-width: 790px) {
        .modal-dialog {
            max-width: 100% !important;
            margin: 1.75rem auto;
        }
        .row-mobile {
            flex-wrap: nowrap;
            overflow-x: scroll;
        }

        .card {
            height: auto;
            width: 200px;
        }
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
    <form id="form_search" method="POST">
        <div class="row"> 
        <p>
            <button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="false" aria-controls="collapse-filters">Filtros de buscas</button>
        </p>
        <div class="input-group collapse" id="collapse-filters">

            <div class="col-sm-12 mb-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" name="busca" id="txt_busca" aria-label="Search" value="" aria-describedby="basic-addon2">
            </div>
                
            <div class="col-sm-3 mb-2">
                <input type="date" name="dt_entrada" id="busca_entrada" class="form-control" value="">
            </div>
            <div class="col-sm-3 mb-2">
                <input type="date" name="dt_saida" id="busca_saida" class="form-control" value="">
            </div>
            <div class="col-sm-3">
                <select name="status" id="busca_status" class="form-control">
                    <option value="">Selecione o status</option>
                    <option value="1">Reservada</option>
                    <option value="2">Confirmada</option>
                    <option value="3">Hospedadas</option>
                    <option value="4">Finalizada</option>
                    <option value="5">Cancelada</option>
                </select>
            </div>     
            <div class="col-sm-3 input-group-append float-right">
                <button class="btn btn-primary" type="button" onclick="pesquisa()">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>  
    </div>  
    </form>
<hr>
    <div class="row">
        <h4 class="display-6">Lista de reservas</h4>
        <div class="table-responsive ml-3">
            <div id="table"></div>
        </div>
    </div>    

<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Reserva</h5>
                <button class="btn btn-danger close" onclick="sair()"> <span aria-hidden="true">&times;</span></button>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row row-mobile">
                        <input type="hidden" disabled id="id" >
                        <input type="hidden" disabled id="opcao" value="" >
                        <div class="col-sm-5">
                            <label for="">Data Entrada</label>
                            <input type="date" name="entrada" id="entrada" class="form-control" min="<?=Date("Y-m-d")?>">
                        </div>

                        <div class="col-sm-5">
                            <label for="">Data Saida</label>
                            <input type="date" name="saida" id="saida" class="form-control" min="<?=self::addDdayInDate(Date("Y-m-d"), 1)?>">
                        </div>

                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-primary mt-2" type="button" id="buscaApt" >
                                <i class="fas fa-search fa-sm"></i>
                            </button>   
                        </div>                    
                    </div>
                    <div class="form-row hide" id="div_apartamento">

                        <div class="col-sm-8">
                            <label for="">Hospede</label><br>
                            <select id="hospedes" class="selectized" name="hospedes">
                               
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="">Apartamentos</label><br>
                                <select name="apartamento" id="apartamento">                               
                                   
                                </select>
                        </div>

                        <div class="row">

                            <div class="col-sm-6 row row-mobile ml-2 mb-2 mt-2">
                                <div class="col-sm-6">
                                    <label for="">Tipo</label><br>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="1">Diária</option>
                                        <option value="2">Pacote</option>
                                        <option value="3">Promocao</option>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Status</label><br>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">Reservada</option>
                                        <option value="2">Confirmada</option>
                                        <option value="5">Cancelada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 row row-mobile mt-2 mb-2 ml-2">
                                <div class="col-sm-6">
                                    <label for="">Valor</label>
                                    <input type="number" class="form-control" onchange="valores()" name="valor" step="0.01" min="0.00" value="" id="valor">
                                </div>

                                <div class="col-sm-6">
                                    <label for="">Qtde Hospedes</label>
                                    <input type="number" class="form-control" name="qtde_hosp" step="1" min="1" value="2" id="inp-qtdeHosp">
                                </div>
                            </div>

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
                    
                    <!-- <button  class="btn btn-secondary" onclick="sair()" >Fechar</button> -->
                    <button type="submit" name="salvar" id="btnSubmit" disabled class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->
</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>

<script>
    var apartamento = null;
    var hospede = null;
    function valores(){
        var dias = moment($('#saida').val()).diff(moment($('#entrada').val()), 'days');
         var valor = $("#valor").val();
            $('#valores').removeClass('text-success');
            $('#valores').addClass('text-success');
            $('#valores').text("Valor Total da Estadia: R$" + valor * dias);
      }

    function formatDate(value)
    {
        const date = value.split('-');
        return ''+date[2]+ '/' + date[1] + '/' + date[0];
    }

    $(document).ready(function() {
        $('#hospedes').selectize({});
        $('#apartamento').selectize({});
        showData("<?=ROTA_GERAL?>/Reserva/getAll")
        .then((response) => createTable(response))
        .then(() => hideLoader());  
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Reservas");
        $('#modal').modal('show');        
    });


    function sair(){
        redirecionarPagina("<?=ROTA_GERAL?>/Administrativo/reservas");
    }

    function pesquisa() {                
        // Executa a função com base no valor do input
        showDataWithData("<?=ROTA_GERAL?>/Reserva/buscaReservas/",  new FormData(document.getElementById("form_search")))
        .then((response) => createTable(response))
        .then(() => hideLoader());    
    }

    function destroyTable() {
        var table = document.getElementById('table');
        if (table) {
        table.remove(); // Remove a tabela do DOM
        }
    }

    function createTable(data) {
        // Remove a tabela existente, se houver
        var tableContainer = document.getElementById('table');
        var existingTable = tableContainer.querySelector('table');
        if (existingTable) {
            existingTable.remove();
        }
        var thArray = ['Cod', 'Hospede', 'Dt.Entrada','Dt.Saida', 'Apt', 'Situação']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');

        thArray.forEach(function(value) {
            var th = document.createElement('th');
            th.textContent = value;
            
            if (value === 'CPF' || value === 'Endereço' || value === 'Cod') {
                th.classList.add('d-none', 'd-sm-table-cell');
            }
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');

            data.forEach(function(item) {
                var tr = document.createElement('tr');
                if (item.dataEntrada) {
                        dateEntrada = formatDate(item.dataEntrada);
                        dateSaida = formatDate(item.dataSaida);
                    } 

                if (item.status == '1') {
                     status = 'Reservada';
                } if (item.status == '2') {
                    status = 'Confirmada';
                } 
                if (item.status == '3') {
                    status = 'Hospedada';
                } 
                if (item.status == '4') {
                    status = 'Finalizada';
                } 
                if (item.status == '5') {
                    status = 'Cancelada';
                } 

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        td.textContent = item[key];

                        if (value === 'Dt.Entrada') {
                            td.textContent = dateEntrada;
                        }

                        if (value === 'Dt.Saida') {
                            td.textContent = dateSaida;
                        }
                        
                        if (value === 'Situação') {
                            td.textContent = status;
                        }                       

                        if (value === 'CPF' || value === 'Endereço' || value === 'Cod') {
                            td.classList.add('d-none', 'd-sm-table-cell');
                        }
                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var editButton = document.createElement('button');
                editButton.innerHTML = '<i class="fa fa-edit"></i>';
                editButton.className = 'btn btn-edit';
                buttonsTd.appendChild(editButton);

                // Adicionando a ação para o botão "Editar"
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                editarRegistro(rowData);
                });

                tr.appendChild(buttonsTd);
                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

        return table;
    }

    function editarRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Reserva/buscaReservaPorId/" + rowData[0])
            .then((response) => preparaModalEditarReserva(response.data));
    }

    function activeRegistro(rowData)
    {
        Swal.fire({
            title: 'Deseja cancelar esta reserva?',
            showDenyButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: `Não`,
        }).then((result) => {
            if (result.isConfirmed) {
                showData("<?=ROTA_GERAL?>/Reserva/changeStatusReservas/" + rowData[0])
                    .then((response) => showSuccessMessage('Registro atualizado com sucesso!'));
            } else if (result.isDenied) {
                Swal.fire('nenhuma mudança efetuada', '', 'info')
            }
        })   
    }

    $(document).on('click','#buscaApt',function() {
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());           
            
            var opcao = $('#opcao').val();   

            if(dataSaida >= dataEntrada){
                valores();
                buscaApartamento(
                    dataEntrada._i,
                    dataSaida._i
                );

                $('.Salvar').attr('disabled', false);
            }            
        });

    function buscaApartamento(
        dataEntrada,
        dataSaida
    ){
        $.ajax({
                    url: '<?=ROTA_GERAL?>/Reserva/reservaBuscaPorData/',
                    method:'POST',
                    data: {
                        dataEntrada: dataEntrada,
                        dataSaida: dataSaida
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
                            populaHospede(hospede);
                            let apartamentos = data.data.map(element => {
                                return {
                                    id: element.id,
                                    title: element.numero
                                }
                            });

                            if(apartamento) {
                                apartamentos.push(
                                  {
                                    id: apartamento,
                                    title: 'mesmo Apartamento'
                                  }
                                );
                            }
                            
                            prepareSelect(apartamentos, '#apartamento', apartamento);
                        }
                    }
                })
    }

    function preparaModalEditarReserva(data) 
    {
        $('#entrada').val(data.dataEntrada);
        $('#saida').val(data.dataSaida);
        $('#tipo').val(data.tipo);
        $('#valor').val(data.valor);
        $('#status').val(data.status);
        $('#observacao').val(data.obs);        
        $('#inp-qtdeHosp').val(data.qtde_hosp);
        $('#id').val(data.id);
        $('#div_apartamento').removeClass('hide');
        apartamento =  data.apartamento_id;
        hospede = data.hospede_id;
        buscaApartamento(
            data.dataEntrada, 
            data.dataSaida           
        );
        
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Reservas");
        $('#modal').modal('show'); 
        $('.Salvar').attr('disabled', false);
        return ;
    }

    function populaHospede(hospede = null){
        showData("<?=ROTA_GERAL?>/Hospede/getAllSelect")
       .then((response) => {
            let hospedes = response.map(element => {
               return { id: element.id, title: element.nome}
            });
            console.log("Hospede" + hospede);
            prepareSelect(hospedes, '#hospedes', hospede);
       });

    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        var id = $('#id').val();
        var dataEntrada = moment($('#entrada').val());
        var dataSaida = moment($('#saida').val());
        
        if(dataSaida > dataEntrada){
            if(id == ''){
                return createData('<?=ROTA_GERAL?>/Reserva/salvarReservas', new FormData(document.getElementById("form"))).then( (response) => {
                    apartamento = null;
                    hospede = null;
                    if(response.status === 200) {
                        showSuccessMessage(response.message);
                        return;
                    }  
                    
                    if(response.status === 422) {
                        showErrorMessage(response.message);
                    }
                    
                    hideLoader();
                    return;
                });
            }
        
            return updateData('<?=ROTA_GERAL?>/Reserva/atualizarReserva/' + id, new FormData(document.getElementById("form"))).then( (response) => {
                apartamento = null;
                hospede = null;
            });
        }
    });
</script>

<?php if (isset($_GET['hospede']) && !empty($_GET['hospede'])) {?>
    <script>
        hospede = <?=$_GET['hospede'];?> ;
        $('#novo').click();       
    </script>
<?php } ?>


<?php if (isset($_GET['apartamento']) && !empty($_GET['apartamento'])) {?>
    <script>
        apartamento = <?=$_GET['apartamento'];?> ;
        $('#novo').click();       
    </script>
<?php } ?>