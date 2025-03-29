<!DOCTYPE html>
<html lang="bg">
    <?php
    session_start();
    require 'admin/db_connect.php';
    require 'header.php';
    ?>

    <body id="page-top">
        <!-- Navigation-->
        <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
      </div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="./">Книжарница "Атина"</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=home"><i class="fa fa-home"></i> Начало</a></li>                        
                        <li class="nav-item dropdown">
                          <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="true">                            
                            <i class="fa fa-book"></i>
                            <span>Книги</span>
                          </a>
                          <div class="dropdown-menu">                                                       
                            <div class="dropdown mr-4">
                              <?php
                                $cat = $conn->query("SELECT * FROM categories WHERE isDeleted=false ORDER BY genre ASC");
                                while($row=$cat->fetch_assoc()):
                                    $cat_arr[$row['id']] = $row['genre'];
                              ?>
                               <a class="dropdown-item" data-id='<?php echo $row['id'] ?>' href="index.php?page=home&id=<?php echo $row['id'] ?>"><?php echo ucwords($row['genre']) ?></a>
                              <?php endwhile; ?>
                            </div>
                          </div>
                        </li>            
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=contact_us"><i class="fa fa-phone"></i> Контакти</a></li>
                                                       
                            <li class="nav-item dropdown">
                              <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="true">
                                <div class="badge badge-danger cart-count"><?php echo isset($_SESSION['book_id']) ? $_SESSION['qty1'] : 0 ?></div>
                                <i class="fa fa-shopping-cart"></i>
                                <span>Количка</span>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="width:25vw">
                                <div class="cart-list w-100" id="cart_product">                                    
                                </div>                            
                                <div class="d-flex bg-light justify-content-center w-100 p-2">
                                  <a href="index.php?page=cart" class="btn btn-sm btn-primary btn-block col-sm-4 text-white"><i class="fa fa-edit"></i>  Виж количката</a>
                                </div>
                              </div>
                            </li>                                                   
                            <?php if(isset($_SESSION['login_id'])): ?>                        
                            <div class=" dropdown mr-4">
                                  <a href="#" class="text-white dropdown-toggle"  id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i><?php echo " ". $_SESSION['login_name'] ?></a>
                                    <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
                                      <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Редактирай данните си</a>
                                      <a class="dropdown-item" href="index.php?page=order_history" id="order_history"><i class="fa fa-history"></i> История на поръчките</a>
                                      <a class="dropdown-item" href="admin/ajax.php?action=logout_user"><i class="fa fa-power-off"></i> Излез</a>
                                    </div>
                              </div>
                            <?php else: ?>
                              <li class="nav-item"><a class="nav-link js-scroll-trigger" href="javascript:void(0)" id="login_now"><i class="fas fa-sign-in-alt"></i> Влез</a></li>
                            <?php endif; ?>                                                                    
                    </ul>
                </div>
            </div>
        </nav>
  <main id="main-field">
        <?php 
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        include $page.'.php';
        ?>
       
</main>
<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Потвърждение</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Продължи</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмени</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Запази</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмени</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
  <div id="preloader"></div>
        <footer class=" py-5 bg-dark">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="mt-0 text-white">Свържете се с нас</h2>
                        <hr class="divider my-4" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                        <i class="fas fa-phone fa-3x mb-3 text-muted"></i>
                        <div class="text-white">+359882866331</div>
                    </div>
                    <div class="col-lg-4 mr-auto text-center">
                        <i class="fas fa-envelope fa-3x mb-3 text-muted"></i>                        
                        <a class="d-block" href="mailto:georgeivanov720@gmail.com">georgeivanov720@gmail.com</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="container"><div class="small text-center text-muted">Copyright © 2022 - Книжарница "Атина"</div></div>
        </footer>
        
       <?php require 'footer.php'; ?>
    </body>

    <style>
      #main-field{
        margin-top: 5rem!important;
      }
      .cart-img {
          width: calc(20%);
          height: 13vh;
          padding: 3px
      }
      .cart-img img{
        width: 100%;
        height: 100%;
      }
      .cart-qty {
        font-size: 14px
      }  
    </style>

    <script type="text/javascript">
      $('#login').click(function(){
          uni_modal("Влез",'login.php');
      });

      $('.datetimepicker').datetimepicker({
          format:'Y-m-d H:i',
      });
      
      $('#manage_my_account').click(function(){
          uni_modal("Редактирай данните си",'signup.php');
      });
    </script>
    <?php $conn->close(); ?>
</html>
