<!DOCTYPE html>
<html lang="bg">

	<?php 
		session_start();
		require './db_connect.php';
		ob_start();		
	?>

	<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Книжарница "Атина"</title>
		

	<?php require './header.php'; ?>
	<?php 
		if(isset($_SESSION['login_admin_id']))
			header("location:index.php?page=home");
	?>
	</head>

	<style>
		body{
			width: 100%;
			height: calc(100%);
			position: fixed;
			top:0;
			left: 0
			background: #007bff;
		}
		main#main{
			width:100%;
			height: calc(100%);
			display: flex;
		}

	</style>

	<body class="bg-dark">


	<main id="main" >
			<div class="align-self-center w-100">
			
			<div id="login-center" class="bg-dark row justify-content-center">
				<div class="card col-md-4">
					<div class="card-body">
						<form id="login-form" >
							<div class="form-group">
								<label for="username" class="control-label">Потребителско име</label>
								<input type="text" id="email" name="email" class="form-control">
							</div>
							<div class="form-group">
								<label for="password" class="control-label">Парола</label>
								<input type="password" id="password" name="password" class="form-control">
							</div>
							<center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Влез</button></center>
						</form>
					</div>
				</div>
			</div>
			</div>
	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

	</body>
	<script>
		$('#login-form').submit(function(e){
			e.preventDefault()
			$('#login-form button[type="button"]').attr('disabled',true).html('Влизане...');
			if($(this).find('.alert-danger').length > 0 )
				$(this).find('.alert-danger').remove();
			$.ajax({
				url:'ajax.php?action=login_admin',
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
			$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				},
				success:function(result){					
					if(result==200){
						location.href ='index.php?page=home'
					} else if(result==201){
						$('#login-form').prepend('<div class="alert alert-danger">Няма такъв потребител</div>');
						$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
					} else {
						$('#login-form').prepend('<div class="alert alert-danger">Паролата не съвпада</div>');
						$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
					}
				}
			});
		});
		$('.number').on('input',function(){
			var val = $(this).val();
			val = val.replace(/[^0-9 \,]/, '');
			$(this).val(val);
		});
	</script>	
</html>