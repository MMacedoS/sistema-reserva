<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary mt-2" id="novo">Adicionar</button>
        </div>
    </div>
</div>


<h1 class="mr-4">Lista de bimestres</h1>
    
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
        $bimestres = $this->buscaBimestres($request);
        if(!empty($bimestres)){
    ?>
    <table class="table table-sm mr-4 mt-3" id="lista">
        <thead>
            <tr>
                <th scope="col">COD</th>
                <th scope="col">Bimestre</th>
                <th colspan="2">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            
        <?php 
            foreach ($bimestres as $key => $value) {
                echo '<tr>            
                    <th scope="row">'.$value['id'].'</th>
                    <td>'.$value['bimestre'].' Bimestre</td>            
                    <td><button type="button" class="btn btn-outline-primary view_semana" id="'.$value['id'].'" >Dias da Semana</button></td>';
            }
        ?>
            </tr>
        </tbody>
    </table>
    <?php }?>
</div>



<!-- editar -->
<div class="modal fade" id="modalBimestre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalEstudantesLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="nome">Bimestre</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="ex: I" required value="">
                        </div>                    
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                        <button type="button" name="" id="salvarBimestre" class="btn btn-primary">Salvar</button>
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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/bimestres">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: "Algo de errado aconteceu!",
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/bimestres">Atualizar?</a>'
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
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/bimestres">Atualizar?</a>'
                  }).then(()=>{
                      window.location.reload();    
                  })
              }
              if(typeof data !== 'object')
                  return Swal.fire({
                          icon: 'warning',
                          title: 'ops...',
                          text: "Algo de errado aconteceu!",
                          footer: '<a href="<?=ROTA_GERAL?>/Administrativo/bimestres">Atualizar?</a>'
                  })
          });
          return "";
      }

      function preparaModalEditarTurma(data) {
        $('#nome').val(data.nome);
        $('#id').val(data.id);

        $('#exampleModalLabel').text("Atualizar Turma");
        $('#modalBimestre').modal('show');     
      }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/bimestres/"+texto;
    });

    $('#novo').click(function(){
        console.log("cliclou");
        $('#exampleModalLabel').text("Cadastro de Bimestre");
        $('#modalBimestre').modal('show');        
    });

    $('#salvarBimestre').click(function(){
      event.preventDefault();
      $('#salvarBimestre').attr('disabled','disabled');
        
        var dados = {
            nome:$('#nome').val()
        };
        envioRequisicaoPostViaAjax('Administrativo/addBimestre/', dados);     
    });

</script>