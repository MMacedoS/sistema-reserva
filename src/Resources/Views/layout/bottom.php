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
    </body>
</html>
