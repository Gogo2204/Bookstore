<?php require 'admin/db_connect.php'; ?>

<div class="container-fluid m-3">	
	<div class="col-lg-10 offset-md-1">
		<div class="row">
			<div class="col-md-8">    	
					<ul class="list-group">
					<?php
					if (isset($_SESSION["products"]) && count($_SESSION["products"]) > 0):
					$cart_box = '<ul class="cart-products-loaded">';
					$total = 0;
					foreach ($_SESSION["products"] as $product){
						$product_name = $product["title"];
						$product_price = $product["price"];
						$product_id = $product["book_id"];
						$product_qty = $product["qty"];
						$subtotal = ($product_price * $product_qty);
						$total = ($total + $subtotal); 
					?>					
						<li class="list-group-item" data-id="<?php echo $product_id ?>" data-price="<?php echo $product_price ?>">
							<div class="d-flex w-100">
								<?php
								$images = $conn->query("SELECT image FROM books WHERE id = " . $product_id);
								while($rowI=$images->fetch_assoc()): 
								?>
								<div class="img-field mr-4 img-thumbnail rounded">
									<img src="admin/assets/uploads/<?php echo $rowI['image'] ?>"  alt="" class="img-fluid rounded">
								</div>
								<?php endwhile; ?>
								<div class="detail-field">
									<p>Книга: <b><?php echo $product_name ?></b></p>    					
									<p>Цена: <b><?php echo number_format($product_price,2) . ' лв.' ?></b></p>
									<div class="d-flex col-sm-5">
										<span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
										<input type="number" name="qty" id="" class="form-control form-control-sm qty-input" readonly value="<?php echo $product_qty ?>">
										<span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
									</div>
								</div>
								<div class="amount-field">
									<b class="amount"><?php echo number_format($subtotal,2)  . ' лв.'?></b>
								</div>
							<span class="float-right"><button class="btn btn-sm btn-outline-danger rem_item" type="button"  data-id="<?php echo $product_id ?>"><i class="fa fa-trash"></i></button></span>
							</div>
						</li>
					<?php } ?>
					</ul>
				<?php else: $total=0; ?>
					<center><h3 style="padding-top:80px;"><b>Количката ви е празна</b></h3></center>
				<?php endif; ?>			
			</div>						
			<div class="col-md-4">
				<div class="card mb-4">
					<div class="card-header bg-primary text-white"><b>Обща сума: </b></div>
					<div class="card-body">
						<h4 class="text-right"><b id="tamount"><?php echo number_format($total,2)  . ' лв.'?></b></h4>
					</div>
				</div>
				<button class="btn btn-block btn-primary" onclick="location.href='http://localhost/bookstore/index.php?page=checkout'" type="button" <?php echo $_SESSION["qty1"] > 0 ? '' : 'disabled'; ?>> Към завършване на поръчката</button>
			</div>
		</div>								
	</div>
</div>

<style type="text/css">
	.img-field{
		width: calc(25%);
		max-height: 25vh;
		overflow: hidden;
		display: flex;
		justify-content: center
	}
	.detail-field{
		width: calc(50%);
	}
	.amount-field{
		width: calc(25%);
		text-align:right;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.img-field img{
		max-width: 100%;
		max-height: 100%;
	}
	.qty-input{
		width: 75px;
		text-align: center; 
	}

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>
<script>
	$('.btn-minus').click(function(){
		var qty = $(this).siblings('input').val();	;
		if(qty>1){
			start_load();
			qty = qty > 1 ? parseInt(qty) - 1 : 1;
			var input = $(this).siblings('input')
			var id = $(this).closest('li').attr('data-id')
			$.ajax({
				url:'admin/ajax.php?action=decrease_quantity_in_cart',
				method:'POST',
				data:{book_id:id},
				success:function(result){
					if(result==200){
						input.val(qty).trigger('change');
						calc();
						setTimeout(function(){
						location.reload();
						},250);
					}
				}
			});
		}
     });

     $('.btn-plus').click(function(){
		start_load();
        var qty = $(this).siblings('input').val();
            qty = parseInt(qty) + 1;
        var input = $(this).siblings('input');
        var id = $(this).closest('li').attr('data-id');
        $.ajax({
        	url:'admin/ajax.php?action=increase_quantity_in_cart',
        	method:'POST',
        	data:{book_id:id},
        	success:function(result){
        		if(result==200){
            		input.val(qty).trigger('change');
            		calc();
					setTimeout(function(){
					location.reload();
					},250);
        		}
        	}
        });
     });

     function calc(){
     	$('.qty-input').each(function(){
     		var li = $(this).closest('li');
     		var price = li.attr('data-price');
     		var qty = $(this).val();
     		var amount = parseFloat(qty) * parseFloat(price);
     		li.find('.amount').text(parseFloat(amount).toLocaleString('en-US',{style:"decimal",maximumFractionDigits:2,minimumFractionDigits:2}));
     	});

     	var total = 0;
     	$('.amount').each(function(){
     		var amount = $(this).text();
     			amount = amount.replace(/,/g,'');
     			total += parseFloat(amount);
     	});
     	$('#tamount').text(parseFloat(total).toLocaleString('en-US',{style:"decimal",maximumFractionDigits:2,minimumFractionDigits:2}));
     }

     $('.rem_item').click(function(){
     	_conf("Сигурен ли си, че искаш да премахнеш книгата от количката?","delete_cart",[$(this).attr('data-id')]);
     });
	 
     function delete_cart($id){
     	start_load();
        $.ajax({
            url:'admin/ajax.php?action=remove_from_cart',
            method:'POST',
            data:{book_id:$id},
            success:function(result){
                if(result==200){
                    alert_toast("Книгата е премахната от количката","success");
                    setTimeout(function(){
					location.reload();
					},250);
                }
            }
        });
     }
</script>
