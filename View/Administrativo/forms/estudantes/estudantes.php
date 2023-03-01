<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-primary mt-2" id="novo">Adicionar</button>
        </div>
    </div>
</div>


<h1 class="mr-4">Lista de Estudantes</h1>
    
<div class="input-group">
    <input type="text" class="form-control bg-light border-0 small" placeholder="busca por ..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
    <div class="input-group-append">
        <button class="btn btn-primary" type="button" id="btn_busca">
            <i class="fas fa-search fa-sm"></i>
        </button>   
    </div>
</div>


<div class="table-responsive ml-3">
    <table class="table table-sm mr-4 mt-3" id="lista">
    <?php
        $estudantes = $this->buscaEstudantes($request);
        if(!empty($estudantes)){
    ?>
        <thead>
            <tr>
                <th scope="col">Cod</th>
                <th scope="col">Nome</th>
                <th scope="col">E-mail</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($estudantes as $key => $value) {
                    
                echo '<tr>
                <th scope="row">'.$value['matricula'].'</th>
                <td>'.substr($value['nome'],0,30).'...</td>
                <td class="email">'.substr($value['email'],0,30).'...</td>
                <td><button type="button" class="btn btn-outline-primary view_data" id="'.$value['id'].'" >Editar</button> &nbsp;';                        
                        if($value['status']=="1"){
                            echo '<button type="button" class="btn btn-outline-primary view_Ativo" id="'.$value['id'].'" >Ativo</button> &nbsp;';
                        }else{
                            echo '<button type="button" class="btn btn-outline-danger view_Ativo" id="'.$value['id'].'" >Inativo</button> &nbsp;';
                        }
                            echo '<button type="button" class="btn btn-outline-primary view_vinculo" id="'.$value['id'].'" >Turma</button> &nbsp;</td>
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
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Estudantes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" method="POST">
                <div class="modal-body" id="Professor">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <?php
                                $ultimoCode = $this->buscaTodosEstudantes();
                                $code = $ultimoCode[0]['matricula']+741+$ultimoCode[0]['id_estudantes'];
                            ?>
                            <input type="hidden" id="id" value="">
                            <label for="">Matrícula</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="codigo" required  disabled value="<?=$code?>">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Nome do Estudante</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do estudante" required value="">
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" placeholder="telefone" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" placeholder="endereço" required value="">
                        </div>
                    </div>     
                    <div class="form-row">
                        <div class="col-sm-12">
                            <label for="">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email" required value="">
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


<!-- editar -->
<div class="modal fade" id="modalVinculoEstudantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Vinculo de Estudantes Com Turma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>           
            <form action="" method="POST">
                <div class="modal-body" id="Professor">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" id="id_vinculo" value="">
                            <label for="">Matrícula</label>
                            <input type="text" class="form-control" id="codigo_vinculo" name="codigo" placeholder="codigo" required  disabled value="'.@$new_code.'">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Nome do Estudante</label>
                            <input type="text" class="form-control" id="nome_vinculo" name="nome" placeholder="Nome do estudante" required value="'.@$nome.'">
                        </div>
                        <div class="col-sm-12">
                            <label for="turma">Turma</label>
                            <select class="form-control" name="turma_vinculo" id="turma">
                                <option value="">Não possui Vinculo este ano</option>
                                <?php
                                    foreach($this->buscaTurmaAtivas() as $key=>$value){  
                                        echo '<option value="'.$value['id'].'">'.$value['nome'].'</option>';
                                    }?>
                            </select>
                        </div>
                        <small>
                            <div align="center" class="mt-1" id="mensagem">
                                
                            </div>
                        </small>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Vincular">Salvar</button>
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
                        footer: '<a href="<?=ROTA_GERAL?>/Administrativo/estudantes">Atualizar?</a>'
                    }).then(()=>{
                      window.location.reload();    
                  })
                }
                return Swal.fire({
                        icon: 'warning',
                        title: 'ops...',
                        text: "Algo de errado aconteceu!",
                        footer: '<a href="<?=ROTA_GERAL?>/Administrativo/estudantes">Atualizar?</a>'
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
                       preparaModalEditarEstudantes(data);
                    }
                }
            })
            .done(function(data) {
                if(data != false && typeof data !== 'object'){
                    return Swal.fire({
                        icon: 'success',
                        title: 'OhoWW...',
                        text: data,
                        footer: '<a href="<?=ROTA_GERAL?>/Administrativo/estudantes">Atualizar?</a>'
                    }).then(()=>{
                        window.location.reload();    
                    })
                }
                if(typeof data !== 'object')
                    return Swal.fire({
                            icon: 'warning',
                            title: 'ops...',
                            text: "Algo de errado aconteceu!",
                            footer: '<a href="<?=ROTA_GERAL?>/Administrativo/estudantes">Atualizar?</a>'
                    })
            });
            return "";
        }

        function preparaModalEditarEstudantes(data) {
            $('#codigo').val(data[0].matricula);
            $('#nome').val(data[0].nome);           
            $('#endereco').val(data[0].endereco);
            $('#email').val(data[0].email);
            $('#telefone').val(data[0].telefone);
            $('#id').val(data[0].id);
            $('#btnSubmit').addClass('Atualizar');
            $('#exampleModalLabel').text("Atualizar Estudante");
            $('#modalEstudantes').modal('show');   
        }

     $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/Estudantes/"+texto;
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Estudantes");
        $('#modalEstudantes').modal('show');        
    });

    $(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modalEstudantes').modal('hide');
        });

        $(document).on('click','.Salvar',function(){
            $('#btnSubmit').attr('disabled','disabled');
            event.preventDefault();
            var dados = {
                codigo:$('#codigo').val(),
                nome:$('#nome').val(),
                endereco:$('#endereco').val(),
                email:$('#email').val(),
                telefone:$('#telefone').val()
            };
            
            envioRequisicaoPostViaAjax('Administrativo/InserirEstudante/', dados);                
        });

        $(document).on('click','.Atualizar',function(){
            event.preventDefault();
            $('#btnSubmit').attr('disabled','disabled');
            var id = $('#id').val();
            var dados = {
                codigo:$('#codigo').val(),
                nome:$('#nome').val(),
                endereco:$('#endereco').val(),
                email:$('#email').val(),
                telefone:$('#telefone').val()
            };
            envioRequisicaoPostViaAjax('Administrativo/updateEstudante/' + id, dados);      
        });

    $(document).on('click','.view_data',function(){
        var id=$(this).attr("id");
        $('#btnSubmit').removeClass('Salvar');
        envioRequisicaoGetViaAjax('Administrativo/buscaEstudantePorId/' + id);
    });

    $(document).on('click','.view_Ativo',function(){    
            event.preventDefault();
            var code=$(this).attr("id");
            var dados={
                codigo:code,
                status:"Inativo"   
            };
            envioRequisicaoGetViaAjax('Administrativo/changeStatusEstudante/'+ code);
    });    

    $(document).on('click', '.view_vinculo', function(){
        var id = $(this).attr("id");
        $('#turma').val('');
        $.ajax({
                url: '<?=ROTA_GERAL?>/Administrativo/buscaEstudantePorId/'+ id,
                method:'POST',
                processData: false,
                dataType: 'JSON',
                success: function(data){
                    $('#id_vinculo').val(id);
                    $('#nome_vinculo').val(data[0].nome);
                    $('#codigo_vinculo').val(data[0].id);
                }
        })
        .done(function(data) {           
            $.ajax({
                url: '<?=ROTA_GERAL?>/Administrativo/buscaVinculoTurmaEstudantePorIdEstudante/'+ id,
                method:'POST',
                processData: false,
                dataType: 'JSON',
                success: function(data){
                    $('#id_vinculo').val(id);                    
                    $('#turma').val(data[0].turmas_id);     
                }
            });
        })
        .fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'requisição não finalizada!',
                footer: '<a href="<?=ROTA_GERAL?>/Administrativo/Estudantes">Atualizar?</a>'
            })
        })
        .always(function() {
            $('#modalVinculoEstudantes').modal('show');
        });        
    });

    // vincular
    $(document).on('click','.Vincular',function(){
        event.preventDefault();
        turma = $('#turma').val();
        id = $('#id_vinculo').val();
        var dados = {
            id:id,
            turma:turma
        };
        envioRequisicaoPostViaAjax('Administrativo/vincularEstudanteTurma', dados);
    });

});

    cpfCheck = function (el,id) {
        document.getElementById(id).innerHTML = is_cpf(el.value)? '<span style="color:green">válido</span>' : '<span style="color:red">inválido</span>';
            if(el.value=='') document.getElementById(id).innerHTML = '';
    }
    
    function is_cpf (c) {
        if((c = c.replace(/[^\d]/g,"")).length != 11)
        return false

        if (c == "00000000000")
        return false;

        var r;
        var s = 0;

        for (i=1; i<=9; i++)
        s = s + parseInt(c[i-1]) * (11 - i);

        r = (s * 10) % 11;

        if ((r == 10) || (r == 11))
        r = 0;

        if (r != parseInt(c[9]))
        return false;

        s = 0;

        for (i = 1; i <= 10; i++)
        s = s + parseInt(c[i-1]) * (12 - i);

        r = (s * 10) % 11;

        if ((r == 10) || (r == 11))
        r = 0;

        if (r != parseInt(c[10]))
        return false;

        return true;
    }


    function fMasc(objeto,mascara) {
        obj=objeto
        masc=mascara
        setTimeout("fMascEx()",1)
    }

    function fMascEx() {
        obj.value=masc(obj.value)
    }

    function mCPF(cpf){
        cpf=cpf.replace(/\D/g,"")
        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
        cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
        return cpf
    }
</script>