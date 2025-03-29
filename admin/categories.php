<?php require 'db_connect.php'; ?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-category">
				<div class="card">
					<div class="card-header">
						    Добави жанр
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id" id="id">
							<div id="msg" class="form-group"></div>
							<div class="form-group">
								<label class="control-label">Име *</label>
								<input type="text" class="form-control" id="genre" name="genre">
							</div>
							<div class="form-group">
								<label class="control-label">Описание</label>
								<textarea name="description" id="description" cols="30" rows="4" class="form-control"></textarea>
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3">Добави</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-category').get(0).reset()">Отмени</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Списък с жанровете</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Данни за жанра</th>
									<th class="text-center">Действие</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$category = $conn->query("SELECT * FROM categories WHERE isDeleted = false ORDER BY genre ASC");
								while($row=$category->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p>Жанр: <b><?php echo $row['genre'] ?></b></p>
										<p><small>Описание: <b><?php echo $row['description'] ?></b></small></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_category" type="button" data-id="<?php echo $row['id'] ?>" data-description="<?php echo $row['description'] ?>" data-name="<?php echo $row['genre'] ?>" >Редактиране</button>
										<button class="btn btn-sm btn-danger delete_category" type="button" data-id="<?php echo $row['id'] ?>">Изтриване</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->

		</div>
	</div>	
</div>
<style>
	td{
		vertical-align: middle !important;
	}
	td p {
		margin:unset;
	}
</style>
<script>
	$('#manage-category').on('reset',function(){
		$('input:hidden').val('');
	});
	
	$('#manage-category').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');
		
		var genre = $('#genre').val();

		if(genre != ""){
			$.ajax({
				url:'ajax.php?action=save_category',
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				success:function(result){				
					if(result==200){
						alert_toast("Жанрът е успешно добавен",'success');
						setTimeout(function(){
							location.reload();
						},250);					
					} else if(result==204){
						alert_toast("Данните за жанра са редактирани",'success');
						setTimeout(function(){
							location.reload();
						},250);
					} else if(result==205){
						$('#msg').html('<div class="alert alert-danger">Данните за жанра не са редактирани</div>');
						end_load();
					}
				}
			});
		} else {
			$('#msg').html('<div class="alert alert-danger">Попълни задължителните полета</div>');	
			end_load();
		}		
	});

	$('.delete_category').click(function(){
		_conf("Сигурен ли си, че искаш да изтриеш този жанр?","delete_category",[$(this).attr('data-id')]);
	});
	function delete_category($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_category',
			method:'POST',
			data:{id:$id},
			success:function(result){
				if(result==200){
					alert_toast("Жанрът е успешно изтрит",'success');
					setTimeout(function(){
						location.reload();
					},250);
				}
			}
		});
	}

	$('.edit_category').click(function(){
		start_load();
		var cat = $('#manage-category');
		cat.get(0).reset();
		cat.find("[name='id']").val($(this).attr('data-id'));
		cat.find("[name='genre']").val($(this).attr('data-name'));
		cat.find("[name='description']").val($(this).attr('data-description'));
		end_load();
	});	
	
	$('table').dataTable({
		"language":{
			"lengthMenu": "Покажи _MENU_ резултата",
			"zeroRecords": "Няма намерени резултати",
			"info": "Показва _PAGE_ от _PAGES_ страници",
			"infoEmpty": "Няма налични записи",
			"search": "Търси: ",
			"paginate": {
				"next": "Следваща",
				"previous": "Предишна",
			}			
		}
	});
</script>