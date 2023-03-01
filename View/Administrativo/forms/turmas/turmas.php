<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary mt-2" id="novo">Adicionar</button>
        </div>
    </div>
</div>


<h1 class="mr-4">Lista de Turmas</h1>
    
<div class="input-group">
    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
    <div class="input-group-append">
        <button class="btn btn-primary" type="button" id="btn_busca">
            <i class="fas fa-search fa-sm"></i>
        </button>   
    </div>
</div>
<div class="table-responsive ml-3">
<?php
        $turmas = $this->buscaTurma($request);
        if(!empty($turmas)){
    ?>
    <table class="table table-sm mr-4 mt-3" id="lista">
        <thead>
            <tr>
                <th scope="col">Cod</th>
                <th scope="col">Turma</th>
                <th scope="col">Turno</th>
                <th >Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($turmas as $key => $value) {
                echo '<tr>
                        <th scope="row">'.$value['id'].'</th>
                        <td>'.$value['nome'].'</td>
                        <td>'.$value['turno'].'</td>
                        <td><button type="button" class="btn btn-outline-primary view_data" id="'.$value['id'].'" >Editar</button> &nbsp;';
                        
                        if($value['status']==1){
                            echo '<button type="button" class="btn btn-outline-primary view_Ativo" id="'.$value['id'].'" >Ativo</button> &nbsp;';
                        }else{
                            echo '<button type="button" class="btn btn-outline-danger view_Ativo" id="'.$value['id'].'" >Inativo</button> &nbsp;';
                        }
                            echo '<button type="button" class="btn btn-outline-primary view_estudante" id="'.$value['id_cursos'].'" >Estudantes</button> &nbsp;';
                            echo '<button type="button" class="btn btn-outline-primary view_professores" id="'.$value['id_cursos'].'" >Disciplinas</button></td>
                    </tr>';
                }
            ?>
        </tbody>
    </table>
    <?php }?>
</div>


<!-- editar -->
<div class="modal fade" id="modalEstudantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalEstudantesLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="height: 500px">
                    <table class="table bordered" id="listaEstudante">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- editar -->
<div class="modal fade" id="modalTurmas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <small>
                <div align="center" class="mt-1" id="mensagem">
                    
                </div>
            </small>
            <form action="" method="POST">
                <div class="modal-body" id="editarTurma">    
                   <input type="hidden" id="id" value="">
                   <div class="form-row">
                        <div class="col-sm-6">
                            <label for="nome">Turma</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="ex: 4ª Ano A" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="Turno">Turno</label>
                            <select name="turno" id="turno" class="form-control">
                                <option value="matutino">Matutino</option>
                                <option value="vespertino">Vespertino</option>
                                <option value="noturno">Noturno</option>
                            </select>
                        </div>
                        <br>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="Ordem">Ordem da turma</label>
                            <select name="ordem" id="ordem" class="form-control" required>
                                <option value="">selecione</option>
                                <option value="10">1º Série Ensino Médio</option>
                                <option value="11">2º Série Ensino Médio</option>
                                <option value="12">3º Série Ensino Médio</option>                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="button" name="" id="salvarTurmas" class="btn btn-primary">Salvar</button>
                </div>
            </form>          
        </div>
    </div>
</div>
<!-- editar -->




<script>

let url = "<?=ROTA_GERAL?>/";
      
      function envioRequisicaoPostViaAjax(controle_metodo, dados) {
          $.ajax({
              url: url+controle_metodo,
              method:'POST',
              data: dados,
              dataType: 'JSON',
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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/turmas">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: "Algo de errado aconteceu!",
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/turmas">Atualizar?</a>'
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
                  if(typeof data === 'object'){
                    preparaModalEditarTurma(data);
                  }
              }
          })
          .done(function(data) {
              if(data != false && typeof data !== 'object'){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/turmas">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              if(typeof data !== 'object')
                  return Swal.fire({
                          icon: 'warning',
                          title: 'ops...',
                          text: "Algo de errado aconteceu!",
                          footer: '<a href="<?=ROTA_GERAL?>/Administrativo/turmas">Atualizar?</a>'
                  })
          });
          return "";
      }

      function preparaModalEditarTurma(data) {
        $('#nome').val(data.nome);
        $('#turno').val(data.turno);
        $('#ordem').val(data.ordem);
        $('#id').val(data.id);

        $('#exampleModalLabel').text("Atualizar Turma");
        $('#modalTurmas').modal('show');     
      }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/turmas/"+texto;
    });

    $('#novo').click(function(){
        console.log("cliclou");
        $('#exampleModalLabel').text("Cadastro de Turma");
        $('#modalTurmas').modal('show');        
    });

    $('#salvarTurmas').click(function(){
      event.preventDefault();
      $('#salvarTurmas').attr('disabled','disabled');
        var id = $('#id').val();
        if($('#ordem').val()!=""){
            var dados = {
            nome:$('#nome').val(),
            turno:$('#turno').val(),
            ordem:$('#ordem').val()
            };
           
            if(id !== "") {
                envioRequisicaoPostViaAjax('Administrativo/updTurmas/' + id, dados);
            } if(id === "") {
                envioRequisicaoPostViaAjax('Administrativo/addTurmas/', dados);
            }
        } else {
          $('#mensagem').addClass('text-danger');
            $('#mensagem').text("Selecione a ordem da turma");   
        }       
    });
    $(document).ready(function(){
        $(document).on('click','.view_data',function(){
            var id=$(this).attr("id");
            if(id !== ''){
                var dados = {
                    id
                };
                envioRequisicaoGetViaAjax('Administrativo/buscaTurmasPorId/' + id);
            }
        });

        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            var dados={
                codigo:code,
                status:"Inativo"   
            };
            envioRequisicaoGetViaAjax('Administrativo/changeStatusTurma/' + code);
        });

        $(document).on('click','.view_estudante',function(){
            $("#listaEstudante tr").detach();
            var turma = $(this).attr("id");
            $.ajax({
                url: '<?=ROTA_GERAL?>/Administrativo/listaEstudantesPorTurma/' + turma,
                method:'POST',
                processData: false,
                dataType: 'JSON',
                success: function(data){

                $('#exampleModalEstudantesLabel').text("Estudantes desta Turma");
                
                if(data.length > 0)
                {
                    data.map(element => {
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += '<td>'+element.nome+'</td>';
                        cols += '<td>';
                        cols += '<button class="btn btn-outline-primary" onclick="visualizar(this)" type="button">visualizar</button>';
                        cols += '</td>';
                        newRow.append(cols);
                        $("#listaEstudante").append(newRow);
                    });   

                    $('#modalEstudantes').modal('show');                
                    return true;
                }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Não possui estudantes!',
                        footer: '<a href="<?=ROTA_GERAL?>/Administrativo/turmas">Atualizar?</a>'
                    })
               
                }
            });

        });

        $(document).on('click','.view_professores',function(){
            $("#listaEstudante tr").detach();
            var turma = $(this).attr("id");            
            $.ajax({
                url: '<?=ROTA_GERAL?>/Administrativo/listaProfessorPorTurma/' + turma,
                method:'POST',
                processData: false,
                dataType: 'JSON',
                success: function(data){           
                $('#exampleModalEstudantesLabel').text("Professores desta Turma");
                
                    if(data.length > 0)
                    {
                        data.map(element => {
                            var newRow = $("<tr>");
                            var cols = "";
                            cols += '<td><b>'+element.disciplina+'</b>  | Professor:<b> '+element.professor+'</b></td>';
                            cols += '<td>';
                            cols += '<button class="btn btn-outline-primary" onclick="visualizar(this)" type="button">visualizar</button>';
                            cols += '</td>';
                            newRow.append(cols);
                            $("#listaEstudante").append(newRow);
                        });   

                        $('#modalEstudantes').modal('show');                
                        return true;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Não possui disciplinas ou esta Inativo!',
                            footer: '<a href="<?=ROTA_GERAL?>/Administrativo/turmas">Atualizar?</a>'
                        })
                }  
            })
        });
    });

</script>