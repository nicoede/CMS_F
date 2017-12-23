<?php 
include "db.php"; 
include "header.php";
include "admin/includes/functions.php";
?>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        
        <div class="container" style="margin-bottom: -3px; margin-top: -10px;">
            
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php" style="margin-top: -3px; " ><img  src="images/cms.png" width="56"></a>
            </div>
            <?php
                if($_SESSION['username'] != null){
                    $user = $_SESSION['username'];
                    $userimage_query = "SELECT user_image FROM users WHERE username = '{$user}' ";
                    $userimage_result = mysqli_query($connection, $userimage_query);
                    confirm($userimage_result);
                    $row2 = mysqli_fetch_assoc($userimage_result);
                    $userimage = $row2['user_image'];
            ?>
            <ul class="nav navbar-right top-nav" style="margin-top:5px;">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo "<img width='36' style='margin-right: 5px;' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$userimage}' alt='image'>"; ?><?php echo $_SESSION['username']; ?> </b> <b class="caret" style="color:#999;"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php if($_SESSION['user_role'] == 'Admin'){?>
                                <a href="admin/profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                            <?php }else{ ?>
                                <a href="includes/subscriber_profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                            <?php } ?>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php } ?>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="margin-top: 14px;">
                <ul class="nav navbar-nav">
                  <?php  
                    $query = "SELECT * FROM categories";
                    $select_all_categories_query = mysqli_query($connection, $query);
                    
                    while($row = mysqli_fetch_assoc($select_all_categories_query)){
                      $cat_id = $row['cat_id'];
                      $cat_title = $row['cat_title'];
                      echo "<li><a href='category.php?category=$cat_id'>{$cat_title}</a></li>";
                    }
                  ?>
                    
                    <?php
                        if($_SESSION['user_role'] == 'Admin'){
                    ?>
                        <li>
                            <a href="admin" style="color: white;">Admin</a>
                        </li>
                    <?php } ?>
                    <?php if(!isset($_SESSION['username'])){ ?>
                        <li>
                            <a href="registration.php">Registration</a>
                        </li>
                    <?php } ?>

                    
                </ul>
                
            </div>
            
            <!-- /.navbar-collapse -->
        </div>
 
        <!-- /.container -->
    </nav>
    
    