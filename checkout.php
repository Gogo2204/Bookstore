<?php
require "admin/db_connect.php";
require "header.php";
require "assets/system/helpers.php";

if(isset($_SESSION['login_id'])){
	$customer = $conn->query("SELECT * FROM customers WHERE user_id =".$_SESSION['login_id']);
	if($customer->num_rows>0){
		$data=$customer->fetch_assoc();
	}
}              
?>

<section class="section">       
	<div class="container-fluid">
		<h1>Завършване на поръчката</h1>
		<div class="col-lg-12">
			<div class="row-checkout">
				<?php if(!isset($_SESSION['login_id'])): ?>
				<div class="col-md-4" style="padding-bottom: 30px;">
					<div class="card">
						<div class="card-header">
							<b>Влез</b>
						</div>
						<div class="card-body">
							<form action="" id="login-frm-checkout">
								<div class="form-group">
									<label for="" class="control-label">Имейл *</label>
									<input type="text" name="email_ch" id="email_ch" class="form-control">
								</div>
								<div class="form-group">
									<label for="" class="control-label">Парола *</label>
									<input type="password" name="password_ch" id="password_ch" class="form-control">
									<p style="padding-top:10px;"><input type="checkbox" onclick="showPassword()">Покажи паролата</p>
									<a href="javascript:void(0)" id="new_account">Нямаш профил? Регистрирай се тук!</a>
								</div>
								<button class="button btn btn-primary btn-sm">Влез</button>
								<button class="button btn btn-secondary btn-sm" type="button" data-dismiss="modal">Отмени</button>
							</form>
						</div>
					</div>					
				</div>
				<?php endif; ?>						
				<div class="<?php echo isset($_SESSION['login_id']) ? "col-md-8" : "col-md-4" ?>">
					<div class="card">
						<div class="card-header">
							<b>Списък с покупките</b>
						</div>
						<div class="card-body">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-center">Заглавие</th>
										<th class="text-center">Количество</th>
										<th class="text-center">Сума</th>
									</tr>
								</thead>
								<tbody>
									<?php									
									$i = 1;
									$total = 0;
									foreach ($_SESSION["products"] as $product){
										$title = $product["title"];
										$price = $product["price"];											
										$product_id = $product["book_id"];
										$qty = $product["qty"];
										$subtotal = ($price * $qty);
										$total = ($total + $subtotal);								
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class=""><?php echo $title ?></td>
										<td class="text-center"><?php echo $qty ?></td>
										<td class="text-center"><?php echo number_format($subtotal,2) . ' лв.' ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<h3><?php echo 'Общо: ' . number_format($total,2)  . ' лв.'  ?></h3>					
				</div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-header">
							<b>Данни за доставка</b>
						</div>
						<div class="card-body">
							<form action="" id="checkout">
								<div class="col-50">					
									<label for="name" style="padding-top: 20px;"><i class="fa fa-user" ></i> Име и фамилия *</label>
									<input type="text" id="name" class="form-control" name="name" value="<?php echo isset($data['name']) ? $data['name'] : '' ?>">

									<label for="phone"><i class="fa fa-phone"></i> Телефон *</label>
									<input type="text" id="phone" name="phone" class="form-control" value="<?php echo isset($data['tel']) ? $data['tel'] : '' ?>">
									
									<label for="email"><i class="fa fa-envelope"></i> Имейл *</label>
									<input type="text" id="email" name="email" class="form-control" value="<?php echo isset($data['email']) ? $data['email'] : '' ?>">
									
									<label for="town"><i class="fa fa-city"></i> Населено място *</label>
									<select id="town" name="town"  class="custom-select custom-select-sm select2">
										<option value=""></option>
										<?php											
											foreach($cities as $city){ ?>
												<option value="<?php echo $data['city'] ?>" <?php echo isset($data['city']) && $data['city']==$city ? 'selected' : '' ?>><?php echo $city; ?></option>
										<?php } ?>										
									</select>									

									<label for="adr" style="padding-top: 20px;"><i class="fa fa-address-card"></i> Адрес *</label>
									<input type="text" id="address" name="address" class="form-control" value="<?php echo isset($data['address']) ? $data['address'] : '' ?>">	
									<div class="float-right" >
										<button class="btn btn-block btn-primary" id="finish_order" type="submit">Изпрати поръчката</button>
									</div>
									<div class="float-left" >
										<button class="btn btn-block btn-danger" onclick="location.href='http://localhost/bookstore/index.php?page=cart'" type="button">Върни се към количката</button>
									</div>					
								</div>			
							</form>						
						</div>
					</div>
				</div>				
			</div>					
		</div>		
	</div>
</section>
		
<?php require "footer.php"; ?>

<style>
h1 {
	text-align: center;
	padding-top: 10px;
	padding-bottom: 20px;
}

h3 {
	text-align: center;
	padding-top: 20px;
}

.row-checkout {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%;
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%;
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%;
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container-checkout {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.checkout-btn {
  background-color: #008cb8;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.checkout-btn:hover {
  background-color: #008cb8;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}

@media (max-width: 800px) {
  .row-checkout {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>
<script>
	$('.select2').select2({
		placeholder:"Изберете от тук",
		width:'100%'
	});

	function showPassword() {
		var x = document.getElementById("password");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}

	function IsPhone(phone){		
    var filter = /^([+]?359)|0?(|-| )8[789]\d{1}(|-| )\d{3}(|-| )\d{3}$/;
		if(filter.test(phone)){
			return true;
		} else {
			return false;
		}
	}

	function IsEmail(email){
        var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!filter.test(email)) {
           return false;
        }else{
           return true;
        }
    }

	$('#new_account').click(function(){
		uni_modal("Регистрирай се",'signup.php?redirect=index.php?page=checkout');
	});

	$('#checkout').submit(function(e){
		e.preventDefault();
		start_load();

		var name = $('#name').val();
		var phone = $('#phone').val();
		var email = $('#email').val();
		var town = $('#town').val();
		var select = document.getElementById('town');
        var town = select.options[select.selectedIndex].text;
		var address = $('#address').val();

		if(name != "" && phone != "" && email != "" && town != "" && address != ""){
			if(IsEmail(email)){
				if(IsPhone(phone)){
					$.ajax({
						url: 'admin/ajax.php?action=make_order',
						method:'POST',
						data: {name:name, phone:phone, email:email, town:town, address:address},
						error:err=>{
							console.log(err);
						end_load();
						},
						success:function(result){					
							if(result==200){
								alert_toast("Поръчката е изпратена!","success");
								location.href ='index.php?page=thank_you';
							} else {
								$('#checkout').prepend('<div class="alert alert-danger">Грешка при изпращането на поръчката</div>');						
								end_load();
							}
						}
					});
				} else {
					$('#checkout').prepend('<div class="alert alert-danger">Телефонът е невалиден</div>');						
					end_load();
				}
			} else {
				$('#checkout').prepend('<div class="alert alert-danger">Имейлът е невалиден</div>');						
				end_load();
			}
		} else {
			$('#checkout').prepend('<div class="alert alert-danger">Попълни задължителните полета</div>');
			end_load();
		}
	});

	$('#login-frm-checkout').submit(function(e){
		e.preventDefault();
		start_load();

		var email = $('#email_ch').val();
		var password = $('#password_ch').val();
		
		if(email != "" && password != ""){
			$.ajax({
				url:'admin/ajax.php?action=login_user',
				method:'POST',
				data:{email:email, password:password},
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