
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
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Apartamentos Disponiveis</h4>
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?=ROTA_GERAL?>/Administrativo/consultas" class="btn btn-primary" id="novo">Voltar</a>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">         
            
            <div class="col-sm-11 mt-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
            </div>

            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
    <div class="row">        
            <?php
                $reservas = $this->listApartamento();                    
                if(is_array($reservas)) {
                    foreach ($reservas as $key => $value) {
                        ?>

                    <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <a href="#" class="checkin" id="<?=$value['id']?>">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <?= $value['nome']?>
                                                </div>                 
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Codigo:
                                                <?= $value['id']?>
                                                </div> 
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">APT <?= $value['numero']?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-key fa-2x text-gray-300"></i>
                                            </div>                                                         
                                        </div>
                                    </div>
                                </a>    
                            </div>
                        </div>
                        
                    
            <?php }//foreach
                }//if
            ?>       
    </div>    

<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <input type="hidden" disabled id="id" >
                        <div class="col-sm-5">
                            <label for="">Data Entrada</label>
                            <input type="date" name="entrada" id="entrada" class="form-control">
                        </div>

                        <div class="col-sm-5">
                            <label for="">Data Saida</label>
                            <input type="date" name="saida" id="saida" class="form-control">
                        </div>

                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-primary mt-2" type="button" id="buscar">
                                <i class="fas fa-search fa-sm"></i>
                            </button>   
                        </div>                    
                    </div>
                    <div class="form-row hide" id="div_apartamento">

                        <div class="col-sm-8">
                            <label for="">Hospede</label><br>
                            <select class="form-control js-example-basic-single" name="hospedes" id="hospedes">
                                <?php
                                $hospedes = $this->buscaHospedes();
                                    foreach ($hospedes as $hospede) { 
                                        ?>
                                            <option value="<?=$hospede['id']?>"><?=$hospede['nome'] . " CPF: " . $hospede['cpf']?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="">Apartamentos</label><br>
                            <select class="form-control js-example-basic-single" name="apartamento" id="apartamento">
                               
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Tipo</label><br>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value="1">Diária</option>
                                <option value="2">Pacote</option>
                                <option value="3">Promocao</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="">Status</label><br>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Reservada</option>
                                <option value="2">Confirmada</option>
                                <option value="5">Cancelada</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="">Valor</label>
                            <input type="number" class="form-control" onchange="valores()" name="valor" step="0.01" min="0.00" value="0.00" id="valor">
                        </div>

                        <div class="col-sm-12">
                            <label for="">observação</label><br>
                            <textarea name="observacao" class="form-control" id="observacao" cols="30" rows="5"></textarea>
                        </div>
                    </div>   

                    <small>
                        <div align="center" class="mt-1" id="mensagem"></div>
                        <div align="right" class="mt-1 fs" id="valores"></div>
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

</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    let url = "<?=ROTA_GERAL?>/";
    $(document).ready(function()
    {        
        $(document).on('click','.checkin',function(){
            event.preventDefault();
            var code=$(this).attr("id");
            Swal.fire({
                title: 'Deseja fazer criar reserva ?',
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    var redirectUrl = "<?=ROTA_GERAL?>/Administrativo/Reservas/index"; 
                        redirectUrl += "?apartamento=" + code  + '&showModal=true';
                    
                    // Redireciona para a página de destino
                    window.location.href = redirectUrl;
                } else if (result.isDenied) {
                    Swal.fire('nenhuma mudança efetuada', '', 'info')
                }
            })
        });
       
    });
</script>