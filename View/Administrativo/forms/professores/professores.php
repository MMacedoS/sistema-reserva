<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary mt-2" id="novo">Adicionar</button>
        </div>
    </div>
</div>


<h1 class="mr-4">Lista de Professores</h1>
    
<div class="input-group">
    <input type="text" class="form-control bg-light border-0 small" placeholder="buscar por nome..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
    <div class="input-group-append">
        <button class="btn btn-primary" type="button" id="btn_busca">
            <i class="fas fa-search fa-sm"></i>
        </button>   
    </div>
</div>



<div class="table-responsive ml-3">
    <?php
        $professores = $this->buscaProfessor($request);
        if(!empty($professores)){
    ?>
    <table class="table table-sm mr-4 mt-3" id="lista">
        <thead>
            <tr>
                <th scope="col">MATRÍCULA</th>
                <th scope="col">NOME</th>
                <th scope="col">E-MAIL</th>
                <th colspan="2">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($professores as $key => $value) {
                echo '<tr>
                        <th scope="row">'.$value['matricula'].'</th>
                        <td>'.$value['nome'].'</td>
                        <td class="email">'.$value['email'].'</td>
                        <td><button type="button" class="btn btn-outline-primary view_data" id="'.$value['id'].'" >Editar</button></td>';
                         echo $value['status'] == 1 ?
                            '<td><button type="button" class="btn btn-outline-info view_Ativo" id="'.$value['id'].'" >Ativo</button></td>'
                          : 
                          '<td><button type="button" class="btn btn-outline-danger view_Ativo" id="'.$value['id'].'" >Inativo</button></td>'
                        .'                      
                    </tr>';
                }
            ?>
        </tbody>
    </table>

    <?php }?>
</div>





<!-- editar -->
<div class="modal fade" id="modalProfessor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Professor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <small>
                <div align="center" class="mt-1" id="mensagem">
                    
                </div>
            </small>
            <form action="" method="POST">
                <div class="modal-body" id="editarProfessor">
                    <div class="form-row">
                        <input type="hidden" value="" id="id">       
                        <div class="col-sm-6">
                        <?php
                                $ultimoCode = $this->buscaTodosProfessores();
                                $code = $ultimoCode[0]['matricula']+0001+$ultimoCode[0]['id'];
                            ?>
                            <label for="codigo">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="codigo" required  disabled value="<?=$code?>">
                        </div>
                        <div class="col-sm-6">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="nome" required value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="graduacao">Graduação</label>
                            <input type="text" class="form-control" id="graduacao" name="graduacao" placeholder="graduacao" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX " required value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@email.com" required value="">
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="button" name="Salvar" id="btnSubmit" class="btn btn-primary Salvar">Salvar</button>
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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/professores">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: "Algo de errado aconteceu!",
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/professores">Atualizar?</a>'
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
                    preparaModalEditarProfessores(data);
                  }
              }
          })
          .done(function(data) {
              if(data != false && typeof data !== 'object'){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/professores">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              if(typeof data !== 'object')
                  return Swal.fire({
                          icon: 'warning',
                          title: 'ops...',
                          text: "Algo de errado aconteceu!",
                          footer: '<a href="<?=ROTA_GERAL?>/Administrativo/professores">Atualizar?</a>'
                  })
          });
          return "";
      }

      function preparaModalEditarProfessores(data) {
        $('#codigo').val(data[0].matricula);
        $('#nome').val(data[0].nome);        
        $('#graduacao').val(data[0].graduacao);
        $('#email').val(data[0].email);
        $('#telefone').val(data[0].telefone);
        $('#id').val(data[0].id);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Professor");
        $('#modalProfessor').modal('show');    
      }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/professores/"+texto;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Professores");
        $('#modalProfessor').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modalProfessor').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            // $('#btnSubmit').removeClass('Salvar');
            event.preventDefault();
            $('#btnSubmit').attr('disabled','disabled');
            var dados = {
                codigo:$('#codigo').val(),
                nome:$('#nome').val(),
                cpf:$('#cpf').val(),
                nascimento:$('#nascimento').val(),
                graduacao:$('#graduacao').val(),
                salario:$('#salario').val(),
                email:$('#email').val(),
                telefone:$('#telefone').val()
            };
            envioRequisicaoPostViaAjax('Administrativo/InserirProfessor', dados);
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
            var dados={
                codigo:$('#codigo').val(),
                nome:$('#nome').val(),
                cpf:$('#cpf').val(),
                nascimento:$('#nascimento').val(),
                graduacao:$('#graduacao').val(),
                salario:$('#salario').val(),
                email:$('#email').val(),
                telefone:$('#telefone').val(),
                id:$('#id').val()
            };
            envioRequisicaoPostViaAjax('Administrativo/updateProfessor/'+ id, dados);
        
        });

        $(document).on('click','.view_data',function(){
            var id=$(this).attr("id");
            $('#btnSubmit').removeClass('Salvar');
            envioRequisicaoGetViaAjax('Administrativo/buscaProfessorPorId/'+ id);
        });

        $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            envioRequisicaoGetViaAjax('Administrativo/changeStatusProfessor/'+ code);
        });    
    });
</script>