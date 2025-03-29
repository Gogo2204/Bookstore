<?php require 'admin/db_connect.php'; ?>

<div class="contain-fluid">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Категории</div>
                    <div class="card-body">
                        <ul class='list-group' id='cat-list'>
                            <li class='list-group-item' id='01' data-href="index.php?page=home&id=-1">Най-продавани</li>
                            <li class='list-group-item' id='02' data-href="index.php?page=home&id=-2">Най-скъпи</li>
                            <li class='list-group-item' id='03' data-href="index.php?page=home&id=-3">Най-актуални</li>                                                        
                        </ul>
                    </div>
                </div>
                <?php 
                $images = $conn->query("SELECT image FROM books WHERE image != 'noPhoto.png' ORDER BY year DESC LIMIT 10");
                while($rowI=$images->fetch_assoc()):
                ?>
                <div class="slideshow-container" style="padding-top: 20px;">
                    <div class="mySlides fade">          
                        <img src="admin/assets/uploads/<?php echo $rowI['image'] ?>" style="width:100%">        
                    </div>        
                </div>
                
                <?php endwhile; ?>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php
                                $isCategory = false;
                                if(!isset($_GET['id'])){
                                    $books = $conn->query("SELECT * FROM books WHERE isDeleted=false ORDER BY books.title ASC");
                                } else {
                                    if($_GET['id']==-1){
                                        $isCategory = true;
                                        $id = 'book_id';
                                        $cats = $conn->query("SELECT book_id, COUNT(*) AS magnitude FROM orders GROUP BY book_id ORDER BY magnitude DESC LIMIT 50");                                        
                                    } elseif($_GET['id']==-2){
                                        $isCategory = true;
                                        $id = 'id';
                                        $cats = $conn->query("SELECT id, price FROM books ORDER BY price DESC LIMIT 50");
                                    } elseif($_GET['id']==-3){
                                        $isCategory = true;
                                        $cats = $conn->query("SELECT id, year FROM books WHERE year != '' ORDER BY year DESC LIMIT 50");
                                        $id = 'id';
                                    } else {
                                        $books = $conn->query("SELECT books.*, categories.genre FROM books 
                                        INNER JOIN book_category ON books.id = book_category.book_id
                                        INNER JOIN categories ON categories.id = book_category.category_id WHERE books.isDeleted=false AND categories.id = ". $_GET['id']);
                                    }                                                                       
                                }                                                           
                                if(!$isCategory){
                                    if($books->num_rows <= 0){
                                        echo "<center><h4><i>Няма налични продукти.</i></h4></center>";
                                    }
                                    while($row=$books->fetch_assoc()):
                                        $author = $conn->query("SELECT authors.name FROM authors INNER JOIN book_author ON authors.id=book_author.author_id WHERE book_author.book_id =".$row['id']); 
                                            $auth = array();                                        
                                            $k = 0;
                                            while($row1=$author->fetch_assoc()):
                                                $auth[$k] = $row1['name'];
                                                $k++;
                                            endwhile;
                                            $auths = implode(', ', $auth); ?>
                                            <div class="col-sm-4">
                                                <div class="card">
                                                    <div class="float-right align-top bid-tag">
                                                        <span class="badge badge-pill badge-primary text-white"><i class="fa fa-tag"></i> <?php echo number_format($row['price']) ?></span>
                                                    </div>
                                                    <div class="card-img-top d-flex justify-content-center" style="max-height: 30vh;overflow: hidden">
                                                    <img class="img-fluid" src="admin/assets/uploads/<?php echo $row['image'] ?>" alt="Card image cap">
                                                    
                                                    </div>
                                                    <div class="float-right align-top d-flex">
                                                    </div>
                                                    <div class="card-body prod-item">
                                                        <p>Заглавие: <?php echo $row['title'] ?></p>
                                                        <p>Автор/и: <?php echo $auths ?></p>                                         
                                                        <p class="truncate"><?php echo $row['description'] ?></p>
                                                        <button class="btn btn-primary btn-sm view_prod" type="button" data-id="<?php echo $row['id'] ?>"> Прегледай</button>
                                                    </div>
                                                </div>
                                            </div>

                               <?php endwhile; } else {                                   
                                    while($row_c=$cats->fetch_assoc()):
                                        $books = $conn->query("SELECT * FROM books WHERE isDeleted=false AND id=".$row_c[$id]);
                                        while($row=$books->fetch_assoc()):
                                            $author = $conn->query("SELECT authors.name FROM authors INNER JOIN book_author ON authors.id=book_author.author_id WHERE book_author.book_id =".$row['id']); 
                                                $auth = array();                                        
                                                $k = 0;
                                                while($row1=$author->fetch_assoc()):
                                                    $auth[$k] = $row1['name'];
                                                    $k++;
                                                endwhile;
                                                $auths = implode(', ', $auth);                                                                        										
                                ?>                                
                             <div class="col-sm-4">
                                 <div class="card">
                                    <div class="float-right align-top bid-tag">
                                         <span class="badge badge-pill badge-primary text-white"><i class="fa fa-tag"></i> <?php echo number_format($row['price']) ?></span>
                                     </div>
                                     <div class="card-img-top d-flex justify-content-center" style="max-height: 30vh;overflow: hidden">
                                     <img class="img-fluid" src="admin/assets/uploads/<?php echo $row['image'] ?>" alt="Card image cap">
                                       
                                     </div>
                                      <div class="float-right align-top d-flex">
                                     </div>
                                     <div class="card-body prod-item">
                                         <p>Заглавие: <?php echo $row['title'] ?></p>
                                         <p>Автор/и: <?php echo $auths ?></p>                                         
                                         <p class="truncate"><?php echo $row['description'] ?></p>
                                        <button class="btn btn-primary btn-sm view_prod" type="button" data-id="<?php echo $row['id'] ?>"> Прегледай</button>
                                     </div>
                                 </div>
                             </div>
                            <?php endwhile; endwhile; }?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>.

<style>
    #cat-list li{
        cursor: pointer;
    }
    #cat-list li:hover {
    color: white;
    background: #007bff8f;
    }
    #cat-list li:active {
    color: white;
    background: #007bff8f;
    }
    .prod-item p{
        margin: unset;
    }
    .bid-tag {
    position: absolute;
    right: .5em;
    }

    * {box-sizing: border-box;}
    body {font-family: Verdana, sans-serif;}
    .mySlides {display: none;}
    img {vertical-align: middle;}

    /* Slideshow container */
    .slideshow-container {
    max-width: 1000px;
    position: relative;
    margin: auto;
    }

    .active {
    background-color: #717171;
    }

    /* Fading animation */
    .fade {
    -webkit-animation-name: fade;
    -webkit-animation-duration: 3.5s;
    animation-name: fade;
    animation-duration: 3.5s;
    }

    @-webkit-keyframes fade {
    from {opacity: .6} 
    to {opacity: 1}
    }

    @keyframes fade {
    from {opacity: .6} 
    to {opacity: 1}
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 200px) {
    .text {font-size: 11px}
    }

    img {
    border-radius: 5%;
    }  
</style>      
<script>
    $(document).ready(function () {
        $('#cat-list li').click(function () {
            var id = $(this).attr("id");

            $('#' + id).siblings().find(".active").removeClass("active");                
            $('#' + id).addClass("active");
            localStorage.setItem("selectedolditem", id);
        });

        var selectedolditem = localStorage.getItem('selectedolditem');
        var params = getUrlParams(window.location.href);
        var id = params.id;        

        var isClicked = false;
        if(id==-1 || id==-2 || id==-3) {
            isClicked = true;
        }

        if (selectedolditem != null && isClicked) {
            $('#' + selectedolditem).siblings().find(".active").removeClass("active");            
            $('#' + selectedolditem).addClass("active");
        }        
    });

    var getUrlParams = function (url){
        var params = {};
        (url + '?').split('?')[1].split('&').forEach(
            function (pair) 
            {
            pair = (pair + '=').split('=').map(decodeURIComponent);
            if (pair[0].length) 
            {
                params[pair[0]] = pair[1];
            }
        });
        return params;
    };

    var slideIndex = 0;
    showSlides();

    function showSlides() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}    
    slides[slideIndex-1].style.display = "block";  
    setTimeout(showSlides, 2500); // Change image every 2.5 seconds
    }

    $('#cat-list li').click(function(){
        location.href = $(this).attr('data-href');
    });  

    $('.view_prod').click(function(){         
        uni_modal_right('Прегледай книга','view_prod.php?id='+$(this).attr('data-id'));
    });
</script>