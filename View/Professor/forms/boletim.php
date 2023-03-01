<div class="col main">
    <h1 class="display-4 d-none d-sm-block">
        Montar Boletim
    </h1>      
            <!-- <p class="lead d-none d-sm-block">Historico</p> -->       
    <a id="features"></a>
    <hr>
    <input type="hidden" disabled name="id_disciplinas" id="id_disciplinas" value="<?=$this->disciplina_id?>">
    
    <div class="row">            
        <div class="col">
            <label for="" class="">Bimestre</label>
                <select class="form-control " name="bimestre" id="bimestre">
                    <option value="">Todos</option>
                    <?php                            
                        foreach($this->bimestres() as $key=>$value)
                        {
                            echo '<option value="'.$value['unidade'].'">'.$value['unidade'].' Bimestre</option>';
                        }
                    ?>
                </select>
        </div>

        <div class="col">
            <label for="" class="">Estudantes</label>
                <select class="js-example-basic-multiple js-states form-control" id="estudantes" name="states[]" multiple="multiple">  
                    <option value="">Todos</option>              
                    <option value="WY">Wyoming</option>
                    <?php       
                        // foreach($this->buscarTurmasPorProfessor($_SESSION['code']) as $key=>$value)
                        // {
                        //     echo '<option value="'.$value['id_cursos'].'">'.$value['curso'].'</option>';
                        // }
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
                <a class="btn btn-warning mt-4" href="<?=ROTA_GERAL?>/Professor">Voltar</a>  
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center hide" id="loader">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

</div> 

<script>
    $(document).ready(function(){
        $('#estudantes').select2();
    });
</scrip>