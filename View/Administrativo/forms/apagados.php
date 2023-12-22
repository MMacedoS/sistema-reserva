<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Dados Apagados</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Aceitar todos</button>
            </div>
        </div>
    </div>

    <div class="row">        
        <p>
            <button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="false" aria-controls="collapse-filters">Filtros de buscas</button>
        </p>
        <div class="input-group collapse" id="collapse-filters">
            <input type="text" class="form-control bg-light border-0 small" onkeyup="pesquisa()" placeholder="busca por ..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" onclick="pesquisa()">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive ml-3">
            <div id="table"></div>
        </div>
    </div>

</div>
<!-- editar -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>  
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="id">ID:</label>
                        <input type="text" class="form-control" id="id" name="id" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label for="funcionario">Funcionário:</label>
                        <input type="text" class="form-control" id="nome_funcionario" name="funcionario" readonly>
                    </div>

                    <div class="col-sm-4">
                        <label for="created_at">Criado em:</label>
                        <input type="text" id="created_at" class="form-control" name="created_at" readonly>
                    </div>
                </div>

                <div class="row">
                    <label for="dados">Dados:</label>
                    <textarea id="dados" class="form-control" cols='100' row="10" name="dados" readonly></textarea>
                </div>
                
                <div class="row">
                    <label for="motivo">Motivo:</label>
                    <input type="text" id="motivo" class="form-control" name="motivo" readonly>
                </div>
                
                <div class="row">                    
                    <label for="table_reference">Referência da Tabela:</label>
                    <input type="text" id="table_reference" class="form-control" name="table_reference" readonly>
                </div>
            </div>  
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
            </div>  
        </div>
        
    </div>
</div>

<script>

    $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Apagados/findAllApagados")
        .then((response) => createTable(response));
        hideLoader();
    });

    
    $('#novo').click(function(){
            
    });

    function pesquisa() {
        // Obtém o valor do input
        var input = document.getElementById('txt_busca');
        var valor = input.value;
        
        // Executa a função com base no valor do input
        showData("<?=ROTA_GERAL?>/Apagados/findByParams/"+valor)
        .then((response) => createTable(response));;    
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
        var thArray = ['Cod', 'Funcionario', 'Motivo','Local']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');

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

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        if(value === 'Funcionario') {
                            td.textContent = item.nome_funcionario 
                        }
                        if(value === 'Cod') {
                            td.textContent = item.id 
                        }
                        if(value === 'Motivo') {
                            td.textContent = item.motivo 
                        }
                        if(value === 'Local') {
                            td.textContent = item.table_reference 
                        }
                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var viewButton = document.createElement('button');
                viewButton.innerHTML = '<i class="fa fa-eye"></i>';
                viewButton.className = 'btn btn-edit';
                buttonsTd.appendChild(viewButton);

                // var clearButton = document.createElement('button');
                // clearButton.innerHTML = '<i class="fa fa-trash"></i>';
                // buttonsTd.appendChild(clearButton);

                var activateButton = document.createElement('button');
                activateButton.innerHTML = '<i class="fa fa-check"></i>';
                activateButton.className = 'btn btn-activate';
                buttonsTd.appendChild(activateButton);

                // Verificar o valor e definir o ícone e classe apropriados
                if (item.status === '2') {           
                    activateButton.querySelector('i').className = 'fa fa-times-circle text-danger';
                    activateButton.title = 'Ocupado';
                } else {
                    activateButton.querySelector('i').className = 'fa fa-check-circle text-success';
                    activateButton.title = 'Ativo';
                }

                // Adicionando a ação para o botão "Editar"
                viewButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                    visualizarRegistro(rowData);
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

        return table;
    }

    function activeRegistro(rowData)
    {
        Swal.fire({
            title: 'Confirmar a deleção do registro?',
            text: "esta ação é para remover o registro da tela de apagados!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, aceito isto!'
            }).then((result) => {
            if (result.isConfirmed) {
                showData("<?=ROTA_GERAL?>/Apagados/changeStatusApagados/" + rowData[0])
                    .then((response) => showSuccessMessage('Registro atualizado com sucesso!'));
                }
            })
        
    }

    function visualizarRegistro (rowData) {
        showData("<?=ROTA_GERAL?>/Apagados/findById/" + rowData[0]).then((response) => createView(response));
    }

    $('#novo').click(function(){
        Swal.fire({
            title: 'Confirmar todas deleções em aberto?',
            text: "esta ação é para remover os registros da tela de apagados!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, aceito isto!'
            }).then((result) => {
            if (result.isConfirmed) {
                showData("<?=ROTA_GERAL?>/Apagados/changeAllStatusApagados")
                    .then((response) => showSuccessMessage('Registro atualizado com sucesso!'));
                }
            })     
    });
    
    function createView(response)
    {
        document.getElementById('id').value = response.id;
        document.getElementById('dados').value = response.dados;
        document.getElementById('motivo').value = response.motivo;
        document.getElementById('table_reference').value = response.table_reference;
        document.getElementById('created_at').value = formatDateWithHour(response.created_at);
        document.getElementById('nome_funcionario').value = response.nome_funcionario;
        $('#viewModal').modal('show');
    }
</script>

