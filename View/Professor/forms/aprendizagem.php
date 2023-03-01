<div class="col main">
    <h1 class="display-4 d-none d-sm-block">
        Lançar aprendizagem
    </h1>      
            <!-- <p class="lead d-none d-sm-block">Historico</p> -->       
    <a id="features"></a>
    <hr>
    <span id="success_message"></span>
    <div class="form-group" id="process" style="display:none;">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
            </div>
        </div>
    </div>
    <input type="hidden" disabled name="id_disciplinas" id="id_disciplinas" value="<?=$this->disciplina_id?>">
    
    <div class="row">            
        <div class="col">
            <label for="" class="">Bimestre</label>
                <select class="form-control col-sm-6 ml-2" name="bimestre" id="bimestre" onchange="listaTabela();">
                    <?php                            
                        foreach($this->bimestres() as $key=>$value)
                        {
                            echo '<option value="'.$value['unidade'].'">'.$value['unidade'].' Bimestre</option>';
                        }
                    ?>
                </select>
        </div>
        <div class="col">
            <label for="" class="">Ano Letivo
                <input type="number" class="form-control" step="0000" name="ano" id="ano" onkeypress="listaTabela();" value="<?=Date('Y')?>">
            </label>
        </div>
        <div>
            <div class="col">
                <button class="btn btn-success mt-4" onclick="listaTabela()">Buscar</button>
                <a class="btn btn-warning mt-4" href="<?=ROTA_GERAL?>/Professor/aprendizagem">Voltar</a>  
            </div>
        </div>
    </div>
           
    <div class="col-lg-12 col-md-8">
        <div class="table-responsive">
            <table class="table table-striped" id="lista"></table>
        </div>                 
    </div>

    <div class="d-flex justify-content-center hide" id="loader">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

</div> 

<script>

var column;
function salvar(obj,atv1,code)
{
    document.getElementById('salvar_1').disabled=true;
    var bimestre= $('#bimestre').val();
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();
    
    column = $(obj).parents("tr").find("td:nth-child(" + atv1 + ")");
    var atv1=column[0].querySelector('input');
    atv1=atv1.value;
    column = $(obj).parents("tr").find("td:nth-child(" + code + ")");
    var code=column[0].querySelector('input');
    code=code.value;
     
     $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/inserirAprendizagem/",
        data:
            {
                bimestre:bimestre,
                id:id,
                ano:ano,
                atv1:atv1,                
                code:code
            },
        beforeSend:function()
        {
            $('#salvar_1').attr('disabled', 'disabled');
            $('#process').css('display', 'block');
        },
        dataType: 'json', 
        success:function(resposta){
            var percentage = 0;

            var timer = setInterval(function(){
                    percentage = percentage + 20;
                    progress_bar_process(percentage, timer, 'success');
            }, 1000);
            
            if(!resposta) {
                window.alert('verifique o valor inserido');
                listaTabela();
                document.getElementById('salvar_1').disabled=false;
            }
        }       
    });
}

$(document).ready(function(){
        
    var bimestre= $('#bimestre').val();
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();
    var dados = {
            bimestre:bimestre,
            id:id,
            ano:ano
        };

    if(!ano <= 2019){ 
        $.ajax({
            method:"POST",
            url:"<?=ROTA_GERAL?>/Professor/filtrarAprendizagem/",
            data: dados,
            dataType: 'json',  
            success:function(resposta){               
                resposta.length > 0 ? lista(resposta) : lista();                
            }       
        });
    
    } else{
        window.alert('Ano letivo não considerado');
    }
});

function listaTabela()
{
    $('#loader').removeClass('hide');  
    var bimestre= $('#bimestre').val();
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();
    var dados = {
            bimestre:bimestre,
            id:id,
            ano:ano
        };
    console.log(bimestre,id,ano);
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/filtrarAprendizagem/",
        data: dados,
        dataType: 'json',       
        success:function(resposta) {
            $('#loader').addClass("hide"); 
            resposta.length > 0 ? lista(resposta) : lista();
            // console.log(resposta['id_disciplinas']);
        }       
    });
}

function deletaApre(param)
{
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/deletarAprendizagem/",
        data:{id:param},
        dataType: 'json',
        beforeSend:function()
        {
            $('#process').css('display', 'block');
        }, 
        success:function(resposta) {
            var percentage = 0;

            var timer = setInterval(function(){
                    percentage = percentage + 20;
                    progress_bar_process(percentage, timer, 'danger');
            }, 1000);
            
            // console.log(resposta['id_disciplinas']);
        }       
    });
}

function lista(params = null)
{
    var arrayD=params;
   
    var html="";

    if(params === null || params === '')
    {
        var html="";
        html+='<th> Não possui registros!</th>';
    }
   
    if(params) {
            html+='<thead class="thead-inverse ">';
                html+='<tr style="">'+
                            '<th>Disciplina: '+params[0]['disciplina']+'</th>'+
                            '<th>Turma: '+params[0]['curso']+'</th>'+                                    
                      '</tr>';

                html+='<tr style="">'+
                            '<th>Estudante</th>'+
                            '<th>ATV01 </th>'+
                            '<th>Média</th>'+
                            '<th>Status</th>'+
                        '</tr>'+
                      '</thead>'+
                    '<tbody>';

        for(let i=0;i<params.length;i++) {
            html+='<tr>'+
            '<td>'+params[i]['nome']+'</td>';
            if (params[i]['media']>=6.9 || params[i]['paralela']!=null) {
                html+='<td>';
                    if (params[i]['paralela']!=null) {
                        html+=params[i]['paralela'];
                        html+='<a id="" onclick="deletaApre('+params[i]['id_paralela']+');"><img src="/image/deleta.png" width="30" border="0" title="deleta nota" /></a>';
                    }
                        html+='<input type="hidden"  step="0.1" class="form-control" id="" name="at1" disabled value=""/>';                            
                html+='</td>';
            } else {
                html+='<td><input type="number" step="0.1" max="10" min="0" class="form-control" id="at'+i+'" name="at1"/></td>';
            }

            html+='<td style="display:none;"><input type="text" class="form-control" id="" name="code" value="'+params[i]['matricula']+'"/></td>';                                    
            html+='<td><input type="number" disabled step="0.1" min="0" max="10" class="form-control" id="" name="code" value="'+params[i]['media']+'"/></td>';

            if(params[i]['media']<6.9 && params[i]['paralela']==null) {
                html+='<td><button class="btn btn-primary" id="salvar_1" onclick="salvar(this,2,3);">Salvar</button></td>';
            } 
            if(params[i]['media']<6.9 && params[i]['paralela']!=null){
                html+='<td>&#10006;</td>';
            } else {
                html+='<td>&#10004;</td>';
            }
            html+='</tr>';
        }

        html+='</tbody>';     
    }

    $('#lista').html(html);
    
    for(let i=0;i<params.length;i++){
        if(document.getElementById("at"+i+"")) {
            document.getElementById('at'+i+'').focus();
        }
    }
}

function progress_bar_process(percentage, timer, tipo)
  {
   $('.progress-bar').css('width', percentage + '%');
   if(percentage > 100)
   {
    clearInterval(timer);
    $('#process').css('display', 'none');
    $('.progress-bar').css('width', '0%');
    if(tipo == 'danger'){
        $('#success_message').html("<div class='alert alert-warning'>Dados apagados</div>");
    }
    if(tipo == 'success'){
        $('#success_message').html("<div class='alert alert-success'>Dados Salvos</div>");
    }

    setTimeout(function(){
        $('#success_message').html('');
    }, 5000);

    listaTabela();
   }
  }

</script>