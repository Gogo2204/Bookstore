<?php require 'db_connect.php'; ?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">

			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-author">
				<div class="card">
					<div class="card-header">
						    Добави автор
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id" id="id">
							<div id="msg" class="form-group"></div>
							<div class="form-group">
								<label class="control-label">Име *</label>
								<input type="text" class="form-control" name="name" id="name">
							</div>	
                            <div class="form-group">
								<label class="control-label">Биография</label>
								<textarea name="bio" id="bio" cols="30" rows="4" class="form-control"></textarea>
							</div>				 
					</div>							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Добави</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-author').get(0).reset()"> Отмени</button>
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
						<b>Списък с автори</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Данни за автора</th>
									<th class="text-center">Действие</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$authors = $conn->query("SELECT * FROM authors WHERE isDeleted=false ORDER BY name ASC");
								while($row=$authors->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p>Име: <b><?php echo $row['name'] ?></b></p>
                                        <p><small>Биография: <b><?php echo $row['bio'] ?></b></small></p>										
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_author" type="button" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['name'] ?>" data-bio="<?php echo $row['bio'] ?>">Редактиране</button>
										<button class="btn btn-sm btn-danger delete_author" type="button" data-id="<?php echo $row['id'] ?>">Изтриване</button>
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
	$('#manage-author').on('reset',function(){
		$('input:hidden').val('');
	});

	$('#manage-author').submit(function(e){
			e.preventDefault();
			start_load();
			$('#msg').html('');
			
			var name = $('#name').val();

			if(name != ""){				
				$.ajax({
					url:'ajax.php?action=save_author',
					data: new FormData($(this)[0]),
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',
					type: 'POST',
					success:function(result){						
						if(result==200){
							alert_toast("Авторът е успешно добавен",'success');
							setTimeout(function(){
								location.reload();
							},250);						
						} else if(result==204){
							alert_toast("Данните за автора са редактирани",'success');
							setTimeout(function(){
								location.reload();
							},250);
						} else if(result==205){
							$('#msg').html('<div class="alert alert-danger">Данните за автора не са редактирани</div>');
							end_load();
						}											
					}	
				});
			} else {
				$('#msg').html('<div class="alert alert-danger">Попълни задължителните полета</div>');	
				end_load();			
			}
	});

	$('.delete_author').click(function(){
		_conf("Сигурен ли си, че искаш да изтриеш автора?","delete_author",[$(this).attr('data-id')]);
	});
	function delete_author($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_author',
			method:'POST',
			data:{id:$id},
			success:function(result){
				if(result==200){
					alert_toast("Автора е успешно изтрит",'success');
					setTimeout(function(){
						location.reload();
					},250);
				}
			}
		});
	}

	$('.edit_author').click(function(){		
		var auth = $('#manage-author');				
		auth.get(0).reset();
		auth.find("[name='id']").val($(this).attr('data-id'));
		auth.find("[name='name']").val($(this).attr('data-name'));
		auth.find("[name='bio']").val($(this).attr('data-bio'));		
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