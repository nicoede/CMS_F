<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>


    <!-- Navigation -->
    <?php include "includes/navigation.php"?>
    
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                
                <?php
                    if(isset($_GET['p_id'])){
                        $the_post_id = $_GET['p_id'];
                    }
                    
                    
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
                    $select_all_posts_query = mysqli_query($connection, $query);
                    
                    while($row = mysqli_fetch_assoc($select_all_posts_query)){
                      $post_title = $row['post_title'];
                      $post_author = $row['post_author'];
                      $post_date = $row['post_date'];
                      $post_image = $row['post_image'];
                      $post_content = $row['post_content'];
                      
                      ?>
                        
                        <!-- First Blog Post -->
                        <h2>
                            <?php echo $post_title ?>
                        </h2>
                        <p class="lead">
                            by <a href="post_by_author.php"><?php echo $post_author ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date ?></p>
                        <hr>
                        <?php echo "<img class='img-responsive' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$post_image}' alt='image'>";?>
                        <hr>
                        <?php echo $post_content ?>
                        <?php
                        if(isset($_SESSION['user_role'])){
                            $u_role = $_SESSION['user_role'];
                            if($u_role = "Admin"){
                                ?>
                                <a class="btn btn-primary" href="admin/posts.php?source=edit_post&p_id=<?php echo $the_post_id ?>">Edit This Post <span class="glyphicon glyphicon-chevron-right"></span></a>
                                <?php
                            }
                        }
                        ?>
                        <!--
                        
                        <hr>
                        <?php
                    }
                ?>
                
                <!-- Blog Comments -->
                
                <?php 
                    if(isset($_POST['create_comment'])){
                        $user_name_session = $_SESSION['username'];
                        $user_email_query = "SELECT * FROM users WHERE username = '{$user_name_session}' ";
                        $user_email_query_result = mysqli_query($connection, $user_email_query);
                        confirm($user_email_query_result);
                        while($row3 = mysqli_fetch_assoc($user_email_query_result)){
                          $username = $row3['username'];
                          $user_email = $row3['user_email'];
                        }
                        
                        $the_post_id = $_GET['p_id'];
                        
                        $comment_author = $username;
                        $comment_email = $user_email;
                        $comment_content = $_POST['comment_content'];
                        
                        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_email)){
                            $query = "INSERT INTO comments(comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                            $query .= "VALUES ({$the_post_id}, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'disapproved', now() ) ";
                            
                            $comment_query = mysqli_query($connection, $query);
          
                            confirm($comment_query);
                            
                            $query_count = "UPDATE posts set post_comment_count = post_comment_count + 1 ";
                            $query_count .= "WHERE post_id = $the_post_id ";
                            $update_comment_count = mysqli_query($connection, $query_count);
                        }else{
                            echo "<script>alert('Fields can not be empty')</script>";
                        }
                    }
                ?>

                <!-- Comments Form -->
                <div class="well" style="margin-top: 20px;">
                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <label for="Comment">Your Comment:</label>
                            <textarea class="form-control" name="comment_content" rows="3"></textarea>
                        </div>
                        <?php if($_SESSION['username'] == null){ ?>
                            <h3><b>You must be Logged to comment</b></h3>
                        <?php }else{ ?>
                            <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                        <?php } ?>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
                <?php 
                    $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                    $query .= "AND comment_status = 'approved' ";
                    $query .= "ORDER BY comment_id DESC ";
                    $select_comment_query = mysqli_query($connection, $query);
                    
                    confirm($select_comment_query);
                    
                 
                    while($row = mysqli_fetch_assoc($select_comment_query)){
                        $comment_date = $row['comment_date'];
                        $comment_content = $row['comment_content'];
                        $comment_author = $row['comment_author'];
                        ?>
                        <?php 
                        $userimage_query = "SELECT user_image FROM users WHERE username = '{$comment_author}' ";
                        $userimage_result = mysqli_query($connection, $userimage_query);
                        confirm($userimage_result);
                        $row2 = mysqli_fetch_assoc($userimage_result);
                        $userimage = $row2['user_image'];
                        ?>
                        <!-- Comment -->
                        <div class="media">
                            <span class="pull-left" >
                                <?php echo "<img width='64' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$userimage}' alt='image'>"; ?>
                            </span>
                            <div class="media-body">
                                <h4 class="media-heading"><?php echo $comment_author; ?>
                                    <small><?php echo $comment_date; ?></small>
                                </h4>
                                <?php echo $comment_content; ?>
                            </div>
                        </div>
                        
                        
                        <?php
                    }
                ?>

                
                
                
            </div>

            <!-- Blog Sidebar Widgets Column -->
              <?php include "includes/blog_sidebar.php" ?>

        </div>
        <!-- /.row -->

        <hr>
        <?php include "includes/footer.php" ?>