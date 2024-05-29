
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

    .nota-container {
      max-width: 800px;
      margin: auto;
      padding: 20px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
    }
    .nota-header, .nota-footer {
      text-align: center;
      margin-bottom: 20px;
    }
    .nota-section {
      margin-bottom: 20px;
    }
    .nota-section h5 {
      margin-bottom: 10px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 5px;
    }
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Notas das Reservas</h4>
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
                
            <div class="col-lg-3 col-sm-6 mb-2">
                <input type="date" name="dt_entrada" id="busca_entrada" class="form-control" value="">
            </div>
            <div class="col-lg-3 col-sm-6 mb-2">
                <input type="date" name="dt_saida" id="busca_saida" class="form-control" value="">
            </div>
            <div class="col-lg-5 col-sm-9">
                <select name="status" id="busca_status" class="form-control">
                    <option value="">Selecione o status</option>
                    <option value="1">Reservada</option>
                    <option value="2">Confirmada</option>
                    <option value="3">Hospedadas</option>
                    <option value="4">Finalizada</option>
                    <option value="5">Cancelada</option>
                </select>
            </div>     
            <div class="col-1 input-group-append float-right">
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

    <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservaModalLabel">Nota para o Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="nota-container" id="nota">
                <!-- Header do Hotel -->
                    <div class="nota-header text-center">
                        <!-- <img src="logo.png" alt="Logo do Hotel" style="max-width: 100px;"> -->
                        <h1>Pousada Bela Vista</h1>
                        <p>Rua Josete Tuvo, Caldas do Jorro - Tucano-BA, Bahia, CEP 48793-000</p>
                    </div>

                    <!-- Dados do Hóspede -->
                    <hr>
                    <div class="nota-section">
                        <h5>Dados do Hóspede</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>Nome:</strong> <span id="hospedeNome"></span></div>
                            <div class="col-md-6"><strong>Hóspede ID:</strong> <span id="hospedeId"></span></div>
                        </div>
                    </div>

                    <!-- Dados da Reserva -->
                    <hr>
                    <div class="nota-section">
                        <h5>Dados da Reserva</h5>
                        <div class="row">
                        <div class="col-md-6"><strong>Reserva ID:</strong> <span id="apartamentoId"></span></div>
                        <div class="col-md-6"><strong>Número do Apartamento:</strong> <span id="apartamentoNumero"></span></div>
                        </div>
                        <div class="row">
                        <div class="col-md-6"><strong>Data da Reserva:</strong> <span id="dataReserva"></span></div>
                        <div class="col-md-6"><strong>Data de Entrada:</strong> <span id="dataEntrada"></span></div>
                        </div>
                        <div class="row">
                        <div class="col-md-6"><strong>Data de Saída:</strong> <span id="dataSaida"></span></div>
                        <div class="col-md-6"><strong>Funcionário:</strong> <span id="funcionario"></span></div>
                        </div>
                        <div class="row">
                        <div class="col-md-6"><strong>Gerar Diária:</strong> <span id="gerarDiaria"></span></div>
                        <div class="col-md-6"><strong>Status:</strong> <span id="status"></span></div>
                        </div>
                        <div class="row">
                        <div class="col-md-6"><strong>Tipo:</strong> <span id="tipo"></span></div>
                        <div class="col-md-6"><strong>Quantidade de Hóspedes:</strong> <span id="qtdeHosp"></span></div>
                        </div>
                    </div>

                    <!-- Consumo -->
                    <hr>
                    <div class="nota-section">
                        <h5>Consumo</h5>
                        <div id="consumoDetalhado" class="container">
                        <!-- Exemplo de item de consumo -->
                        <!-- <div class="col-md-12"><strong>Descrição:</strong> Bebidas no Bar - <strong>Total:</strong> 50,00</div> -->
                        </div>
                    </div>
                    <hr>
                    <div class="nota-section">
                        <h5>Diarias</h5>
                        <div id="diariaDetalhado" class="container">
                        <!-- Exemplo de item de consumo -->
                        <!-- <div class="col-md-12"><strong>Descrição:</strong> Bebidas no Bar - <strong>Total:</strong> 50,00</div> -->
                        </div>
                    </div>

                    <!-- Pagamentos -->
                    <hr>
                    <div class="nota-section">
                        <h5>Pagamentos</h5>
                        <div id="pagamentoDetalhado" class="container">
                        <!-- Exemplo de item de pagamento -->
                        <!-- <div class="col-md-6"><strong>Data:</strong> 2024-01-14</div>
                        <div class="col-md-6"><strong>Valor:</strong> 140,00</div> -->
                        </div>
                    </div>

                    <!-- Subtotal -->
                    <hr>
                    <div class="nota-section">
                        <h5>Subtotal</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>Total Consumos:</strong></div>
                            <div class="col-md-6"><span id="totalConsumos"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Total Diárias:</strong></div>
                            <div class="col-md-6"><span id="totalDiarias"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Total Pagamentos:</strong></div>
                            <div class="col-md-6"><span id="totalPag"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Subtotal:</strong></div>
                            <div class="col-md-6"><strong><span id="subtotal"></span></strong></div>
                        </div>
                    </div>

                    <!-- Mensagem de Agradecimento -->
                    <hr>
                    <div class="nota-footer text-center">
                        <p>Agradecemos a sua preferência e esperamos vê-lo novamente!</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button onclick="printModal()" class="btn btn-primary">Imprimir Nota</button>
            </div>
            </div>
        </div>
        </div>

    </div>
        

</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>

<script>

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
                editButton.innerHTML = '<i class="fas fa-sticky-note"></i>';
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
        showData("<?=ROTA_GERAL?>/Reserva/buscaAllReservaPorId/" + rowData[0])
            .then((response) => preparaModalEditarReserva(response.data));
    }

    $(document).on('click','#buscaApt',function() {
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());           
            
            var opcao = $('#opcao').val();   

            if(dataSaida >= dataEntrada){
                valores();
                $('.Salvar').attr('disabled', false);
            }            
        });

    function preparaModalEditarReserva(reserva) 
    {
        console.log(reserva);
        // Dados do Hóspede
        document.getElementById('hospedeNome').textContent = reserva.nome;
        document.getElementById('hospedeId').textContent = reserva.hospede_id;

        // Dados da Reserva
        document.getElementById('apartamentoId').textContent = reserva.id;
        document.getElementById('apartamentoNumero').textContent = reserva.numero;
        document.getElementById('dataReserva').textContent = formatDate(reserva.dataReserva);
        document.getElementById('dataEntrada').textContent = formatDate(reserva.dataEntrada);
        document.getElementById('dataSaida').textContent = formatDate(reserva.dataSaida);
        document.getElementById('funcionario').textContent = reserva.funcionario;
        document.getElementById('status').textContent = reserva.status;
        document.getElementById('tipo').textContent = reserva.tipo;
        document.getElementById('qtdeHosp').textContent = reserva.qtde_hosp;

        // Detalhes do Consumo
        const consumoDetalhado = document.getElementById('consumoDetalhado');
        consumoDetalhado.innerHTML = '';
        reserva.consumosDetalhados.forEach(consumo => {
            
            // Criação da div para cada diária
            const consumoDiv = document.createElement('div');
            consumoDiv.classList.add('row', 'mb-2', 'border'); // Adicionando classes para estilização
            consumoDiv.innerHTML = `
            <div class="col-sm-5"><strong>Descrição:</strong>${consumo.descricao}</div>            
            <div class="col-sm-2"><strong>Vl.Unit:</strong> ${formatCurrency(consumo.valorUnitario)}</div>
            <div class="col-sm-2"><strong>qtdo.:</strong> ${consumo.quantidade}</div>
            <div class="col-sm-3"><strong>Tl.:</strong> ${formatCurrency(consumo.total)}</div>
            `;
            consumoDetalhado.appendChild(consumoDiv);
        });

        // Detalhes dos Pagamentos
        const pagamentoDetalhado = document.getElementById('pagamentoDetalhado');
        pagamentoDetalhado.innerHTML = '';
        reserva.pagamentosDetalhados.forEach(pagamento => {
            const pagamentoDiv = document.createElement('div');
            pagamentoDiv.classList.add('row', 'mb-2', 'border'); // Adicionando classes para estilização

            pagamentoDiv.innerHTML = `
            <div class="col-sm-5 ml-2"><strong>Data:</strong> ${formatDate(pagamento.dataPagamento)}</div>            
            <div class="col-sm-3">${prepareTipo(pagamento.tipoPagamento)}</div>
            <div class="col-sm-3"><strong>Valor:</strong> ${formatCurrency(pagamento.valorPagamento)}</div>
            `;
            pagamentoDetalhado.appendChild(pagamentoDiv);
        });
        
        // Detalhes dos Diarias
        const diariaDetalhado = document.getElementById('diariaDetalhado');
        reserva.diariasDetalhados.forEach(diaria => {
            // Criação da div para cada diária
            const diariaDiv = document.createElement('div');
            diariaDiv.classList.add('row', 'mb-2', 'border'); // Adicionando classes para estilização

            // Configurando o conteúdo HTML da div
            diariaDiv.innerHTML = `
                <div class="col-sm-6"><strong>Diária:</strong> ${formatDate(diaria.data)}</div>
                <div class="col-sm-6"><strong>Valor:</strong> ${formatCurrency(diaria.valor)}</div>
            `;
            
            // Adicionando a div ao elemento pai
            diariaDetalhado.appendChild(diariaDiv);
        });

        // Subtotal
        document.getElementById('totalConsumos').textContent = `${formatCurrency(reserva.consumos)}`;
        document.getElementById('totalDiarias').textContent = `${formatCurrency(reserva.diarias)}`;
        document.getElementById('totalPag').textContent = `${formatCurrency(reserva.pag)}`;
        document.getElementById('subtotal').textContent = `${formatCurrency(reserva.subtotal)}`;

  // Mostrar Modal
       $('#reservaModal').modal('show');
        return ;
    }

    function printModal() {
            var modalContent = document.querySelector('#nota').innerHTML;
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Nota para o Cliente</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                    <style>
                        .nota-container {
                            max-width: 800px;
                            margin: auto;
                            padding: 20px;
                            border: 1px solid #ddd;
                            background-color: #f9f9f9;
                        }
                        .nota-header, .nota-footer {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .nota-section {
                            margin-bottom: 20px;
                        }
                        .nota-section h5 {
                            margin-bottom: 10px;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }
                        /* Reset básico */
                            body, div, h1, h5, p, span {
                                margin: 0;
                                padding: 0;
                                box-sizing: border-box;
                            }

                            /* Estilos de layout */
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                padding: 20px;
                            }

                            /* Estilos de tipografia */
                            h1, h5 {
                                margin-bottom: 15px;
                            }

                            h1 {
                                font-size: 24px;
                                font-weight: bold;
                            }

                            h5 {
                                font-size: 18px;
                                font-weight: bold;
                            }

                            p, span {
                                font-size: 14px;
                            }

                            /* Estilos de seção */
                            .nota-section {
                                margin-bottom: 20px;
                            }

                            .nota-header, .nota-footer {
                                margin-bottom: 20px;
                                text-align: center;
                            }

                            .nota-header img {
                                max-width: 100px;
                                margin-bottom: 10px;
                            }

                            .nota-container {
                                border: 1px solid #ddd;
                                padding: 20px;
                                border-radius: 5px;
                            }

                            /* Estilos de coluna */
                            .row {
                                display: flex;
                                flex-wrap: wrap;
                                margin-right: -10px;
                                margin-left: -10px;
                            }

                            .col-md-6, .col-sm-6, .col-md-12 {
                                padding-right: 10px;
                                padding-left: 10px;
                            }

                            .col-md-6 {
                                flex: 0 0 50%;
                                max-width: 50%;
                            }

                            .col-md-12 {
                                flex: 0 0 100%;
                                max-width: 100%;
                            }

                            .ml-2 {
                                margin-left: 0.5rem;
                            }

                            /* Estilos do botão */
                            button {
                                display: inline-block;
                                font-weight: 400;
                                color: #212529;
                                text-align: center;
                                vertical-align: middle;
                                user-select: none;
                                background-color: transparent;
                                border: 1px solid transparent;
                                padding: 0.375rem 0.75rem;
                                font-size: 1rem;
                                line-height: 1.5;
                                border-radius: 0.25rem;
                                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                            }

                            button.btn-secondary {
                                color: #fff;
                                background-color: #6c757d;
                                border-color: #6c757d;
                            }

                            button.btn-secondary:hover {
                                color: #fff;
                                background-color: #5a6268;
                                border-color: #545b62;
                            }

                            button.close {
                                padding: 0;
                                background-color: transparent;
                                border: 0;
                                appearance: none;
                            }

                            button.close span {
                                font-size: 1.5rem;
                                line-height: 1;
                                color: #000;
                            }

                            /* Estilos de impressão */
                            @media print {
                                body {
                                    padding: 0;
                                }

                                .nota-container {
                                    border: none;
                                    padding: 0;
                                }

                                .nota-header img {
                                    max-width: 100px;
                                }

                                .nota-section {
                                    margin-bottom: 10px;
                                }

                                .modal-footer, .close {
                                    display: none;
                                }
                            }

                    </style>
                </head>
                <body>
                    <div class="container-fluid">
                        ${modalContent}
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            // printWindow.print();
            // printWindow.close();
        }

</script>