window.minhaVariavel = "";
  // Função para criar um novo registro
  function createData(url, data) {
    showLoader();
    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData:false,
      success: function(response) {
        window.minhaVariavel = response.data;
        showSuccessMessage('Registro criado com sucesso!');
        hideLoader();
        showSuccessMessage("Registro criado com sucesso!");
      },
      error: function(error) {
        console.error('Erro ao criar registro:', error);
        hideLoader();
        showErrorMessage("Não foi possivel criar o registro");
      }
    });
  }

  // Função para atualizar um registro existente
  function updateData(url, data) {
    showLoader();
    $.ajax({
      url: url,
      type: 'PUT',
      data: data,
      success: function(response) {
        window.minhaVariavel = response.data;
        showSuccessMessage('Registro atualizado com sucesso!');
        hideLoader();
      },
      error: function(error) {
        console.error('Erro ao atualizar registro:', error);
        hideLoader();
      }
    });
  }

  // Função para exibir um registro
  function showData(url) {
    showLoader();
    return $.ajax({
      url: url,
      method:'GET',
      processData: false,
      dataType: 'json',
    }).catch(function(error){
      console.error('Erro ao obter registro:', error);
      hideLoader();
    });
  }

  // Função para excluir um registro
  function deleteData(url) {
    showLoader();
    $.ajax({
      url: url,
      method: 'DELETE',
      success: function(response) {
        window.minhaVariavel = response.data;
        showSuccessMessage('Registro excluído com sucesso!');
        hideLoader();
      },
      error: function(error) {
        console.error('Erro ao excluir registro:', error);
        hideLoader();
      }
    });
  }

  function showLoader() {
    $('<div class="spinner"></div>').appendTo('body');
  }

  function hideLoader() {
    $('.spinner').remove();
  }

  function showSuccessMessage(message) {
    Swal.fire({
      icon: 'success',
      title: 'Sucesso!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ok'
    }).then(()=>{
        refreshPage();
    });
  }
  
  function showWarningMessage(message) {
    Swal.fire({
      icon: 'warning',
      title: 'Atenção!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ok'
    }).then(()=>{
        refreshPage();
    });
  }
  
  function showErrorMessage(message) {
    Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: message,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ok'
    }).then(()=>{
        refreshPage();
    });
  }
  
  function refreshPage() {
    location.reload(true);
  }
  
  