
<div class="col-lg-4 col-sm-6 col-12">
  <div class="card mb-3">
    <div class="card-body">
      <div class="m-0">
        <label class="form-label">Apartamento</label>
        <input type="number" step="0" min="1" class="form-control" name="name" placeholder="numero do apartamento" value="<?=$apartamento->name ?? ''?>" />
      </div>
    </div>
  </div>
</div>

<div class="col-lg-4 col-sm-6 col-12">
  <div class="card mb-3">
    <div class="card-body">
      <div class="m-0">
        <label class="form-label">Situação</label>
        <select name="status" class="form-control" id="">
            <option value="0" <?php if(isset($apartamento->status) && $apartamento->status == '0') { echo 'selected'; } ?>>Impedido</option>
            <option value="1" selected <?php if(isset($apartamento->status) && $apartamento->status == '1') { echo 'selected'; } ?>>Disponivel</option>
            <option value="2" <?php if(isset($apartamento->status) && $apartamento->status == '2') { echo 'selected'; } ?>>Ocupado</option>            
            <option value="3" <?php if(isset($apartamento->status) && $apartamento->status == '3') { echo 'selected'; } ?>>Sujo</option>
        </select>
      </div>
   </div>
  </div>
</div>

<div class="col-lg-4 col-sm-6 col-12">
    <div class="card mb-3">
        <div class="card-body">
            <div class="m-0">
                <label class="form-label">Categoria</label>
                <select name="category" class="form-control" id="">
                    <option value="Padrão" <?php if(isset($apartamento->category) && $apartamento->category == 'Padrão') { echo 'selected'; } ?>>Padrão</option>
                    <option value="Suite" <?php if(isset($apartamento->category) && $apartamento->category == 'Suite') { echo 'selected'; } ?>>Suite</option>
                    <option value="Solteiro" <?php if(isset($apartamento->category) && $apartamento->category == 'Solteiro') { echo 'selected'; } ?>>Solteiro</option>                       
                    <option value="Triplo" <?php if(isset($apartamento->category) && $apartamento->category == 'Triplo') { echo 'selected'; } ?>>Triplo</option>                      
                    <option value="Quadruplo" <?php if(isset($apartamento->category) && $apartamento->category == 'Quadruplo') { echo 'selected'; } ?>>Quadruplo</option>            
                    <option value="Quintuplo" <?php if(isset($apartamento->category) && $apartamento->category == 'Quintuplo') { echo 'selected'; } ?>>Quintuplo</option>
                    <option value="Chale" <?php if(isset($apartamento->category) && $apartamento->category == 'Chale') { echo 'selected'; } ?>>Chalé</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 col-sm-12 col-12">
  <div class="card mb-3">
    <div class="card-body">
      <div class="m-0">
        <label class="form-label">Descrição</label>
        <input type="text" class="form-control" name="description" placeholder="descrição do apartamento" value="<?=$apartamento->description ?? ''?>" />
      </div>
    </div>
  </div>
</div>

<div class="col-xxl-12">
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="\apartamento\" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</div>

