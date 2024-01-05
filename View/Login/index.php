<?php $dados = $this->findParamByParam('nome_site'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistema Reserva</title>
    <link rel="shortcut icon" href="<?=ROTA_GERAL?>/Estilos/img/logo.png">
	<link rel="stylesheet" href="<?=ROTA_GERAL?>/Estilos/css/bootstrap.min.css">
	<link href="<?=ROTA_GERAL?>/Estilos/css/login.css" rel="stylesheet" id="bootstrap-css">

	
<style>
	#retornar{
		background: #df4848 !important;
	}

	.abcRioButtonLightBlue {
		width: 100% !important;
		border-radius:50px !important;
		color:#FFFFFF !important;
		content:"Opa" !important;
		background: linear-gradient(-50deg, #df7b07, #df7b07, #d8bc06, #0a3365, #0a3365) !important;
	}
</style>

<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="84165197314-nr5636p86jsp5duuglfnop76jktu2i82.apps.googleusercontent.com">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<a href="<?=ROTA_GERAL?>"><img src="<?=ROTA_GERAL?>/Estilos/img/img-01.png" alt="IMG bag" id="imagen"></a>
				</div>

				<form class="login100-form validate-form" action="" method="post">
					<span class="login100-form-title">
						<!-- <a href="<=ROTA_GERAL?>"><img src="<=ROTA_GERAL?>/Estilos/img/image_logo.png" width="150" alt="IMG" id="imagen"></a> -->
						<?=$dados['valor'] ?>
					</span>

					<div class="wrap-input100 validate-input" data-validate = "email obrigatório: ex@abc.xyz">
						<input class="input100" type="email" id="user" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "obrigatório possuir senha">
						<input class="input100" type="password" id="password" placeholder="senha">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div id='recaptcha' class="g-recaptcha" data-sitekey="6LeV0jYpAAAAAGWoi1W7GOHfIJXZvBeQtUPeDMR7"></div>
					
					<div class="container-login100-form-btn">
						<button type="button" class="login100-form-btn" id="enviar">
							Entrar
						</button>
					</div>
					

					<!-- <div class="text-center p-t-12">
						<span class="txt1">
							Esqueceu sua
						</span>
						<a class="txt2" data-toggle="modal" data-target="#modal-senha" href="#">
							senha?
						</a>
					</div> -->
					<br>
					<!--<p>Acesso Institucional</p>-->
					<!--<div class="g-signin2" data-onsuccess="onSignIn"></div>-->
				</form>
			</div>
		</div>
	</div>
</body>
</html>

<div class="modal fade" id="modal-senha" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-dark">Recuperar Senha</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post">
				<div class="modal-body">
					<div class="form-group">
						<label class="text-dark" for="exampleInputEmail1">Seu Email</label>
						<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="txtEmail">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button name="recuperar-senha" type="submit" class="btn btn-primary">Recuperar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
	$('#enviar').click(function(){
		let recaptcha = grecaptcha.getResponse();
		if (recaptcha == '') {
			return false;
		}

		let dados = {
			user: $('#user').val(),
			password: $('#password').val(),
			recaptcha: recaptcha
		}

		$.post('<?=ROTA_GERAL?>/Login/logar',dados).then(function(response) {
				window.location.reload();
		});
	});
// document.getElementById('olho').addEventListener('mousedown', function() {
//   document.getElementById('pass').type = 'text';
// });
// document.getElementById('olho_e').addEventListener('mousedown', function() {
//   document.getElementById('pass_e').type = 'text';
// });

// document.getElementById('olho').addEventListener('mouseup', function() {
//   document.getElementById('pass').type = 'password';
// });
// document.getElementById('olho_e').addEventListener('mouseup', function() {
//   document.getElementById('pass_e').type = 'password';
// });

// Para que o password não fique exposto apos mover a imagem.
// document.getElementById('olho').addEventListener('mousemove', function() {
//   document.getElementById('pass').type = 'password';
// });
// Para que o password não fique exposto apos mover a imagem.
// document.getElementById('olho_e').addEventListener('mousemove', function() {
//   document.getElementById('pass_e').type = 'password';
// });

//
//function onSignIn(googleUser) {
  //var profile = googleUser.getBasicProfile();
 // console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  //console.log('Name: ' + profile.getName());
 // console.log('Image URL: ' + profile.getImageUrl());
 // console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
 // var dados={emailInstitucional:profile.getEmail()}
 // $.post('autenticar.php',dados);
 // var acesso="<=@$_SESSION['institucional']?>";
 // console.log(acesso);
 // if(acesso=='logado'){
  //    setInterval(() => {
    //    window.location.href='autenticar.php';    
    //  }, 1000);
            
//}else{
  //  setInterval(() => {
    //    window.location.href='login.php?login=6';
      //  window.open("https://accounts.google.com/SignOutOptions", "_blank", "toolbar=no,width=600,height=400");
    // }, 12000);
    
//}
 
//} 


//function signOut() {
  //    var auth2 = gapi.auth2.getAuthInstance();
    ///  auth2.signOut().then(function () {
      //  console.log('User signed out.');
      //});
//    }
</script>

