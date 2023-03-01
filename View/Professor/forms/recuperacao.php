<div class="col main">
    <h1 class="display-4 d-none d-sm-block">
        Lançar Recuperação Final
    </h1>      
            <p class="lead d-none d-sm-block">Esta modalidade só pode ser utilizada no final do Ano letivo</p>       
    <a id="features"></a>
    <hr>
 
    <input type="hidden" disabled name="id_disciplinas" id="id_disciplinas" value="<?=$this->disciplina_id?>">
    
    <div class="row">            
        
        <div class="col">
            <label for="" class="">Ano Letivo
                <input type="number" class="form-control" step="0000" name="ano" id="ano" onkeypress="listaTabela();" value="<?=Date('Y')?>">
            </label>
        </div>
        <div>
            <div class="col">
                <button class="btn btn-success mt-4" onclick="listaTabela()">Buscar</button>
                <a class="btn btn-warning mt-4" href="<?=ROTA_GERAL?>/Professor/recuperacao">Voltar</a>  
            </div>
        </div>
    </div>
          
    <div class="d-flex justify-content-center hide" id="loader">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <span id="success_message"></span>
    <div class="form-group" id="process" style="display:none;">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-8">
        <div class="table-responsive">
            <table class="table table-striped" id="lista"></table>
        </div>                 
    </div> 

</div> 

<script>

var column;
function salvar(obj,atv1,code)
{
    $('#loader').removeClass('hide');  
    document.getElementById('salvar_1').disabled=true;
    column = $(obj).parents("tr");
    console.log(column);
    var atv1 = column[0].children[1].children['at1'].value !== undefined ? column[0].children[1].children['at1'].value : '';
    var id = column[0].children[2].children['code'].value !== undefined ? column[0].children[2].children['code'].value : '';
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/inserirRecuperacao/",
        data:
        {
            id:id,
            nota:atv1,                   
        },
        dataType: 'json',  
        beforeSend:function()
        {
            $('#salvar_1').attr('disabled', 'disabled');
            $('#process').css('display', 'block');
        },     
        success:function(resposta) {
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

$(document).ready(function() {
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();        
    if(!ano<=2019){
        $.ajax({
            method:"POST",
            url:"<?=ROTA_GERAL?>/Professor/filtrarRecuperacao/",
            data: {
                id:id,
                ano:ano
            },
            dataType: 'json', 
            processData: false,        
            success:function(resposta) {
               if(resposta) {
                resposta.length > 0 ? lista(resposta) : '';                
               } else {
                    window.alert('Necessita da permissao do coordenador');
               }
                // console.log(resposta);
            }       
        });
    // lista('');
    } else {
        window.alert('Ano letivo não considerado');
    }
});

function listaTabela()
{
    $('#loader').removeClass('hide');  
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/filtrarRecuperacao/",
        data:{id:id,ano:ano},
        dataType: 'json',      
        success:function(resposta) {
            $('#loader').addClass("hide"); 
            if(resposta) {
                resposta.length > 0 ? lista(resposta) : lista();
                // console.log(resposta['id_disciplinas']);
            } else {
                window.alert('Necessita da permissao do coordenador');
            }
        }       
    });
}

function deletaApre(param)
{
    $('#loader').removeClass('hide');  
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/deletarRecuperacao/",
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

function recalcular(param)
{
    $('#loader').removeClass('hide'); 
    var ano=$('#ano').val(); 
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/recalcularNotaFinal/",
        data:{id:param, ano:ano},
        dataType: 'json', 
        success:function(resposta) {
            listaTabela();
            // console.log(resposta['id_disciplinas']);
        }       
    });
}


function lista(params = null)
{
    var arrayD=params;
   
    var html="";

    if(params === null)
    {
        var html="";
        html+='<th> Não possui registros!</th>';
    }
   
    if(params) {
        console.log(params);
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
                            '<th>Recalcular</th>'
                        '</tr>'+
                      '</thead>'+
                    '<tbody>';

        for(let i=0;i<params.length;i++) {
            html+='<tr>'+
            '<td>'+params[i]['nome']+'</td>';
            if(params[i]['media']>=27.6 || params[i]['recuperacao']!=null || params[i]['situacao']!='reprovado'){
                html+='<td>';
                    if (params[i]['recuperacao']!=null) {
                        html+=params[i]['recuperacao'];
                        html+='<a id="" onclick="deletaApre('+params[i]['idRes']+');"><img src="/image/deleta.png" width="30" border="0" title="deleta nota" /></a>';
                    }
                        html+='<input type="hidden"  step="0.1" class="form-control" id="" name="at1" disabled value=""/>';                            
                html+='</td>';
            } else {
                html+='<td><input type="number" step="0.1" max="10" min="0" class="form-control" id="at'+i+'" name="at1"/></td>';
            }

            html+='<td style="display:none;"><input type="text" class="form-control" id="" name="code" value="'+params[i]['idRes']+'"/></td>';                                    
            html+='<td><input type="number" disabled step="0.1" min="0" max="10" class="form-control" id="" name="code" value="'+params[i]['media']+'"/></td>';

            if(params[i]['media'] < 27.6 && params[i]['recuperacao']==null) {
                html+='<td><button class="btn btn-primary" id="salvar_1" onclick="salvar(this,2,3);">Salvar</button></td>';
            } 
            if(params[i]['recuperacao'] >= 7 || params[i]['media'] >= 28){                
                html+='<td>&#10004;</td>';
            } else {
                html+='<td>&#10006;</td>';
            }
            html+='<td><button class="btn btn-success" id="salvar_1" onclick="recalcular('+params[i]['idRes']+');">Recalcular</button></td>';
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