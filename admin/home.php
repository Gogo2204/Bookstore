<?php require 'db_connect.php' ?>

<div class="containe-fluid">
	<div class="row mt-2 ml-2 mr-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Добре дошли, ". $_SESSION['login_admin_name']."!"  ?>
                    <hr>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 p2">
                            	<div class="card bg-light">
	                            	<div class="card-body">
		                                <span class="float-right summary_icon"> <i class="fa fa-book text-muted "></i></span>
		                                <h4><b>
		                                    <?php echo $conn->query("SELECT * FROM books WHERE isDeleted = false")->num_rows ?>
		                                </b></h4>
		                                <p><b>Брой книги</b></p>
	                                </div>
                                </div>
                            </div>
                            <div class="col-md-4 p2">
                            	<div class="card bg-light">
	                            	<div class="card-body">
		                                <span class="float-right summary_icon"> <i class="fa fa-funnel-dollar text-muted "></i></span>
		                                <h4><b>
		                                    <?php echo $conn->query("SELECT * FROM orders")->num_rows ?>
		                                </b></h4>
		                                <p><b>Брой поръчки</b></p>
	                                </div>
                                </div>
                            </div>
                            <div class="col-md-4 p2">
                            	<div class="card bg-light">
	                            	<div class="card-body">
		                                <span class="float-right summary_icon"> <i class="fa fa-user text-muted"></i></span>
		                                <h4><b>
		                                    <?php echo $conn->query("SELECT * FROM users")->num_rows ?>
		                                </b></h4>
		                                <p><b>Брой потребители</b></p>
	                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      			
        </div>
    </div>
</div>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
	.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}

	.row mt-2 ml-2 mr-2 {display: flex;}
	.column {
  float: left;
}

.left {
  width: 25%;
}

.right {
  width: 75%;
}
</style>