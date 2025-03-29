<?php require 'db_connect.php'; ?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Всички книги</b>
						<span class="float:right"><a class="btn btn-primary btn-sm col-sm-3 float-right" href="javascript:void(0)" id="new_book">
			                    <i class="fa fa-plus"></i> Добави 
			                </a></span>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<colgroup>
								<col width="5%">
								<col width="15%">
								<col width="25%">						
								<col width="10%">
								<col width="5%">
								<col width="20%">
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Снимка</th>
									<th class="text-center">Описание</th>							
									<th class="text-center">Добавена на</th>
									<th class="text-center">Цена</th>
									<th class="text-center">Действие</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1; 								
								$book = $conn->query("SELECT * FROM books WHERE isDeleted = false");
								while($row=$book->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<div class="d-flex w-100">
					    					<div class="img-field mr-4 img-thumbnail rounded">
					    						<img src="assets/uploads/<?php echo $row['image'] ?>"  alt="" class="img-fluid rounded">
					    					</div>
										</div>
									</td>
									<td class="">
										<p>ISBN: <b><?php echo $row['isbn'] ?></b></p>
										<p>Заглавие: <b><?php echo $row['title'] ?></b></p>																				
										<p><small>Автор/и:
										<?php
										$author = $conn->query("SELECT authors.name FROM authors INNER JOIN book_author ON authors.id = book_author.author_id WHERE book_author.book_id =".$row['id']); 
										$auth = array();
										$k = 0;
										while($row1=$author->fetch_assoc()):
											$auth[$k] = $row1['name'];
											$k++;
										endwhile;
										$auths = implode(', ', $auth);
										?>
										<b><?php echo $auths ?></b></small></p>																																	
									</td>
									<td class="">
										<p><b><?php echo $row['created_at'] ?></b></p>
									</td>									
									<td class="">
										<p class="text-right"><b><?php echo number_format($row['price'],2) ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_book" type="button" data-id="<?php echo $row['id'] ?>">Редактиране</button>
										<button class="btn btn-sm btn-danger delete_book" type="button" data-id="<?php echo $row['id'] ?>">Изтриване</button>
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
	.custom-switch{
		cursor: pointer;
	}
	.custom-switch *{
		cursor: pointer;
	}
	.img-field{
		width: calc(100%);
		height: 15vh;
		overflow: hidden;
		display: flex;
		justify-content: center
	}

	.img-field img{
		max-width: 100%;
		max-height: 100%;
	}
</style>
<script>
	$('#new_book').click(function(){
		uni_modal("Добави книга","manage_book.php","mid-large");
	});
	$('.edit_book').click(function(){
		uni_modal("Редактирай данните за книгата","manage_book.php?id="+$(this).attr('data-id'),"mid-large");
	});	
	$('.delete_book').click(function(){
		_conf("Сигурен ли си, че искаш да изтриеш книгата?","delete_book",[$(this).attr('data-id')]);
	});

	function delete_book($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_book',
			method:'POST',
			data:{
				id:$id
			},
			success:function(result){
				if(result==200){
					alert_toast("Книгата е успешно изтрита",'success');
					setTimeout(function(){
						location.reload();
					},250);
				}
			}
		});
	}
	
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