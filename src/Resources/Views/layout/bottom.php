      </div>
    </div>
      <!-- App footer start -->
      <div class="app-footer">
        <div class="container">
          <span>© Sindsmut <?=date('Y')?></span>
        </div>
      </div>
      <!-- App footer end -->

          <!-- *************
        ************ JavaScript Files *************
      ************* -->
      
      <!-- Required jQuery first, then Bootstrap Bundle JS -->
      <script src="<?=URL_PREFIX?>/Public/assets/js/jquery.min.js"></script>
      
      <!-- Include Select2 JS -->
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="<?=URL_PREFIX?>/Public/assets/js/bootstrap.bundle.min.js"></script>

      <!-- *************
        ************ Vendor Js Files *************
      ************* -->

      <!-- Overlay Scroll JS -->
      <script src="<?=URL_PREFIX?>/Public/assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
      <script src="<?=URL_PREFIX?>/Public/assets/vendor/overlay-scroll/custom-scrollbar.js"></script>
      <!-- <script src="public/assets/vendor/quill/quill.min.js"></script>
      <script src="public/assets/vendor/quill/custom.js"></script> -->

      <script src="<?=URL_PREFIX?>/Public/assets/js/custom.js"></script>
      <script src="<?=URL_PREFIX?>/Public/assets/js/validations.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

      <script>
        let isRequestInProgress = false;
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Selecione os Hóspedes",
                allowClear: true
            });

          function buscarApartamentos() {
            if (isRequestInProgress) return;
            let checkin = $('#dt_checkin').val();
            let checkout = $('#dt_checkout').val();
            let reserve = $('#reserve').val() != '' ? $('#reserve').val() : null;

          if (checkin && checkout) {
            isRequestInProgress = true;
              $.ajax({
                url: '/reserva/apartamentos', // Endpoint PHP para processar a requisição
                type: 'POST',
                dataType: 'json',
                data: { dt_checkin: checkin, dt_checkout: checkout, reserve: reserve },
                success: function(response) {
                  isRequestInProgress = false;
                  setApartamentos(response);
                },
                error: function() {
                  isRequestInProgress = false; // Libera em caso de erro também
                }
              });
            }
          }
          $('#dt_checkin, #dt_checkout').on('change', buscarApartamentos);
        });

        function setApartamentos(data) {
          let $apartamentoSelect = $('#apartament');

          // Verifica se o elemento foi encontrado
          if ($apartamentoSelect.length) {
            $apartamentoSelect.empty(); // Limpa o select
            if(data.length > 0) {
              $.each(data, function(index, apartamento) {
                $apartamentoSelect.append(
                  $('<option>', { 
                    value: apartamento.id, 
                    text: apartamento.name, 
                    selected: true
                  })
                );
              });
              return;
            }
            $apartamentoSelect.append(
              $('<option>', { 
                value: '', 
                text: 'Nenhum apartamento disponível' 
              })
            );
          }
        }
      </script>
      
      <script>
          function list(data) {  
              if (!data) {
                  return;
              }

              $('#list').empty();

              data.forEach((value) => {
                  let date_daily = value.dt_daily.split('-');
                  let tr = "<tr>";
                  tr += `<td>${value.id}</td>`;            
                  tr += `<td>${value.amount}</td>`;            
                  tr += `<td>${date_daily[2]}/${date_daily[1]}/${date_daily[0]}</td>`;
                  tr += `<td>
                      <a class="btn btn-primary" onclick="editar('${value.uuid}')"><span class="icon-edit"></span></a>
                      <a class="btn btn-danger" onclick="deleteItem('${value.uuid}')"><span class="icon-trash"></span></a>
                  </td>`;
                  tr += `<td> <input type="checkbox" onclick="checkBox()"; class="form-check m-0" value="${value.uuid}" /></td>`;
                  tr += `</tr>`;

                  $('#list').append(tr);
              })
          }

          function openModelDiaries(params) {
              showData(`/consumos/reserva/${params}/diarias`)
              .then((result) => {
                  $('#checkAll').val(params);
                  list(result)
                  $('#modalDiaries').modal('show');
              });
          }   

          $('#amount').on('change', function() {
              if($('#amount').val() > 0) {
                  $('#btn_inserir').removeAttr('disabled');
              }
          });

          $('#checkAll').click(function() {
              $('#list .form-check').prop('checked', this.checked);
              
              $('#get-checked-values').attr('disabled', !this.checked);
          });

          function getCheckedValues() {
            let selectedValues = [];

            $('#list .form-check:checked').each(function() {
              selectedValues.push($(this).val());
            });

            return selectedValues;
          }

          $('#get-checked-values').click(function() {
            const values = getCheckedValues();
            if (values.length === 0) {
              $('#get-checked-values').attr('disabled', true);
            }

            Swal.fire({
                  title: "Tem certeza que deseja deletar estes dados?",
                  text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sim, deletar!",
                  cancelButtonText: 'Cancelar',
                  }).then((result) => {
                  if (result.isConfirmed) {
                      let params = $('#checkAll').val();
                      deleteData(`/consumos/reserva/${params}/diarias?data=${values}`)
                      .then((result) => {
                          showMessage(result, 'success');
                          showData(`/consumos/reserva/${params}/diarias`)
                          .then((result) => {
                              $('#checkAll').val(params);
                              list(result)
                              $('#modalDiaries').modal('show');
                          });
                      });
                  }
              });
          });

          function checkBox() {
              $('#get-checked-values').attr('disabled', false);
          }

          $('#btn_inserir').click(function() {
              let params = $('#checkAll').val();
              let id = $('#id').val();
              if (id === '') {
                  createData(`/consumos/reserva/${params}/diarias`, new FormData(document.getElementById("form_inserir"),))
                  .then((result) => {
                      showMessage(result, 'success');
                      showData(`/consumos/reserva/${params}/diarias`)
                      .then((result) => {
                          $('#checkAll').val(params);
                          list(result)
                          $('#modalDiaries').modal('show');
                      });
                  });
                  return;
              }
              updateDataWithData(`/consumos/reserva/${params}/diarias/${id}`, new FormData(document.getElementById("form_inserir"),))
              .then((result) => {
                  showMessage(result, 'success');
                  showData(`/consumos/reserva/${params}/diarias`)
                  .then((result) => {
                      $('#checkAll').val(params);
                      list(result)
                      $('#modalDiaries').modal('show');
                  });
              });
          });

          function editar(item) 
          {   
              let params = $('#checkAll').val();
              showData(`/consumos/reserva/${params}/diarias/${item}`)
              .then((result) => {
                $('#id').val(item);
                $('#dt_daily').val(result.dt_daily);
                $('#amount').val(result.amount);
                $('#btn_inserir').text('Atualizar Diária');       
              });
          }

          
          function deleteItem(item) {
            Swal.fire({
                  title: "Tem certeza que deseja deletar estes dados?",
                  text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sim, deletar!",
                  cancelButtonText: 'Cancelar',
                  }).then((result) => {
                  if (result.isConfirmed) {
                      let params = $('#checkAll').val();
                      deleteData(`/consumos/reserva/${params}/diarias/${item}`)
                      .then((result) => {
                          showMessage(result, 'success');
                          showData(`/consumos/reserva/${params}/diarias`)
                          .then((result) => {
                              $('#checkAll').val(params);
                              list(result)
                              $('#modalDiaries').modal('show');
                          });
                      });
                  }
              });
          }

      </script>

      <script>
          function listConsumptionsProducts(data) {  
              if (!data) {
                  return;
              }

              $('#listProducts').empty();

              data.forEach((value) => {
                  let created = formatDateWithHour(value.created_at);
                  let tr = "<tr>";
                  tr += `<td>${JSON.parse(value.products).name}</td>`;     
                  tr += `<td class="d-none-sm">${created}</td>`;       
                  tr += `<td>${formatCurrency(value.amount)}</td>`;            
                  tr += `<td>${value.quantity}</td>`;
                  tr += `<td>
                      <a class="btn btn-primary m-1" onclick="editar('${value.uuid}')"><span class="icon-edit"></span></a>
                      <a class="btn btn-danger m-1" onclick="deleteItem('${value.uuid}')"><span class="icon-trash"></span></a>
                  </td>`;
                  tr += `<td> <input type="checkbox" onclick="checkBox()"; class="form-check m-0" value="${value.uuid}" /></td>`;
                  tr += `</tr>`;

                  $('#listProducts').append(tr);
              })
          }

          function openModelProducts(params) {
              showData(`/consumos/reserva/${params}/produto`)
              .then((result) => {
                  $('#checkAllProducts').val(params);
                  listConsumptionsProducts(result)
                  $('#modalProduct').modal('show');
              });
          }   

          $('#checkAllProducts').click(function() {
              $('#listProducts .form-check').prop('checked', this.checked);
              
              $('#get-checked-values-products').attr('disabled', !this.checked);
          });

          function getCheckedValuesProducts() {
            let selectedValues = [];

            $('#listProducts .form-check:checked').each(function() {
              selectedValues.push($(this).val());
            });

            return selectedValues;
          }

          $('#get-checked-values-products').click(function() {
            const values = getCheckedValuesProducts();
            if (values.length === 0) {
              $('#get-checked-values-products').attr('disabled', true);
            }

            Swal.fire({
                  title: "Tem certeza que deseja deletar estes dados?",
                  text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sim, deletar!",
                  cancelButtonText: 'Cancelar',
                  }).then((result) => {
                  if (result.isConfirmed) {
                      let params = $('#checkAllProducts').val();
                      deleteData(`/consumos/reserva/${params}/produto?data=${values}`)
                      .then((result) => {
                          showMessage(result, 'success');
                          showData(`/consumos/reserva/${params}/produto`)
                          .then((result) => {
                              $('#checkAllProducts').val(params);
                              listConsumptionsProducts(result)
                              $('#modalProduct').modal('show');
                          });
                      });
                  }
              });
          });

          function checkBox() {
              $('#get-checked-values-products').attr('disabled', false);
          }

          $('#btn_inserir_consumo').click(function() {
              let params = $('#checkAllProducts').val();
              let id = $('#product_id').val();
              if (id === '') {
                  createData(`/consumos/reserva/${params}/produto`, new FormData(document.getElementById("form_inserir_produtos"),))
                  .then((result) => {
                      showMessage(result, 'success');
                      showData(`/consumos/reserva/${params}/produto`)
                      .then((result) => {
                          $('#checkAllProducts').val(params);
                          listConsumptionsProducts(result)
                          $('#modalProduct').modal('show');
                      });
                  });
                  return;
              }
              updateDataWithData(`/consumos/reserva/${params}/produto/${id}`, new FormData(document.getElementById("form_inserir_produtos"),))
              .then((result) => {
                  showMessage(result, 'success');
                  showData(`/consumos/reserva/${params}/produto`)
                  .then((result) => {
                      $('#checkAllProducts').val(params);
                      listConsumptionsProducts(result)
                      $('#modalProduct').modal('show');
                  });
              });
          });

          function editar(item) 
          {   
              let params = $('#checkAllProducts').val();
              showData(`/consumos/reserva/${params}/produto/${item}`)
              .then((result) => {
                $('#product_id').val(item);
                $('#product').val(result.id_produto);                
                $('#quantity').val(result.quantity);
                $('#btn_inserir_consumo').text('Atualizar Produto');       
              });
          }

          
          function deleteItem(item) {
            Swal.fire({
                  title: "Tem certeza que deseja deletar estes dados?",
                  text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sim, deletar!",
                  cancelButtonText: 'Cancelar',
                  }).then((result) => {
                  if (result.isConfirmed) {
                      let params = $('#checkAllProducts').val();
                      deleteData(`/consumos/reserva/${params}/produto/${item}`)
                      .then((result) => {
                          showMessage(result, 'success');
                          showData(`/consumos/reserva/${params}/produto`)
                          .then((result) => {
                              $('#checkAllProducts').val(params);
                              listConsumptionsProducts(result)
                              $('#modalProduct').modal('show');
                          });
                      });
                  }
              });
          }

      </script>

      <script>
          function listConsumptionsPayments(data) {  
              if (!data) {
                  return;
              }

              $('#listPayments').empty();

              data.forEach((value) => {
                  let created = formatDateWithHour(value.created_at);
                  let tr = "<tr>";
                  tr += `<td>${JSON.parse(value.products).name}</td>`;     
                  tr += `<td class="d-none-sm">${created}</td>`;       
                  tr += `<td>${formatCurrency(value.amount)}</td>`;            
                  tr += `<td>${value.quantity}</td>`;
                  tr += `<td>
                      <a class="btn btn-primary m-1" onclick="editar('${value.uuid}')"><span class="icon-edit"></span></a>
                      <a class="btn btn-danger m-1" onclick="deleteItem('${value.uuid}')"><span class="icon-trash"></span></a>
                  </td>`;
                  tr += `<td> <input type="checkbox" onclick="checkBox()"; class="form-check m-0" value="${value.uuid}" /></td>`;
                  tr += `</tr>`;

                  $('#listPayments').append(tr);
              })
          }

          function openModelPayments(params) {
              showData(`/consumos/reserva/${params}/produto`)
              .then((result) => {
                  $('#checkAllPayments').val(params);
                  listConsumptionsPayments(result)
                  $('#modalPayments').modal('show');
              });
          }   

          $('#checkAllPayments').click(function() {
              $('#listPayments .form-check').prop('checked', this.checked);
              
              $('#get-checked-values-payments').attr('disabled', !this.checked);
          });

          function getCheckedValuesPayments() {
            let selectedValues = [];

            $('#listPayments .form-check:checked').each(function() {
              selectedValues.push($(this).val());
            });

            return selectedValues;
          }

          $('#get-checked-values-payments').click(function() {
            const values = getCheckedValuesPayments();
            if (values.length === 0) {
              $('#get-checked-values-payments').attr('disabled', true);
            }

            Swal.fire({
                  title: "Tem certeza que deseja deletar estes dados?",
                  text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sim, deletar!",
                  cancelButtonText: 'Cancelar',
                  }).then((result) => {
                  if (result.isConfirmed) {
                      let params = $('#checkAllPayments').val();
                      deleteData(`/consumos/reserva/${params}/produto?data=${values}`)
                      .then((result) => {
                          showMessage(result, 'success');
                          showData(`/consumos/reserva/${params}/produto`)
                          .then((result) => {
                              $('#checkAllPayments').val(params);
                              listConsumptionsPayments(result)
                              $('#modalPayments').modal('show');
                          });
                      });
                  }
              });
          });

          function checkBox() {
              $('#get-checked-values-payments').attr('disabled', false);
          }

          $('#btn_inserir_payments').click(function() {
              let params = $('#checkAllPayments').val();
              let id = $('#payment_id').val();
              if (id === '') {
                  createData(`/consumos/reserva/${params}/produto`, new FormData(document.getElementById("form_inserir_Payments"),))
                  .then((result) => {
                      showMessage(result, 'success');
                      showData(`/consumos/reserva/${params}/produto`)
                      .then((result) => {
                          $('#checkAllPayments').val(params);
                          listConsumptionsPayments(result)
                          $('#modalPayments').modal('show');
                      });
                  });
                  return;
              }
              updateDataWithData(`/consumos/reserva/${params}/produto/${id}`, new FormData(document.getElementById("form_inserir_produtos"),))
              .then((result) => {
                  showMessage(result, 'success');
                  showData(`/consumos/reserva/${params}/produto`)
                  .then((result) => {
                      $('#checkAllPayments').val(params);
                      list(result)
                      $('#modalPayments').modal('show');
                  });
              });
          });

          function editar(item) {   
              let params = $('#checkAllPayments').val();
              showData(`/consumos/reserva/${params}/produto/${item}`)
              .then((result) => {
                $('#payment_id').val(item);
                $('#Payment').val(result.id_produto);                
                $('#quantity').val(result.quantity);
                $('#btn_inserir_Payments').text('Atualizar Produto');       
              });
          }

          
          function deleteItem(item) {
            Swal.fire({
                  title: "Tem certeza que deseja deletar estes dados?",
                  text: "A deleção é irrevesivel, não consiguirá reater estes daddos novamente",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sim, deletar!",
                  cancelButtonText: 'Cancelar',
                  }).then((result) => {
                  if (result.isConfirmed) {
                      let params = $('#checkAllPayments').val();
                      deleteData(`/consumos/reserva/${params}/produto/${item}`)
                      .then((result) => {
                          showMessage(result, 'success');
                          showData(`/consumos/reserva/${params}/produto`)
                          .then((result) => {
                              $('#checkAllPayments').val(params);
                              listConsumptionsPayments(result)
                              $('#modalPayments').modal('show');
                          });
                      });
                  }
              });
          }

      </script>
    </body>
</html>
