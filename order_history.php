<?php 
require "admin/db_connect.php";
require "header.php";
require "assets/system/helpers.php"; 

if(isset($_SESSION['login_id'])){
	$customer = $conn->query("SELECT * FROM customers WHERE user_id =".$_SESSION['login_id']);
	$hasOrders=false;
    if($customer->num_rows>0){
		$data=$customer->fetch_assoc();
        $hasOrders=true;
	}
}
?>

<section class="section">
    <div class="container-fluid">
        <?php if($hasOrders): ?>
        <div class="col-lg-12">
            <div class="row">                            
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <b>Направени поръчки</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Снимка</th>
                                        <th class="text-center">Заглавие</th>
                                        <th class="text-center">Цена</th>
                                        <th class="text-center">Количество</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php									
                                    $i = 1;
                                    $orders = $conn->query("SELECT * FROM orders WHERE customer_id=".$data['id']);
                                    while($row=$orders->fetch_assoc()):
                                        $book = $conn->query("SELECT title, image, price FROM books WHERE id=".$row['book_id']);
                                        while($row_b=$book->fetch_assoc()):								
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="">
                                            <div class="d-flex w-100">
                                                <div class="img-field mr-4 img-thumbnail rounded">
                                                    <img src="admin/assets/uploads/<?php echo $row_b['image'] ?>"  alt="" class="img-fluid rounded">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo $row_b['title'] ?></td>
                                        <td class="text-center"><?php echo number_format($row_b['price'],2) . ' лв.' ?></td>
                                            <?php endwhile; ?>
                                        <td class="text-center"><?php echo $row['qty'] ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div clas="col-md-4">                
                    <div class="card">
                        <div class="card-header">
                            <b>Данни за доставка</b>
                        </div>
                        <div class="card-body">
                            <form action="" id="customer">
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
                                    <div class="float-center" >
                                        <button class="btn btn-block btn-primary" id="edit" type="submit">Редактирай</button>
                                    </div>                                					
                                </div>			
                            </form>						
                        </div>
                    </div>                
                </div>
            </div>
        </div>
        <?php else: ?>
            <center><h3 style="padding-top:80px; padding-bottom:80px;"><b>Нямате направени поръчки</b></h3></center>
        <?php endif; ?>
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

  $('#customer').submit(function(e){
    e.preventDefault();	
    start_load();

    var name = $('#name').val();
    var phone = $('#phone').val();
    var email = $('#email').val();		
    var select = document.getElementById('town');
    var town = select.options[select.selectedIndex].text;
    var address = $('#address').val();          

    if(name != "" && phone != "" && email != "" && town != "" && address != ""){
      if(IsEmail(email)){
        if(IsPhone(phone)){
          $.ajax({
            url: 'admin/ajax.php?action=edit_customer',
            method:'POST',
            data:{name:name, phone:phone, email:email, town:town, address:address},
            error:err=>{
              console.log(err);
            end_load();
            },
            success:function(result){					
              if(result==200){
                alert_toast("Данните са редактирани успешно","success");
                setTimeout(function(){
                location.reload();
                },250);
              } else {
                $('#customer').prepend('<div class="alert alert-danger">Грешка. Опитайте пак</div>');						
                end_load();
              }
            }
          });
        } else {
          $('#customer').prepend('<div class="alert alert-danger">Телефонът е невалиден</div>');
          end_load();
        }
      } else {
        $('#customer').prepend('<div class="alert alert-danger">Имейлът е невалиден</div>');
        end_load();
      }
    } else {
      $('#customer').prepend('<div class="alert alert-danger">Попълни задължителните полета</div>');
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

  function IsPhone(phone){		
    var filter = /^([+]?359)|0?(|-| )8[789]\d{1}(|-| )\d{3}(|-| )\d{3}$/;
		if(filter.test(phone)){
			return true;
		} else {
			return false;
		}
	}
</script>