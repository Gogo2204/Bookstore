<?php 
    require 'admin/db_connect.php';
    session_start();

    if(isset($_GET['id'])){
    $query = $conn->query("SELECT books.*, publisher.appellation FROM books 
    INNER JOIN publisher ON books.publisher_id = publisher.id WHERE books.id = ".$_GET['id']);
    foreach($query->fetch_array() as $k => $val){
        $$k=$val;
    }

    $auth = array();
    $i = 0;
    $authors = $conn->query("SELECT books.*, authors.name FROM books INNER JOIN book_author ON books.id = book_author.book_id 
    INNER JOIN authors ON authors.id = book_author.author_id WHERE books.id = ".$_GET['id']);
    while($row_a=$authors->fetch_assoc()):         
            $auth[$i] = $row_a['name'];
            $i++;
    endwhile;
    $auths = implode(', ', $auth);

    $cat = array();
    $k = 0;
    $categories = $conn->query("SELECT books.*, categories.genre FROM books INNER JOIN book_category ON books.id = book_category.book_id 
    INNER JOIN categories ON categories.id=book_category.category_id WHERE books.id = ".$_GET['id']);
    while($row_c=$categories->fetch_assoc()):
        $cat[$k] = $row_c['genre'];
        $k++;
    endwhile;
    $cats = implode(', ', $cat);

    }
?>
<style type="text/css">
	
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    #qty{
        width: 50px;
        text-align: center
    }
</style>
<div class="container-fluid">
	<img src="admin/assets/uploads/<?php echo $image ?>" class="d-flex w-100" alt="">
	<p>Заглавие: <large><b><?php echo $title ?></b></large></p>
    <p>ISBN: <large><b><?php echo $isbn ?></b></large></p>
    <p>Автор/и: <b><?php echo $auths ?></b></p>
    <p>Издател: <b><?php echo $appellation ?></b></p>
	<p>Жанр: <b> <?php echo $cats; ?></b></p>           
    <p>Цена: <b><?php echo number_format($price,2) . ' лв.' ?></b></p>
	<p>Описание:</p>
	<p class=""><small><i><?php echo $description ?></i></small></p>
	<div class="d-flex jusctify-content-center col-md-12">
        <div class="d-flex col-sm-5">
            <span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
            <input type="number" name="qty" id="qty" value="1">
            <span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
        </div>
		<button class="btn btn-primary btn-block btn-sm col-sm-4" type="button" id="add_to_cart">Купи</button>
	</div>
</div>
<script>
    $('.btn-minus').click(function(){
            var qty = $(this).siblings('input').val();
                qty = qty > 1 ? parseInt(qty) - 1 : 1;
                $(this).siblings('input').val(qty).trigger('change');
    });
    
     $('.btn-plus').click(function(){
        var qty = $(this).siblings('input').val();
            qty = parseInt(qty) + 1;
            $(this).siblings('input').val(qty).trigger('change');
     });

    $('#add_to_cart').click(function(){        
        start_load();

        $.ajax({
            url:'admin/ajax.php?action=add_to_cart',
            method:'POST',
            data:{book_id: '<?php echo $id ?>',price: '<?php echo $price ?>', qty:$('#qty').val()},
            success:function(result){
                if(result==200){
                    alert_toast("Книгата е добавена в количката","success");
                    setTimeout(function(){
					location.reload();
					},250);
                }
            }
        });
    });	   
</script>
