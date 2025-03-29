<nav id="sidebar" class='mx-lt-5 bg-dark' >		
		<div class="sidebar-list">
				<a href="index.php?page=home" class="nav-item nav-home" onclick="this.style.backgroundColor=black"><span class='icon-field'><i class="fa fa-tachometer-alt "></i></span> Начална страница</a>						
				<a href="index.php?page=books" class="nav-item nav-books"><span class='icon-field'><i class="fa fa-book "></i></span> Книги</a>
				<a href="index.php?page=authors" class="nav-item nav-authors" ><span class='icon-field'><i class="fa fa-user"></i></span> Автори</a>
				<a href="index.php?page=categories" class="nav-item nav-categories" ><span class='icon-field'><i class="fa fa-list-alt"></i></span> Жанрове</a>
				<a href="index.php?page=publisher" class="nav-item nav-publisher" ><span class='icon-field'><i class="fa fa-newspaper"></i></span> Издатели</a>												
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users "></i></span> Потребители</a>	
				<a href="index.php?page=orders" class="nav-item nav-orders" ><span class='icon-field'><i class="fa fa-clipboard-list"></i></span> Поръчки</a>	
		</div>
</nav>

<style>
	.collapse a{
		text-indent:10px;
	}	
	nav#sidebar a:link {
		border-bottom: 2px solid white;
		color: #484343;
	}
	nav#sidebar a:hover{
		background-color: black;
		color: white;
	}
	nav#sidebar a:active{
		background-color: black;
		color: white;
	}		
</style>

<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'));
		$($(this).attr('href')).collapse();
	});
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active');
</script>
