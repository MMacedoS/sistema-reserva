 <!-- Call to Action-->
 <?php $dados = $this->findParamByParam('whatsapp'); ?>
 <section class="content-section bg-primary text-white" id="call_to_image" style="background-color: <?=$this->color[1]['color']?> !important; color: <?=$this->color[3]['color']?> !important;">
            <div class="container px-4 px-lg-5 text-center">
                <h2 class="mb-4"><?=$this->text[8]['texto']?></h2>
                <p> <i class="fab fa-whatsapp"></i> WhatsApp: <?=self::formatPhoneNumber($dados['valor'])?></p>
                <!-- <a class="btn btn-xl btn-light me-4" href="#!">Clique aqui!</a> -->
                <a class="btn btn-xl btn-dark" target="_blank"  href="https://api.whatsapp.com/send?phone=<?=$dados['valor']?>" style="background-color: <?=$this->color[2]['color']?>;">Entre em contato! <i class="fab fa-whatsapp"></i></a>
            </div>
        </section>