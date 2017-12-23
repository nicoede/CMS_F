<?php
include "../s3/init.php";
include "modals/edit_post_modal.php";

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
    $post_image_1 = $post_image;
  }

  if(isset($_POST['udpate_post'])){
    $file = $_FILES['image'];
  
    $name = $file['name'];
    if($name !== ''){
      $file = $_FILES['image'];
  
      $name = $file['name'];
      $tmp_name = $file['tmp_name'];
      
      $ext = explode('.', $name);
      $ext = strtolower(end($ext));
      
      $key = md5(uniqid());
      
      $temp_file_name = "{$key}.{$ext}";
      
      $temp_file_path = "cms/{$temp_file_name}";
      
      move_uploaded_file($tmp_name, $temp_file_path);
      
      try {
        // Upload data.
        $result = $s3->putObject(array(
           'Bucket'=> $config['s3']['bucket'],
            'Key'   => "cms/{$name}",
            'Body'  => fopen($temp_file_path, 'rb'),
            'ACL'   => 'public-read'
       ));
      
        // Print the URL to the object.
      } catch (S3Exception $e) {
          echo $e->getMessage() . "\n";
      }
      unlink($temp_file_path);
      $post_image = $_FILES['image']['name'];
      $post_image_tmp = $_FILES['image']['tmp_name'];
    }else{
      $post_image = $post_image_1;
    }
    
    $post_author = $_POST['post_author'];
    $post_title = $_POST['post_title'];
    $post_category_id = $_POST['post_category'];
    $post_status = $_POST['post_status'];
    $post_content = $_POST['post_content'];
    $post_tags = $_POST['post_tags'];
    
    
    $query = "UPDATE posts SET post_title = '{$post_title}', post_category_id = '{$post_category_id}', post_date = now(), post_author = '{$post_author}', ";
    $query .= "post_status = '{$post_status}', post_tags = '{$post_tags}', post_content = '{$post_content}', post_image = '{$post_image}' "; 
    $query .= "WHERE post_id = {$the_post_id} ";
    
    $update_post = mysqli_query($connection, $query);
    
    confirm($update_post);
    
    header("Refresh: 0.1; url=posts.php");
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
      <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>
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
    <input type="file" name="image" />
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
    <input class="btn btn-primary updatePost" type="submit" name="udpate_post" value="Publish Post"/>
  </div>
</form>

<script>
  $(document).ready(function(){
    $(".updatePost").on('click', function(){
      $('#post_updated_modal_id').modal('show');
    });
  });
</script>