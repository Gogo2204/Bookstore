<?php session_start();
require 'admin/db_connect.php';

if(isset($_SESSION['login_id'])){
	$query = $conn->query("SELECT * from users where id = {$_SESSION['login_id']} ");
	foreach($query->fetch_array() as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="signup-frm">
		<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '' ?>">		
		<div class="form-group">
			<label for="" class="control-label">Имейл *</label>
			<input type="text" name="email" id="email" class="form-control" value="<?php echo isset($email) ? $email : '' ?>">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Потребителско име *</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($username) ? $username : '' ?>">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Парола *</label>
			<input type="password" name="password" id="password" class="form-control">		
		</div>
		<div class="form-group">
			<label for="" class="control-label">Повтори паролата *</label>
			<input type="password" name="repear_p" id="repeat_p" class="form-control">		
		</div>
		<p><small><?php echo isset($id) ? "Ако не искаш да сменяш паролата си остави полето празно" : "" ?></small></p>
		<button class="button btn btn-primary btn-sm"><?php echo !isset($id) ? "Регистрирай се" : "Обнови" ?></button>
		<button class="button btn btn-secondary btn-sm" type="button" data-dismiss="modal">Отмени</button>
	</form>
</div>

<style>
	#uni_modal .modal-footer{
		display:none;
	}
</style>
<script>
	$('#signup-frm').submit(function(e){
		e.preventDefault();
		start_load();

		var id = $('#id').val();
		var email = $('#email').val();
		var username = $('#username').val();	
		var password = $('#password').val();
		var repeat_p = $('#repeat_p').val();

		if(id != "" && password == ""){
			password = "p";
			repeat_p = "p";
		}
		
		if(email != "" && username != "" && password != "" && repeat_p != ""){
			if(IsEmail(email)){
				if(password==repeat_p){			
					$.ajax({
						url:'admin/ajax.php?action=signup',
						method:'POST',
						type: 'POST',
						cache: false,
						contentType: false,
						processData: false,
						data: new FormData($(this)[0]),
						error:err=>{
							console.log(err);
						$('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create');
						},
						success:function(result){
							if(result==200){							
								alert_toast('Успешна регистрация',"success");
								location.reload();
							}else if(result==201){
								$('#signup-frm').prepend('<div class="alert alert-danger">Имейлът вече съществува</div>');
								end_load();
							} else if(result==204){
								alert_toast('Успешно редактирахте данните си',"success");
								location.reload();
							} else {
								$('#signup-frm').prepend('<div class="alert alert-danger">Грешка! Опитайте пак</div>');
								end_load();
							}					
						}
					});
				} else {
					$('#signup-frm').prepend('<div class="alert alert-danger">Паролите не съвпадат</div>');	
					end_load();
				}
			}
			else {
				$('#signup-frm').prepend('<div class="alert alert-danger">Имейлът е невалиден</div>');	
				end_load();
			}
		} else {
			$('#signup-frm').prepend('<div class="alert alert-danger">Попълни задължителните полета</div>');	
			end_load();
		}		
	});

	function IsEmail(email) {
        var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!filter.test(email)) {
           return false;
        }else{
           return true;
        }
    }
</script>