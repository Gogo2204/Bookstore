<?php session_start(); ?>
<div class="container-fluid">
	<form action="" id="login-frm">
		<div class="form-group">
			<label for="" class="control-label">Имейл *</label>
			<input type="text" name="email" id="email" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Парола *</label>
			<input type="password" name="password" id="password" class="form-control">
			<p style="padding-top:10px;"><input type="checkbox" onclick="showPassword()">Покажи паролата</p>
			<a href="javascript:void(0)" id="new_account">Нямаш профил? Регистрирай се тук!</a>
		</div>
		<button class="button btn btn-primary btn-sm">Влез</button>
		<button class="button btn btn-secondary btn-sm" type="button" data-dismiss="modal">Отмени</button>
	</form>
</div>
<style>
	#uni_modal .modal-footer{
		display:none;
	}
</style>
<script>
	function showPassword() {
		var x = document.getElementById("password");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}

	$('#new_account').click(function(){
		uni_modal("Регистрирай се", 'signup.php?redirect=index.php?page=checkout');
	});

	$('#login-frm').submit(function(e){
		e.preventDefault();
		start_load();

		var email = $('#email').val();
		var password = $('#password').val();
		
		if(email != "" && password != ""){
			$.ajax({
				url:'admin/ajax.php?action=login_user',
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err);
				end_load();
				},
				success:function(result){
					if(result==200){
						setTimeout(function(){
						location.reload();
						},250);
					} else if(result==201){
						$('#login-frm').prepend('<div class="alert alert-danger">Няма такъв потребител</div>');
						$('#login-frm button[type="button"]').removeAttr('disabled').html('Login');
						end_load();
					} else {
						$('#login-frm').prepend('<div class="alert alert-danger">Паролата не съвпада</div>');
						$('#login-frm button[type="button"]').removeAttr('disabled').html('Login');
						end_load();
					}
				}
			});
		} else {
			$('#login-frm').prepend('<div class="alert alert-danger">Попълни задължителните полета</div>');
			end_load();
		}		
	});
</script>