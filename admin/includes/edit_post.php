<?php
  if(isset($_GET['p_id'])){
    $the_post_id = $_GET['p_id'];
    
    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
    $select_posts_by_id = mysqli_query($connection, $query);
    
    while($row = mysqli_fetch_assoc($select_posts_by_id)){
      $post_id = $row['post_id'];
      $post_author = $row['post_author'];
      $post_title = $row['post_title'];
      $post_category = $row['post_category'];
      $post_status = $row['post_status'];
      $post_image = $row['post_image'];
      $post_tags = $row['post_tags'];
      $post_content = $row['post_content'];
      $post_comment_count = $row['post_comment_count'];
      $post_date = $row['post_date'];
    }
  }

  if(isset($_POST['udpate_post'])){
    $post_author = $_POST['post_author'];
    $post_title = $_POST['post_title'];
    $post_category_id = $_POST['post_category'];
    $post_status = $_POST['post_status'];
    $post_content = $_POST['post_content'];
    $post_tags = $_POST['post_tags'];
    
    $query = "UPDATE posts SET post_title = '{$post_title}', post_category_id = '{$post_category_id}', post_date = now(), post_author = '{$post_author}', ";
    $query .= "post_status = '{$post_status}', post_tags = '{$post_tags}', post_content = '{$post_content}' "; 
    $query .= "WHERE post_id = {$the_post_id} ";
    
    $update_post = mysqli_query($connection, $query);
    
    if(!confirm($update_post)){
      $message = "Post Updated!";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    
    header("Refresh: 0.5; url=posts.php");
  }
  
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="post_title">Post Title</label>
    <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="post_title"/>
  </div>

  <div class="form-group">
    <select name="post_category" id="">
      <?php
        $query = "SELECT * FROM categories ";
        $select_categories = mysqli_query($connection, $query);
        
        confirm($select_categories);
        
        while($row = mysqli_fetch_assoc($select_categories)){
          $cat_id = $row['cat_id'];
          $cat_title = $row['cat_title'];
          echo "<option value='$cat_id'>{$cat_title}</option>";
        }  
      ?>
    </select>
  </div>
  
  <div class="form-group">
    <label for="post_author">Post Author</label>
    <input value="<?php echo $post_author; ?>" type="text" class="form-control" name="post_author"/>
  </div>
  
  <div class="form-group">
    <label for="post_status">Post Status</label>
    <select class="form-control" name="post_status" id="">
      <option value="Draft"><?php echo $post_status; ?></option>
      <?php
        if($post_status == 'Draft'){
          echo "<option value='Published'>Published</option>";
        }else{
          echo "<option value='Draft'>Draft</option>";
        }
      ?>
    </select>
  </div>
  
  <div class="form-group">
    <?php echo "<img width='100' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$post_image}' alt='image'>"; ?>
  </div>
  
  <div class="form-group">
    <label for="post_tags">Post Tags</label>
    <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags"/>
  </div>
  
  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea class="form-control" name="post_content" id="" cols="30" rows="10"><?php echo $post_content; ?></textarea>
  </div>
  
  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="udpate_post" value="Publish Post"/>
  </div>
</form>