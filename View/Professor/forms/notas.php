
<div class="col main">
    <h1 class="display-4 d-none d-sm-block">
        Lançar notas por bimestre
    </h1>      
            <!-- <p class="lead d-none d-sm-block">Historico</p> -->       
    <a id="features"></a>
    <hr>
    <input type="hidden" disabled name="id_disciplinas" id="id_disciplinas" value="<?=$this->disciplina_id?>">
   
    <div class="row">
        <div class="col-md-4">
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
        
        <div class="col-md-2">
            <label for="" >Ano Letivo</label>
            <input type="number" class="form-control" step="0000" name="ano" id="ano" onkeypress="listaTabela();" value="<?=Date('Y')?>">      
        </div>
        
        <div>
            <div class="col-md-12">
                    <button class="btn btn-success mt-4" onclick="listaTabela()">Buscar</button>
                    <a class="btn btn-warning mt-4" href="<?=ROTA_GERAL?>/Professor/notaBimestre">Voltar</a>  
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
        <div class="table-responsive" style="height: 500px">
            <table class="table table-striped" id="lista"></table>
        </div>
    </div>
    
</div>

<!-- Modal -->
<div class="modal fade" id="avancaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Selecione uma Turma</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
            <table class="table table-striped" id="selectTurmas">
                           
            </table>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
               <!-- <button type="button" class="btn btn-primary-outline" data-dismiss="modal" onclick="sair();">OK</button> -->
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
 var column;

function lista(params = null )
{
   $('#loader').addClass("hide");
   
   var html="";
   let ligar = 0;
   if(params === null) {
        var html="";
        html+='<th> Não possui registros!</th>';
    }
   
    if(params) {
        var arrayD=params;   
        html+='<thead class="thead-inverse ">';
        html+='<tr style="">'+
                '<th>Disciplina: '+params[0]['disciplina']+'  ---  '+
                'Turma: '+params[0]['curso']+'</th>'+                                  
            '</tr>';
        html+='<tr style="">'+
                    '<th>Estudante</th>'+
                    '<th>ATV01 </th>'+
                    '<th>ATV02 </th>';
        if(params[0]['id_categoria']>1) {
            html+='<th>ATV03</th>';
        } else {
            html+="<th>&#10006;</th>"
        }
        html+='<th>ATV04</th>'+
            '<th>MÉDIA</th>'+
            '</tr>'+
            '</thead>'+
            '<tbody>';
        for(let i=0;i<params.length;i++) {
            html+='<tr>'+
            '<td>'+params[i]['nome']+'</td>';
            if(params[i]['nota1']!=null) {
                ligar++;
                html+='<td>'+params[i]['nota1']+'<input type="hidden" step="0.1" class="form-control" id="" name="at1" disabled value=""/>';
                html+='<a id="" onclick="deletaAt1('+params[i]['idnota1']+');"><img src="/image/deleta.png" width="30" border="0" title="deleta nota" /></a>';
                html+='</td>';
            } else {
                ligar--;
                html+='<td><input type="number" step="0.1" class="form-control" id="at'+i+'" min="0" max="5" name="at1" value=""/></td>';
            }
            if(params[i]['nota2']!=null) { 
                ligar++;
                html+='<td>'+params[i]['nota2']+'<input type="hidden" step="0.1" class="form-control" id="" name="at2" disabled value=""/>';
                html+='<a id="" onclick="deletaAt2('+params[i]['idnota2']+');"><img src="/image/deleta.png" width="30" border="0" title="deleta nota" /></a>';
                html+='</td>';
            } else {
                ligar--;
                html+='<td><input type="number" step="0.1" min="0" max="5" class="form-control" id="at'+i+'" name="at2"/></td>';
            }
            if(params[i]['nota3']!=null) {
                ligar++;
                html+='<td>'+params[i]['nota3']+'<input type="hidden" step="0.1" class="form-control" id="" name="at3" disabled value=""/>';
                html+='<a id="" onclick="deletaAt3('+params[i]['idnota3']+');"><img src="/image/deleta.png" width="30" border="0" title="deleta nota" /></a>';
                html+='</td>';
            } else {
                if(params[0]['id_categoria']>1) {
                    ligar++;
                    html+='<td><input type="number" step="0.1" min="0" max="5" class="form-control" id="at'+i+'" name="at3"/></td>';
                } else {
                    ligar--;
                    html+='<td><input type="hidden" disabled step="0.1" max="10" class="form-control" id="at'+i+'" name="at3"/></td>';
                }
            }
            if(params[i]['nota4']!=null) {
                ligar++;
                    html+='<td>'+params[i]['nota4']+'<input type="hidden"  step="0.1" class="form-control" id="" name="at4" disabled value=""/>';
                    html+='<a id="" onclick="deletaAt4('+params[i]['idnota4']+');"><img src="/image/deleta.png" width="30" border="0" title="deleta nota" /></a>';
                    html+='</td>';
            } else {
                ligar--;
                html+='<td><input type="number" step="0.1" min="0" max="5" class="form-control" id="at'+i+'" name="at4"/></td>';
            }
            
            html+='<td style="display:none;"><input type="text" class="form-control" id="" name="code" value="'+params[i]['matricula']+'"/></td>';

            if(params[i]['nota1']==null || params[i]['nota2']==null || params[i]['nota3']==null || params[i]['nota4']==null) {
                if(params[0]['id_categoria']==1) {
                    if(params[i]['nota1']==null || params[i]['nota2']==null || params[i]['nota4']==null) {
                        html+='<td><input type="number" disabled step="0.1" max="10" class="form-control" id="" name="code" value="'+params[i]['media']+'"/></td>';
                    } else {
                        html+='<td><input type="number" disabled step="0.1" max="10" class="form-control" id="" name="code" value="'+params[i]['media']+'"/></td>';
                    }
                } else {
                    html+='<td><input type="number" disabled step="0.1" max="10" class="form-control" id="" name="code" value="'+params[i]['media']+'"/></td>';
                    // html+='<td><button class="btn btn-primary" disabled onclick="salvar(this,2,3,4,5,6);"  >Salvar</button></td>';
                    
                }
            } else {
                html+='<td><input type="number" disabled step="0.1" max="10" class="form-control" id="" name="code" value="'+params[i]['media']+'"/></td>';
            }
            
            html+='</tr>';
        }
        html+='</tbody>';

        html+='<tr><td colspan="7"><button class="btn btn-primary" id="salvar_1" onclick="salvar();">Inserir Notas</button></td></tr>';
    }
    
    $('#lista').html(html);

    if(params) {
        for(let i=0;i<params.length;i++) {
            if(document.getElementById("at"+i+"")){
                //document.getElementById('at'+i+'').focus();
            break;
            }
        }        
    }

    document.getElementById('salvar_1').disabled=false;
}

// function salvar(
//     obj,
//     atv1,
//     atv2,
//     atv3,
//     atv4,
//     code
//     ) {
//         document.getElementById('salvar_1').disabled=true;
//         var bimestre= $('#bimestre').val();
//         var id=$('#id_disciplinas').val();
//         var ano=$('#ano').val();
//         column = $(obj).parents("tr").find("td:nth-child(" + atv1 + ")");
//         var atv1=column[0].querySelector('input');
//         atv1=atv1.value;
//         column = $(obj).parents("tr").find("td:nth-child(" + atv2 + ")");
//         var atv2=column[0].querySelector('input');
//         atv2=atv2.value;
//         column = $(obj).parents("tr").find("td:nth-child(" + atv3 + ")");
//         var atv3=column[0].querySelector('input');
//         atv3=atv3.value;
//         column = $(obj).parents("tr").find("td:nth-child(" + atv4 + ")");
//         var atv4=column[0].querySelector('input');
//         atv4=atv4.value;
//         column = $(obj).parents("tr").find("td:nth-child(" + code + ")");
//         var code=column[0].querySelector('input');
//         code=code.value;
        
//         $.ajax({
//             method:"POST",
//             url:"<=ROTA_GERAL?>/Professor/inserirNotaBimeste/",
//             data: {
//                 bimestre:bimestre,
//                 id:id,
//                 ano:ano,
//                 atv1:atv1,
//                 atv2:atv2,
//                 atv3:atv3,
//                 atv4:atv4,
//                 code:code
//             },
//             dataType: 'json', 
//             success:function(resposta) {
//                 if(resposta) {
//                     listaTabela();
//                 } else {
//                     window.alert('verifique o valor inserido no campo');
//                     listaTabela();
//                      document.getElementById('salvar_1').disabled=false;
//                 }
//             }       
//         });

//     }

function salvar() {
    $('#loader').removeClass('hide');  
    document.getElementById('salvar_1').disabled=true;
    var dados = [];
    var bimestre= $('#bimestre').val();
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();
    
    var tableData =  $("#lista tr");

    tableData = tableData.each(function(obj){
        return obj;
    });

    

    for (let index = 2; index < tableData.length -1; index++) {
        
       var at1 = tableData[index].children[1].children['at1'].value !== undefined ? tableData[index].children[1].children['at1'].value : '';
       var at2 = tableData[index].children[2].children['at2'].value !== undefined ? tableData[index].children[2].children['at2'].value : '';
       var at3 = tableData[index].children[3].children['at3'].value !== undefined ? tableData[index].children[3].children['at3'].value : '';
       var at4 = tableData[index].children[4].children['at4'].value !== undefined ? tableData[index].children[4].children['at4'].value : '';
       var code =tableData[index].children[5].children['code'].value;  

        dados.push(
            {
             'code': code,
             'notas': {
                0: {
                    'at1': at1
                }, 
                1:  {
                    'at2': at2
                }, 
                2:  {
                    'at3': at3
                }, 
                3:  {
                    'at4': at4
                }
                },
             'id': id,
             'ano': ano,
             'bimestre': bimestre
            });
    }

    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/inserirNotaBimeste/",
        data: {
            data:dados
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
                // window.alert('verifique o valor inserido');
                listaTabela();
                document.getElementById('salvar_1').disabled=false;
            }
        }            
    });
    
}


// $(document).ready(function(){      
//         $('#loader').hide();  
//         var bimestre= $('#bimestre').val();
//         var id=$('#id_disciplinas').val();
//         var ano=$('#ano').val();

//         if(!ano<=2019) {
//             $.ajax({
//                 method:"POST",
//                 url:"<=ROTA_GERAL?>/Professor/filtrarNotasBimestre/",
//                 data:{bimestre:bimestre,id:id,ano:ano},
//                 dataType: 'json', 
//                 success:function(resposta){                
//                     resposta.length > 0 ? lista(resposta) : lista();
//                     console.log(resposta);                    
//                 }       
//             });
    
//         } else{
//             window.alert('Ano letivo não considerado');
//         }
    // });

function listaTabela() {
    $('#loader').removeClass('hide');  
    var bimestre= $('#bimestre').val();
    var id=$('#id_disciplinas').val();
    var ano=$('#ano').val();
    console.log(bimestre,id,ano);
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/filtrarNotasBimestre/",       
        data:{bimestre:bimestre,id:id,ano:ano},
        dataType: 'json', 
        success:function(resposta){                  
            resposta.length > 0 ? lista(resposta) : lista();
                // console.log(resposta['id_disciplinas']);
        }       
    });
}


function deletaAt1(param) {
    $('#loader').removeClass('hide');  
    $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/deletarNotaBimestre/",
        data:{id:param,at:1},
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

function deletaAt2(param) {
  $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/deletarNotaBimestre/",
        data:{id:param,at:2},
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

function deletaAt3(param) {
  $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/deletarNotaBimestre/",
        data:{id:param,at:3},
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

function deletaAt4(param) {
  $.ajax({
        method:"POST",
        url:"<?=ROTA_GERAL?>/Professor/deletarNotaBimestre/",
        data:{id:param,at:4},
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