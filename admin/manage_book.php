<?php require "db_connect.php" ?>
<?php
	$auths = "";
	$cats = "";
	if(isset($_GET['id'])){
		$query = $conn->query("SELECT * FROM books WHERE id = ".$_GET['id']);
		foreach($query->fetch_array() as $k => $val){
			$$k=$val;
		}
		
		$auth = array();
		$i = 0;
		$authors = $conn->query("SELECT authors.id FROM books INNER JOIN book_author ON books.id = book_author.book_id 
		INNER JOIN authors ON authors.id = book_author.author_id WHERE books.id = ".$_GET['id']);
		while($row_a=$authors->fetch_assoc()):         
				$auth[$i] = $row_a['id'];
				$i++;
		endwhile;
		$auths = implode(',', $auth);
		
		$cat = array();
		$k = 0;
		$categories = $conn->query("SELECT categories.id FROM books INNER JOIN book_category ON books.id = book_category.book_id 
		INNER JOIN categories ON categories.id=book_category.category_id WHERE books.id = ".$_GET['id']);
		while($row_c=$categories->fetch_assoc()):
			$cat[$k] = $row_c['id'];
			$k++;
		endwhile;
		$cats = implode(',', $cat);

		$pub = array();
		$j = 0;
		$publisher = $conn->query("SELECT publisher.id FROM books
		INNER JOIN publisher ON books.publisher_id = publisher.id WHERE books.id = ".$_GET['id']);
		while($row_p=$publisher->fetch_assoc()):
			$pub[$j] = $row_p['id'];
			$j++;
		endwhile;
		$pubs = implode(',', $pub);
	}
?>
<div class="container-fluid">
	<form action="" id="manage-book">
			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-12">
					<b class="text-muted">Данни за книгата</b>
			<div class="row">
				<div class="col-md-6 border-right">
					<div class="form-group">
						<label class="label control-label">Заглавие *</label>
						<input type="text" class="form-control form-control-sm w-100" id ="title" name="title" required="" value="<?php echo isset($title) ? $title : '' ?>">
					</div>
					<div class="form-group">
						<label class="label control-label">ISBN *</label>
						<input type="text" class="form-control form-control-sm w-100" id ="isbn" name="isbn" required="" value="<?php echo isset($isbn) ? $isbn : '' ?>">
					</div>
					<div class="form-group">
						<label class="label control-label">Автор/и *</label>
						<select id="author" name="authors[]" class="custom-select custom-select-sm select2" required="" multiple="multiple">
							<option value=""></option>
							<?php
							if(isset($auths) && !empty($auths)){
								$author = $conn->query("SELECT * FROM authors ORDER BY name ASC");
							} else {								
								$author = $conn->query("SELECT * FROM authors WHERE isDeleted = false ORDER BY name ASC");
							}					
							while($row= $author->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($auths) && !empty($auths) && in_array($row['id'],explode(',',$auths)) ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>							
						</select>
					</div>
					<div class="form-group">
						<label class="label control-label">Описание *</label>
						<textarea id="description" name="description" cols="30" rows="3" class="form-control" required=""><?php echo isset($description) ? $description : '' ?></textarea>
					</div>								
					<div class="form-group">
						<label class="label control-label">Жанр *</label>
						<select id="category" name="categories[]" class="custom-select custom-select-sm select2" required multiple="multiple">
							<option value=""></option>
							<?php
							if(isset($cats) && !empty($cats)){
								$category = $conn->query("SELECT * FROM categories ORDER BY genre ASC");
							} else {
								$category = $conn->query("SELECT * FROM categories WHERE isDeleted = false ORDER BY genre ASC");
							}																		
							while($row= $category->fetch_assoc()):	
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($cats) && !empty($cats) &&  in_array($row['id'],explode(',',$cats)) ? 'selected' : '' ?>><?php echo ucwords($row['genre']) ?></option>
						<?php endwhile; ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="label control-label">Издател *</label>											
						<select id="publisher" name="publisher"  class="form-control required">
							<option value=""></option>
							<?php
							if(isset($pubs) && !empty($pubs)){
								$publishers = $conn->query("SELECT * FROM publisher ORDER BY appellation ASC");
							} else {
								$publishers = $conn->query("SELECT * FROM publisher WHERE isDeleted = false ORDER BY appellation ASC");
							}							
							while($row=$publishers->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($pubs) && !empty($pubs) && in_array($row['id'],explode(',',$pubs)) ? 'selected' : '' ?>><?php echo ucwords($row['appellation']) ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label class="label control-label">Година</label>
						<input type="number" min="1960" max="2100" class="form-control form-control-sm w-100" id="year" name="year" required="" value="<?php echo isset($year) ? $year : '' ?>">
					</div>
					<div class="form-group">
						<label class="label control-label">Цена *</label>
						<input type="number" placeholder="0.00" pattern="^\d*(\.\d{0,2})?$" class="form-control form-control-sm w-100 text-right number text-right" id="price" name="price" required="" value="<?php echo isset($price) ? $price : '' ?>">
					</div>
					<div class="form-group">
						<label  class="control-label">Изображение</label>
						<input type="file" class="form-control" id="cover" name="cover" onchange="displayImg(this,$(this))" accept="image/*">	
					</div>
					<div class="form-group">
						<p><?php echo isset($image) ? 'Ако не искате да променяте изображението оставете полето празно' : '' ?></p>
					</div>
					<div class="form-group">
						<img src="<?php echo isset($image) ? 'assets/uploads/' . $image :'' ?>" alt="" id="cimg">
					</div>					
					<div class="form-group">
						<input type="submit" id="btn-save" class="btn btn-primary" value="<?php echo !isset($_GET['id']) ? "Запази" : "Обнови" ?>" style="position:absolute; top: 90%; left: 10%; width: 40%;">
						<input type="button" id="btn-cancel" class="btn btn-danger cancel" value="Отмени" style="position:absolute; top: 90%; left: 60%; width: 40%;">
					</div>
					<div id="msg" class="form-group"></div>
				</div>
			</div>
		</div>
	</form>
</div>
<style>
	img#cimg{
		max-height: 10vh;
		max-width: 6vw;
	}	
</style>
<script>
	$('form').attr('novalidate', '');

	$('.select2').select2({
		placeholder:"Изберете от тук",
		width:'100%'
	});

	$('.number').on('input',function(){
        var val = $(this).val();
        val = val.replace(/,/g,'');
        val = val > 0 ? val : 0;
        $(this).val(parseFloat(val).toLocaleString("en-US"));
    });

	$('.cancel').click(function(){
		setTimeout(function(){
			location.reload();
			},50);
	});

	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$(document).ready(function(){
		$('#btn-save').unbind().bind('click', function(e){
			e.preventDefault();

			var title = $('#title').val();
			var isbn = $('#isbn').val();	
			var description = $('#description').val();			
			var authors = $('#author').val();
			var categories = $('#category').val();
			var publisher = $('#publisher').val();
			var price = $('#price').val();

			var form = $('form')[0];
            var formData = new FormData(form);

            formData.append('cover', $('input[type=file]')[0].files[0]);

			if(title != "" && isbn != "" && authors != "" && description != "" && categories != "" && publisher != "" && price != ""){
				$.ajax({
					url:'ajax.php?action=save_book',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',
					type: 'POST',
					error:err=>{
						console.log(err);
					},
					success:function(result){																															
						if(result==200){								
							alert_toast('Книгата е успешно добавена',"success");
							setTimeout(function(){
								location.reload();
							},250);
						} else if(result==204){							
							alert_toast('Данните за книгата са редактирани',"success");
							setTimeout(function(){
								location.reload();
							},250);
						} else {
							switch(result){
									case '1':
										$('#msg').html('<div class="alert alert-danger">Разширението трябва да бъде .jpg, .jpeg или .png</div>');
										end_load();	
										break;
									case '2':
										$('#msg').html('<div class="alert alert-danger">Изображението е твърде голямо</div>');
										end_load();	
										break;
									case '3':
										$('#msg').html('<div class="alert alert-danger">Проблем при качването на файла</div>');
										end_load();	
										break;
							}
						}						
					}
				});
			} else {
				$('#msg').html('<div class="alert alert-danger">Попълни задължителните полета</div>');	
				end_load();
			}
		});
	});

	$(document).on('keydown', 'input[pattern]', function(e){
		var input = $(this);
		var oldVal = input.val();
		var regex = new RegExp(input.attr('pattern'), 'g');

		setTimeout(function(){
			var newVal = input.val();
			if(!regex.test(newVal)){
			input.val(oldVal); 
			}
		}, 1);
	});
</script>