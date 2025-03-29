<?php require 'db_connect.php'; ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Списък с поръчки</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<colgroup>
								<col width="5%">
								<col width="15%">
								<col width="20%">
								<col width="20%">
								<col width="15%">																		
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Дата</th>
									<th class="text-center">Клиент</th>
									<th class="text-center">Книга</th>
									<th class="text-center">Количество</th>																											
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$orders = $conn->query("SELECT orders.*, customers.name, customers.tel, books.title, books.isbn FROM orders INNER JOIN customers ON orders.customer_id = customers.id INNER JOIN books ON orders.book_id = books.id ORDER BY orders.purchase_date DESC");
								while($row=$orders->fetch_assoc()):									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo date("M d,Y",strtotime($row['purchase_date'])) ?></b></p>
									</td>
									<td class="">
										<p><b><?php echo 'Име: ' . ucwords($row['name']) ?></b></p>
										<p><b><?php echo 'Телефон: ' . ucwords($row['tel']) ?></b></p>
									</td>
									<td class="">
										<p><b><?php echo ucwords($row['title'])?></b></p>
										<p><b><?php echo 'ISBN: '. ucwords($row['isbn'])?></b></p>
									</td>
									<td class="">
										<p class="text-center"><b><?php echo ucwords($row['qty']) ?></b></p>
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
	.img{
		max-height: 15vh;
	}
</style>
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
	})
</script>