<style>
    .image-change {
        color: #fffffe;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-size: cover;
      background-position: center;
      transition: background 5s ease-in-out;
    }

    .image-change.bg-animation {
      /* animation: grow 5s; */
    }

    @keyframes grow {
      0% {
        transform: scale(1);
      }
      100% {
        transform: scale(1.05);
      }
    }
  </style>

<!-- Header-->
<header class="masthead d-flex align-items-center image-change">
    <div class="container px-4 px-lg-5 text-center" style="color: <?=$this->color[3]['color']?>;">
        <h1 class="mb-1"><?=$this->text[0]['texto']?></h1>
        <h3 class="mb-5"><em><?=$this->text[1]['texto']?></em></h3>
        <a class="btn btn-primary btn-xl" style="background-color: <?=$this->color[2]['color']?>;" href="#about"><?=$this->text[2]['texto']?></a>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     document.addEventListener('DOMContentLoaded', function() {
      const images = JSON.parse('<?=json_encode($this->images)?>');

      console.log(images);  

      let currentImageIndex = 0;

      function changeBackground() {
        const imageChangeElement = document.querySelector('.image-change');
        imageChangeElement.classList.add('bg-animation');
        setTimeout(function() {
          imageChangeElement.style.backgroundImage = `url(<?=ROTA_GERAL?>/Public/Site/Banner/${images[currentImageIndex]['imagem']})`;
          currentImageIndex = (currentImageIndex + 1) % images.length;
          setTimeout(function() {
            // imageChangeElement.classList.remove('bg-animation');
            setTimeout(changeBackground, 1000);
          }, 5000);
        }, 500);
      }

      changeBackground(); // Inicializa a troca de imagem
    });
    
</script>