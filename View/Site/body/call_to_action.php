<?php $dados = $this->findParamByParam('whatsapp'); ?>
 <!-- Call to Action-->
 <section class="content-section bg-primary text-white" id="call_to_action" style="background-color: <?=$this->color[1]['color']?> !important; color: <?=$this->color[3]['color']?> !important;">
            <div class="container px-4 px-lg-5 text-center">
                <h2 class="mb-4"><?=$this->text[9]['texto']?></h2>
                <p> <i class="fab fa-whatsapp"></i> WhatsApp: <?=$dados['valor']?></p>
                <!-- <a class="btn btn-xl btn-light me-4" href="#!">Clique aqui!</a> -->
                <a class="btn btn-xl btn-dark" target="_blank" style="background-color: <?=$this->color[2]['color']?>;" href="https://api.whatsapp.com/send?phone=<?=$dados['valor']?>">Entre em contato! <i class="fab fa-whatsapp"></i></a>
            </div>
        </section>