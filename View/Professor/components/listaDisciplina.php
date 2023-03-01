
<div class="col main ">
    <h1 class="display-4 d-none d-sm-block">
        <?= $titulo?>
    </h1>
        <!-- <p class="lead d-none d-sm-block">Historico</p> -->
    <div class="row"><p>Selecione a turma</p>
            
        <select class="form-control col-sm-2 ml-2" name="tumas" id="turmas" onchange="listaTabela();">
            <?php                    
                foreach($this->buscarTurmasPorProfessor($_SESSION['code']) as $key=>$value)
                    {
                        echo '<option value="'.$value['id_cursos'].'">'.$value['curso'].'</option>';
                    }
                ?>
        </select>
    </div>
    <p class="lead d-none d-sm-block">Segue a lista de disciplinas desta determinada turma selecionada</p>    
    <hr>
    <div class="row placeholders mb-3" id="disciplinas"></div>
</div>
<!--/.container-->

<script>


function listaDisciplinas(params)
{
    var html="";

    for(let i=0;i<params.length;i++)
    {
       
    html+='<div class="col-6 col-sm-3 placeholder text-center" onclick="turma('+params[i]['id_disciplinas']+')">';
    html+='<img src="https://via.placeholder.com/150/2'+ (Math.floor(Math.random() * 99999)) +'/fff?text='+params[i]['disciplinas']+'" class="mx-auto img-fluid rounded-circle" alt="'+params[i]['disciplinas']+'">';
                    html+='<h4>'+params[i]['curso']+'</h4>';
                    html+='<span class="text-muted">'+params[i]['categoria']+'</span>';
                html+='</div>';
    }

    $('#disciplinas').html(html);
}

$(document).ready(function(){
    var turmas=$('#turmas').val();
    $.ajax({
        method:'POST',
        url: "<?=ROTA_GERAL?>/Professor/filtrarDisciplinas/"+turmas,
        dataType:'json',
        processData: false,        
        success:function(resposta)
        {
            listaDisciplinas(resposta);
        }
    });
});


function listaTabela()
{
    var turmas=$('#turmas').val();
    $.ajax({
        method:'POST',
        url: "<?=ROTA_GERAL?>/Professor/filtrarDisciplinas/"+turmas,
        dataType: 'json',
        processData: false,        
        success:function(resposta)
        {
            listaDisciplinas(resposta);
        }
    });
}

 function turma(id)
 {
     window.location.href="<?=ROTA_GERAL?>/Professor/<?=$redirecionamento?>/"+id;
 }
</script>