<style>
    .group-checkbox{
        display: flex;
        flex-direction: row;
        padding-left:0;
        margin-bottom:0;
        flex-wrap: wrap;
    }
    .list-group-checkbox{
        position: relative;
        display:block;
        padding: .75rem 1.25rem;
        background-color: #fff;
    }
</style>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary mt-2" id="novo">Adicionar</button>
        </div>
    </div>
</div>


<h1 class="mr-4">Lista de Disciplinas</h1>
    
<div class="input-group">
    <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar por disciplina ou turma ou professor ou code..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
    <div class="input-group-append">
        <button class="btn btn-primary" type="button" id="btn_busca">
            <i class="fas fa-search fa-sm"></i>
        </button>   
    </div>
</div>


<div class="table-responsive ml-3">
    <?php
        $disciplinas = $this->buscaDisciplinas($request);
        if(!empty($disciplinas)){
    ?>
    <table class="table table-sm mr-4 mt-3" id="lista">
        <thead>
            <tr>
                <th scope="col">COD</th>
                <th scope="col">DISCIPLINA</th>
                <th scope="col">PROFESSOR</th>
                <th scope="col">TURMA</th>
                <th colspan="3">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            
        <?php 
            foreach ($disciplinas as $key => $value) {
                echo '<tr>            
                    <th scope="row">'.$value['id'].'</th>
                    <td>'.$value['disciplina'].'</td>
                    <td class="email">'.$value['professor'].'</td>
                    <td>'.$value['turma'].'</td>                
                    <td><button type="button" class="btn btn-outline-primary view_data" id="'.$value['id'].'" >Editar</button></td>
                    <td><button type="button" class="btn btn-outline-primary view_semana" id="'.$value['id'].'" >Dias da Semana</button></td>';
                    if($value['status'] == "1"){
                        echo '<td><button type="button" class="btn btn-outline-primary view_Ativo" id="'.$value['id'].'" >Ativo</button></td>';
                    }else{
                        echo '<td><button type="button" class="btn btn-outline-danger view_Ativo" id="'.$value['id'].'" >Inativo</button></td>';
                    }
            }
        ?>
            </tr>
        </tbody>
    </table>
    <?php }?>
</div>



<!-- editar -->
<div class="modal fade" id="modalDisciplina" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Disciplinas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <small>
                <div align="center" class="mt-1" id="mensagem">                    
                </div>
            </small>
            <form action="" id="form" method="POST">
                <div class="modal-body" id="editarDisciplina">
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" id="id" value="" name="id">
                            <label for="">Turma</label>
                            <select class="form-control" id="turma" name="turma" required>
                                <option value="">Selecione uma turmas</option>
                                <?php
                                    foreach ($this->buscaTurmaAtivas() as $key => $value) {
                                        echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label for="disciplinas">Nome da Disciplina</label>
                            <select class="form-control" id="disciplinas" name="disciplinas">  
                            </select>                            
                        </div>
                        <div class="col-sm-1 mt-1">
                            <button type="button" class="mt-4 btn btn-primary addDisciplinas">+</button>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-sm-2">
                            <label for="">Carga Horaria</label>
                            <select class="form-control" id="carga" name="carga">  
                            </select>                            
                        </div>
                        <div class="col-sm-1 mt-1">
                            <button type="button" class="mt-4 btn btn-primary addCarga">+</button>
                        </div>
                        <div class="col-sm-6">
                            <label for="professor">Professor</label>
                            <select class="form-control" id="professor" name="professor" required>
                            </select>
                        </div>
                    
                        <div class="col-sm-3">
                            <label for="">Status</label>
                            <select class="form-control" id="status" name="status">  
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>                            
                        </div>                        
                        <div class="col-sm-4">
                            <label for="">Semestre</label>
                            <select class="form-control" name="semestre" id="semestre">
                                <option value="">Selecione ...</option>
                                <option value="1">Semestre 1</option>
                                <option value="2">Semestre 2</option>
                                <option value="3">Semestre 3</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="Salvar" id="btnSubmit" class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- editar -->

<!-- editar -->
<div class="modal fade" id="modalAddDisciplina" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Disciplinas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <small>
                <div align="center" class="mt-1" id="mensagem">                    
                </div>
            </small>
            <form action="" method="POST">
                <div class="modal-body" id="">
                    <div class="form-row">
                        <input type="hidden" id="idCategoria" value="">
                        <div class="col-sm-12">
                            <label for="">Nome da Disciplinas</label>
                            <input type="text" class="form-control" id="nome_disciplina" name="nome_disciplina" placeholder="nome da disciplina" required value="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="Salvar" id="" class="btn btn-primary SalvarDisciplina">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- editar -->
<div class="modal fade" id="modalAddCarga" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Carga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <small>
                <div align="center" class="mt-1" id="mensagem">                    
                </div>
            </small>
            <form action="" method="POST">
                <div class="modal-body" id="">
                    <div class="form-row">
                        <div class="col-sm-12">
                            <label for="">Carga</label>
                            <input type="number" class="form-control" id="carga_txt" name="carga_txt" placeholder="insira a carga horaria ex: 30" required value="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="Salvar" id="" class="btn btn-primary SalvarCarga">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- editar -->
<div class="modal fade" id="modalAddDias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dias de aulas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <small>
                <div align="center" class="mt-1" id="mensagem">                    
                </div>
            </small>
                <form id="frmList">
                    <div class="modal-body">               
                        <div class="form-row">
                            <div class="col-sm-8">
                                <input type="hidden" name="id" id="id_frmList">
                                <label for="linguagem">Escolha um Dia da Semana</label>
                                <input name = "listaSemana" calss="form-control" list="semana">
                                <datalist id="semana">
                                    <option value="Segunda-feira">
                                    <option value="Terça-feira">
                                    <option value="Quarta-feira">
                                    <option value="Quinta-feira">
                                    <option value="Sexta-feira">
                                    <option value="Sabado">                            
                                </datalist>                      
                            </div>
                            <div class="col-sm-4">
                                <input type="time" class="form-control" name="hora" required>                        
                            </div>
                        </div>
                    
                        <p>Lista de Horarios Adicionados</p>
                        <table class="table bordered" id="resultado"></table>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="Salvar" id="" class="btn btn-primary salvarDias">Salvar</button>
                </div>
             </form>
        </div>
    </div>
</div>


<script>
    let url = "<?=ROTA_GERAL?>/";
      
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
                  if(data != false){
                      $('#mensagem').removeClass('text-danger');
                      $('#mensagem').addClass('text-success');
                      $('#mensagem').text(data);
                  }
              }
          })
          .done(function(data) {
              if(data != false){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/disciplinas">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: "Algo de errado aconteceu!",
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/disciplinas">Atualizar?</a>'
                  })
          });
      }

      function envioRequisicaoGetViaAjax(controle_metodo, tipo = null) {  
          $.ajax({
              url: url+controle_metodo,
              method:'GET',
              processData: false,
              dataType: 'json     ',
              success: function(data){
                  if(tipo === 'editar'){
                    preparaModalEditarDisciplinas(data);
                  }
                  if(tipo === 'semana') {
                    data.map(element => {
                        var row = '<tr>'+
                            '<td>'+ element.nome +'</td>'+
                            '<td>'+ element.hora +'</td>'+
                            '<td><button class="btn btn-danger" onclick="removeDia('+ element.id +')">remover</button></td>'+
                            '</tr>';
                            console.log(element);
                        $("#resultado").append(row);
                    });            
                  } 
              }
          })
          .done(function(data) {
              if(data != false && typeof data !== 'object'){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/disciplinas">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              if(typeof data !== 'object')
                  return Swal.fire({
                          icon: 'warning',
                          title: 'ops...',
                          text: "Algo de errado aconteceu!",
                          footer: '<a href="<?=ROTA_GERAL?>/Administrativo/disciplinas">Atualizar?</a>'
                  })
          });
      }

      async function preparaModalEditarDisciplinas(data) {
        $('#turma').val(data[0].turmas_id);
        $('#id').val(data[0].id);  
        $('#modalAddDisciplina').modal('hide');                         
        $("#disciplinas option").detach();
        $("#professor option").detach();
        $('#turma').change();
        await setTimeout(() => {
            $('#disciplinas').val(data[0].disciplinas_id);                
            $("#professor").val(data[0].professor_id);
            $("#carga").val(data[0].carga_horaria_id);   
            $("#status").val(data[0].status);    
            $("#semestre").val(data[0].semestre);     
        }, 1000);
        $('#modalDisciplina').modal('show');   
      }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/disciplinas/"+texto;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Disciplinas");
        $('#modalDisciplina').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modalDisciplina').modal('hide');
        });

        $(document).on('change','#turma',function(){
            $("#disciplinas option").detach();
            $("#professor option").detach();
            var id=$(this).val();
            $.ajax({
                url: '<?=ROTA_GERAL?>/Administrativo/buscaDisciplina/',
                method:'GET',
                dataType: 'json',
                processData: false,
                success: function(data) {
                    data.map(element => {
                        var newRow = $('<option value="'+element.id+'">'+element.nome+'</option>');
                        $("#disciplinas").append(newRow);
                    }); 
                }
            }).done(() => {
                $.ajax({
                        url: '<?=ROTA_GERAL?>/Administrativo/buscaProfessorParaSelect/',
                        method:'GET',
                        dataType: 'json',
                        processData: false,
                        success: function(data) {
                            data.map(element => {
                                var newRow = $('<option value="'+element.id+'">'+element.nome+'</option>');
                                $("#professor").append(newRow);
                            });
                        }
                    })
                .done(
                    () => {
                        $.ajax({
                            url: '<?=ROTA_GERAL?>/Administrativo/buscaCargaParaSelect/',
                            method:'GET',
                            dataType: 'json',
                            processData: false,
                            success: function(data) {
                                data.map(element => {
                                    var newRow = $('<option value="'+ element.id +'">'+ element.carga +'</option>');
                                    $("#carga").append(newRow);
                                });
                            }
                        });
                });
            });
            
        });

        $(document).on('click','.Salvar',function(){
            event.preventDefault();
            envioRequisicaoPostViaAjax('Administrativo/salvarDiciplinas', new FormData(document.getElementById("form")));
        });

        $(document).on('click', '.salvarDias', function(){
            event.preventDefault();
            envioRequisicaoPostViaAjax('Administrativo/diasSemanaAulasDisciplina', new FormData(document.getElementById("frmList")));
        })

        $(document).on('click','.SalvarDisciplina',function(){
            event.preventDefault();
            disciplina = $('#nome_disciplina').val();
            if(disciplina!==''){
                var dados={
                    categoria:categoria,
                    disciplina:disciplina
                };
                $.ajax({
                    url: '<?=ROTA_GERAL?>/Administrativo/criandoDiciplinas/',
                    method:'POST',
                    dataType: 'json',
                    data: dados,
                    success: function(data) {
                        $('#modalAddDisciplina').modal('hide');                         
                        $("#disciplinas option").detach();
                        $("#professor option").detach();
                        $('#turma').change();
                    }
                });
            }
        });

        $(document).on('click','.SalvarCarga',function(){
            event.preventDefault();
            let carga = $('#carga_txt').val();
            if(carga!==''){
                var dados = {
                    carga:carga
                };
                $.ajax({
                    url: '<?=ROTA_GERAL?>/Administrativo/criandoCarga/',
                    method:'POST',
                    dataType: 'json',
                    data: dados,
                    success: function(data) {
                        $('#modalAddCarga').modal('hide');                         
                        $("#disciplinas option").detach();
                        $("#professor option").detach();
                        $('#turma').change();
                    }
                });
            }
        });
        
        $(document).on('click','.view_data',function(){
            var id = $(this).attr("id");     
            $('#btnSubmit').removeClass('Salvar');
            $('#btnSubmit').addClass('Atualizar');       
             envioRequisicaoGetViaAjax('Administrativo/buscaDisciplinaPorId/' + id, 'editar');           
        });


        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            var dados={
                codigo:code,
                status:"Inativo"   
            };
            envioRequisicaoGetViaAjax('Administrativo/changeStatusDisciplina/' + code);
        });

        // atualizar

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            
           var id = $('#id').val();
            if(id!==''){
                envioRequisicaoPostViaAjax('Administrativo/updateDisciplina/' + id, new FormData(document.getElementById("form")));
            }
            
        });

        $(document).on('click', '.addDisciplinas', function () {
            categoria = $('#categoria').val();
            if(categoria !== '') {
                $('#idCategoria').val(categoria);
                $('#modalAddDisciplina').modal('show'); 
                return ;
           }
           Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'selecione uma categoria!',
                footer: '<a href="<?=ROTA_GERAL?>/Administrativo/estudantes">Atualizar?</a>'
            })           
        });

        $(document).on('click', '.addCarga', function (){
            $('#modalAddCarga').modal('show');
        });

        $(document).on('click', '.view_semana', function (){
            var code = $(this).attr("id");
            $('#id_frmList').val(code);
            envioRequisicaoGetViaAjax('Administrativo/diaSemanaTurma/' + code, 'semana');
            
            // 
            $('#modalAddDias').modal('show');
        })

        
    });
    
    function removeDia(id) {
            event.preventDefault();
            envioRequisicaoGetViaAjax('Administrativo/removeDiaSemanaTurma/' + id);
        }
</script>