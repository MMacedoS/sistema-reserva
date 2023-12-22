
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
    .table-responsive {
        max-height: 400px !important;
    }
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Movimentações Entrada</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>                
                <button class="btn btn-danger" onclick="imprimir()" id="btn">Imprimir</button>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <p>
            <button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="false" aria-controls="collapse-filters">Filtros de buscas</button>
        </p>
        <div class="input-group collapse" id="collapse-filters">
            <div class="col-sm-4 mb-2">
                <label for="">Descrição</label>
                <input type="text" class="form-control bg-outline-danger border-0 small" placeholder="" id="txt_busca" aria-label="Search" value="" aria-describedby="basic-addon2">
            </div>
            <div class="col-sm-3 mb-2">
                <label for="">Data Inicial</label>
                <input type="date" name="" id="start_date" class="form-control" value="<?=Date('Y-m-d') ?>">
            </div>
            <div class="col-sm-3 mb-2">
                <label for="">Data Final</label>
                <input type="date" name="" id="end_date" class="form-control" value="<?=$this->addDdayInDate(Date('Y-m-d'),1)?>">
            </div>   
            <div class="col-sm-5 mb-2">
                <label for="">Selecione um Tipo</label>
                <select name="" id="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="1">Dinheiro</option>
                    <option value="2">Cartão de Crédito</option>
                    <option value="3">Cartão de Débito</option>
                    <option value="4">Deposito/PIX</option>
                </select>
            </div>  
            <?php 
                if($_SESSION['painel'] === 'Administrador') {
            ?>  
                <div class="col-sm-5 mb-2">
                    <label for="">Selecione o Funcionário</label>
                    <select name="" id="funcionarios" class="form-control">
                        <option value="todos">Todos</option>
                    </select>
                </div>
            <?php } ?>
            <div class="input-group-append float-right">
                <button class="btn btn-primary ml-3" type="button" onclick="pesquisa()" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
    </div>
    <hr>
    <div id="contents_inputs">        
        <div class="row pl-2">
            <div class="col-12">                
            <p>Informações Gerais</p>
            </div>
            <div class="col-4">
                <p>Funcionario: <?=$_SESSION['nome']?></p>
            </div>
            <div class="col-8 text-center" id="txt_periodo">
               
            </div>
            <div class="col-3">
                <p class="pl-2" id="txt_dinheiro"></p>
            </div>
            <div class="col-3">
                <p class="pl-2" id="txt_credito"></p>
            </div>
            <div class="col-3">
                <p class="pl-2" id="txt_debito"></p>
            </div>
            <div class="col-3">
                <p class="pl-2" id="txt_pix"></p>
            </div>
        </div>
        <div class="row pl-2">
            <div class="col-sm-3 ml-3">Movimentação de Entradas</div>
            <div class="col-lg-9 col-sm-12 text-info" style="text-align: end" id="total"></div>
        </div>
        <div class="row pl-2">
            <div class="table-responsive ml-3">
                <div id="table" class="w-100"></div>
            </div>
        </div>        
    </div>

<!-- editar -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelEntrada">Lança Entrada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" id="form" method="post">                   
                    <div class="form-row">
                        <div class="col-sm-8">
                            <input type="hidden" name="id" id="id">
                            <label for="">Descrição</label>
                            <input type="text" name="descricao" class="form-control" id="descricao">
                        </div>
                        <div class="col-sm-4">
                            <label for="">Valor</label>
                            <input type="number" step="0.01" min="0"  value="0.00" class="form-control" name="valor" id="valor">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Selecione uma forma</label>
                            <select name="pagamento" id="pagamento" class="form-control">
                                <option  value="1">Dinheiro</option>
                                <option  value="2">Cartão de Crédito</option>
                                <option  value="3">Cartão de Débito</option>
                                <option  value="4">Deposito/PIX</option>
                            </select>
                        </div>  
                        <div class="col-sm-12 text-right">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar mt-4"> &#10010; Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>

<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    
    $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Financeiro/findAllEntradas")
        .then((response) => createTable(response)).then(() => hideLoader());

        showData("<?=ROTA_GERAL?>/Funcionario/getAll")
        .then((response) => prepareSelector(response, "#funcionarios"));
    });
    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Entradas");
        $('#modalEntrada').modal('show');        
    });

    function pesquisa() {
        // Obtém o valor do input
        let data = new FormData();

        let painel = "<?=$_SESSION['painel']?>";

        if (painel === "Administrador") {
            data.append('funcionarios', $('#funcionarios').val());
        }

        data.append('search', $('#txt_busca').val());
        data.append('startDate', $('#start_date').val());
        data.append('endDate', $('#end_date').val());
        data.append('status', $('#status').val());  
        // Executa a função com base no valor do input

        showDataWithData(
            "<?=ROTA_GERAL?>/Financeiro/findEntradasByParams/", data
        )
        .then((response) => createTable(response));

        hideLoader();  
        $('#txt_periodo').text(' Período de '+ formatDate($('#start_date').val()) +' á ' + formatDate($('#end_date').val()));
    }

    function destroyTable() {
        var table = document.getElementById('table');
        if (table) {
        table.remove(); // Remove a tabela do DOM
        }
    }

    function createTable(data) {        
        // Remove a tabela existente, se houver
        let tableContainer = document.getElementById('table');
        let existingTable = tableContainer.querySelector('table');
        if (existingTable) {
            existingTable.remove();
        }
        let thArray = ['Cod', 'Reserva','Hospede', 'APT', 'Descrição', 'Tipo de Pagamento', "Data",'Tipo','Valor']; 
        let table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3 w-100';
        let thead = document.createElement('thead');
        let headerRow = document.createElement('tr');
        let totalValue = 0; 
        let creditoValue = 0; 
        let debitoValue = 0; 
        let pixValue = 0; 
        let dinheiroValue = 0; 
        thArray.forEach(function(value) {
            var th = document.createElement('th');
            th.textContent = value;
            
            if (value === 'Descrição' || value === 'Tipo' || value === 'Cod') {
                th.classList.add('d-none', 'd-sm-table-cell');
            }
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');

            data.forEach(function(item) {
                var tr = document.createElement('tr');
                if (item.created_at) {
                        created_at = formatDateWithHour(item.created_at);
                    } 

                totalValue += parseFloat(item.valor);

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');

                        if (value === 'Cod') {
                            td.textContent = item.id;
                        }

                        if (value === 'Reserva') {
                            td.textContent = item.reserva_code;
                        }

                        if (value === 'Descrição') {
                            td.textContent = item.descricao ?? '';
                        }

                        if (value === 'Hospede') {
                            td.textContent = item.Hospede ?? 'NÃO IDENTIFICADO';
                        }

                        if (value === 'APT') {
                            td.textContent = item.apt ?? '';
                        }
                                               
                        if (item.tipoPagamento === '1' && value == 'Tipo de Pagamento') {                            
                            td.textContent = 'Dinheiro';
                            dinheiroValue += parseFloat(item.valor);
                        } 
                        
                        if (item.tipoPagamento === '2' && value == 'Tipo de Pagamento') {
                            creditoValue += parseFloat(item.valor);
                            td.textContent = 'Cartão de Crédito';
                        } 
                        
                        if (item.tipoPagamento === '3' && value == 'Tipo de Pagamento') {
                            debitoValue += parseFloat(item.valor);
                            td.textContent = 'Cartão de Débito';
                        } 
                        
                        if (item.tipoPagamento === '4' && value == 'Tipo de Pagamento') {
                            pixValue += parseFloat(item.valor);
                            td.textContent = 'Deposito/PIX';
                        }
                        // venda
                        if (item.pagamento_id === null && value == 'Tipo') {
                            td.textContent = 'Venda';
                        }
                        if (item.pagamento_id !== null && value == 'Tipo') {
                            td.textContent = 'Hospedagem';
                        }

                        if (value === 'Data') {
                            td.textContent = created_at;
                        }

                        if (value === 'Valor') {
                            td.textContent = parseFloat(item.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }

                        if (value === 'Dt.Saida') {
                            td.textContent = dateSaida;
                        }

                        if (value === 'Descrição' || value === 'Tipo' || value === 'Cod') {
                            td.classList.add('d-none', 'd-sm-table-cell');
                        }
                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var editButton = document.createElement('button');
                editButton.innerHTML = '<i class="fa fa-edit"></i>';
                editButton.className = 'btn btn-edit';

                var delButton = document.createElement('button');
                delButton.innerHTML = '<i class="fa fa-trash"></i>';
                delButton.className = 'btn btn-edit';
                if(item.pagamento_id !== null) {
                    editButton.hidden = true;
                    delButton.hidden = true;
                } 
                buttonsTd.appendChild(editButton);
                buttonsTd.appendChild(delButton);

                // var clearButton = document.createElement('button');
                // clearButton.innerHTML = '<i class="fa fa-trash"></i>';
                // buttonsTd.appendChild(clearButton);

                var activateButton = document.createElement('button');
                activateButton.innerHTML = '<i class="fa fa-check"></i>';
                activateButton.className = 'btn btn-activate';
                buttonsTd.appendChild(activateButton);

                // Verificar o valor e definir o ícone e classe apropriados
                if (item.status === '0') {           
                    activateButton.querySelector('i').className = 'fa fa-times-circle text-danger';
                    activateButton.title = 'devolvido';
                } else {
                    activateButton.querySelector('i').className = 'fa fa-check-circle text-success';
                    activateButton.title = 'Recebido';
                }

                // Adicionando a ação para o botão "Editar"
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                editarRegistro(rowData);
                });

                delButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                deletarRegistro(rowData);
                });

                // Adicionando a ação para o botão "Editar"
                activateButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                activeRegistro(rowData);
                });

                tr.appendChild(buttonsTd);
                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

            $('#total').text("Total de Entradas " + totalValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $('#txt_dinheiro').text("Total Dinheiro " + dinheiroValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $('#txt_credito').text("Total C.Crédito " + creditoValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $('#txt_debito').text("Total C.Débito " + debitoValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $('#txt_pix').text("Total Pix " + pixValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            hideLoader();
        return table;
    }

    function createPaymentTypeChart(data) {
        var paymentTypes = ['Dinheiro', 'Cartão de Crédito', 'Cartão de Débito', 'Deposito/PIX'];
        var paymentTypeCounts = [0, 0, 0, 0];

        data.forEach(function(item) {
            var tipoPagamento = item[2]; // Supondo que o tipo de pagamento esteja na terceira posição (índice 2) do array

            switch (tipoPagamento) {
                case '1':
                    paymentTypeCounts[0]+= parseFloat(item.valor);
                    break;
                case '2':
                    paymentTypeCounts[1]+= parseFloat(item.valor);
                    break;
                case '3':
                    paymentTypeCounts[2]+= parseFloat(item.valor);
                    break;
                case '4':
                    paymentTypeCounts[3]+= parseFloat(item.valor);
                    break;
            }
        });

        var ctx = document.getElementById('paymentTypeChart').getContext('2d');
        var paymentTypeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: paymentTypes,
                datasets: [{
                    data: paymentTypeCounts,
                    backgroundColor: ['#FF5733', '#36A2EB', '#FFC300', '#4CAF50']
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: true,
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Gráfico de Pagamento por Tipo'
                }
            }
        });
    }

    function editarRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Financeiro/findEntradaById/" + rowData[0])
            .then((response) => prepareModalEditarEntrada(response.data));
        console.log(rowData[0]);
        hideLoader();
    }

    function deletarRegistro(rowData)
    {
        Swal.fire({
            title: 'Deseja remover esta entrada?',
            showDenyButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: `Não`,
        }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {                
                deleteData("<?=ROTA_GERAL?>/Financeiro/deleteEntradaById/" + rowData[0]);
            } else if (result.isDenied) {
                Swal.fire('nenhuma mudança efetuada', '', 'info')
            }
        })
    }
   
    function prepareModalEditarEntrada(data) {
        $('#descricao').val(data.descricao);           
        $('#valor').val(data.valor);
        $('#pagamento').val(data.tipoPagamento);
        $('#id').val(data.id);
        $('#btnSubmit').text('Atualizar');
        $('#exampleModalLabel').text("Atualizar Entrada");
        $('#modalEntrada').modal('show');   
    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        $('.Salvar').prop('disabled', true);
        var id = $('#id').val();
        if(id == '') return createData('<?=ROTA_GERAL?>/Financeiro/salvarEntradas', new FormData(document.getElementById("form"))).then( (response) => {
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
    
        return updateData('<?=ROTA_GERAL?>/Financeiro/atualizarEntradas/' + id, new FormData(document.getElementById("form")), id);
    });   
</script>

