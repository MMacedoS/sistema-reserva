<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Apartamentos</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
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

    

<!-- editar -->
<div class="modal fade" id="modalApartamentos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Apartamentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" disabled id="id" >
                            <label for="">Apartamentos</label>
                            <input type="number" step="0" min="0" class="form-control" id="apartamento" name="apartamento" placeholder="Numero do apartamento" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="descrição" required value="">
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="">Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="Standart">Standart</option>
                                <option value="Suite">Suite</option>
                                <option value="Triplo">Triplo</option>
                                <option value="Quadruplo">Quadruplo</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="">Status</label>
                            <select name="status" class="form-control" id="status">
                                <option value="1">Disponível</option>
                                <option value="2">Ocupado</option>
                                <option value="3">Sujo</option>
                                <option value="4">Inativo</option>
                            </select>
                        </div>
                    </div>     
                    <small>
                        <div align="center" class="mt-1" id="mensagem">
                            
                        </div>
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

<script>

    $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Apartamento/getAll")
        .then((response) => createTable(response));
    });

    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Apartamentos");
        $('#modalApartamentos').modal('show');        
    });

    function pesquisa() {
        // Obtém o valor do input
        var input = document.getElementById('txt_busca');
        var valor = input.value;
        
        // Executa a função com base no valor do input
        showData("<?=ROTA_GERAL?>/Apartamento/buscaApartamentos/"+valor)
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
        var thArray = ['Cod', 'Numero', 'Descrição','Tipo','Status']; 
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
                        td.textContent = item[key];
                        
                        if (item[key] === '1' && value === 'Status') {
                            td.textContent = 'Disponível';
                        } if (item[4] === '2' && value === 'Status') {
                            td.textContent = 'Ocupado';
                        } if (item[4] === '3' && value === 'Status') {
                            td.textContent = 'Sujo';
                        } if (item[4] === '4' && value === 'Status') {
                            td.textContent = 'Inativo';
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
                buttonsTd.appendChild(editButton);

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
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                editarRegistro(rowData);
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

    function editarRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Apartamento/buscaApartamentoPorId/" + rowData[0])
            .then((response) => preparaModalEditarApartamentos(response.data));
        console.log(rowData[0]);
    }

    function activeRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Apartamento/changeStatusApartamentos/" + rowData[0])
            .then((response) => showSuccessMessage('Registro atualizado com sucesso!'));
    }

    function preparaModalEditarApartamentos(data) {
        $('#apartamento').val(data[0].numero);
        $('#descricao').val(data[0].descricao);           
        $('#tipo').val(data[0].tipo);
        $('#status').val(data[0].status);
        $('#id').val(data[0].id);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar apartamentos");
        $('#modalApartamentos').modal('show');   
    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        var id = $('#id').val();
        if(id == '') return createData('<?=ROTA_GERAL?>/Apartamento/salvarApartamentos', new FormData(document.getElementById("form"))).then( (response) => {
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
    
        return updateData('<?=ROTA_GERAL?>/Apartamento/atualizarApartamentos/' + id, new FormData(document.getElementById("form")), id);
    });
</script>

