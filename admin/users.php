<?php require 'db_connect.php'; ?>

<div class="container-fluid">	
	<div class="col-lg-12">
		<div class="card ">
			<div class="card-header"><b>Списък с потребителите</b></div>
			<div class="card-body">
				<table class="table-striped table-bordered">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Потребителско име</th>
					<th class="text-center">Имейл</th>
					<th class="text-center">Роля</th>				
					<th class="text-center">Създаден на</th>	
				</tr>
			</thead>
			<tbody>
				<?php 					
 					$type = array("","Admin","Staff","Alumnus/Alumna");
 					$users = $conn->query("SELECT * FROM users order by username asc");
 					$i = 1;
 					while($row=$users->fetch_assoc()):
				 ?>
				 <tr>
				 	<td class="text-center">
				 		<?php echo $i++ ?>
				 	</td>
				 	<td>
				 		<?php echo $row['username'] ?>
				 	</td>
				 	
				 	<td>
				 		<?php echo $row['email'] ?>
				 	</td>
				 	<td>
				 		<?php echo $row['role'] ?>
				 	</td>
					 <td>
				 		<?php echo $row['created_at'] ?>
				 	</td>				 	
				 </tr>
				<?php endwhile; ?>
			</tbody>
		</table>
			</div>
		</div>
	</div>
</div>
<script>
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