<?php require 'db_connect.php'; ?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">			
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-publisher">
				<div class="card">
					<div class="card-header">
						    Добави издател
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id" id="id">
							<div id="msg" class="form-group"></div>
							<div class="form-group">
								<label class="control-label">Име *</label>
								<input type="text" class="form-control" name="appellation">
							</div>					
					</div>							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Добави</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-publisher').get(0).reset()"> Отмени</button>
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
						<b>Списък с издатели</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Данни за издателя</th>
									<th class="text-center">Действие</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$publisher = $conn->query("SELECT * FROM publisher WHERE isDeleted = false ORDER BY appellation ASC");
								while($row=$publisher->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p>Издател: <b><?php echo $row['appellation'] ?></b></p>										
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_publisher" type="button" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['appellation'] ?>">Редактиране</button>
										<button class="btn btn-sm btn-danger delete_publisher" type="button" data-id="<?php echo $row['id'] ?>">Изтриване</button>
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
	$('#manage-publisher').on('reset',function(){
		$('input:hidden').val('');
	});

	$('#manage-publisher').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');
		
		var appellation = $('#appellation').val();

		if(appellation != ""){
			$.ajax({
				url:'ajax.php?action=save_publisher',
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				success:function(result){					
					if(result==200){
						alert_toast("Издателят е успешно добавен",'success');
						setTimeout(function(){
							location.reload();
						},250);					
					} else if(result==204){
						alert_toast("Данните за издателя са редактирани",'success');
						setTimeout(function(){
							location.reload();
						},250);
					} else if(result==205){
						$('#msg').html('<div class="alert alert-danger">Данните за издателя не са редактирани</div>');
						end_load();
					}
				}
			});
		} else {
			$('#msg').html('<div class="alert alert-danger">Попълни задължителните полета</div>');	
			end_load();
		}
	});

	$('.delete_publisher').click(function(){
		_conf("Сигурен ли си, че искаш да изтриеш издателя?","delete_publisher",[$(this).attr('data-id')]);
	});
	function delete_publisher($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_publisher',
			method:'POST',
			data:{id:$id},
			success:function(result){
				if(result==200){
					alert_toast("Издателят е успешно изтрит",'success');
					setTimeout(function(){
						location.reload();
					},250);
				}
			}
		});
	}
	
	$('.edit_publisher').click(function(){
		start_load();
		var pub = $('#manage-publisher');
		pub.get(0).reset();
		pub.find("[name='id']").val($(this).attr('data-id'));
		pub.find("[name='appellation']").val($(this).attr('data-name'));	
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