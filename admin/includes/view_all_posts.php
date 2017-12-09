<?php 
  if(isset($_POST['checkBoxArray'])){
    foreach($_POST['checkBoxArray'] as $checkBox_post_id){
     $bulk_options = $_POST['bulk_options'];
     switch ($bulk_options) {
       
       case 'Published':
         $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBox_post_id} ";
         $update_to_publish_status = mysqli_query($connection, $query);
         confirm($update_to_publish_status);
         break;
         
       case 'Draft':
         $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBox_post_id} ";
         $update_to_draft_status = mysqli_query($connection, $query);
         confirm($update_to_draft_status);
         break;
         
       case 'Delete':
         $query = "DELETE FROM posts WHERE post_id = {$checkBox_post_id} ";
         $delete_query = mysqli_query($connection, $query);
         confirm($delete_query);
         break;
       
       default:
         // code...
         break;
     }
    }
  }
?>

<form action="" method="post">
  
  <table class="table table-bordered table-hover">
    <div id="bulkOptionContainer" class="col-xs-4" style="margin-bottom: 20px; padding:0px;">
      <select class="form-control" name="bulk_options" id="">
        <option value="">Select Options</option>
        <option value="Published">Published</option>
        <option value="Draft">Draft</option>
        <option value="Delete">Delete</option>
      </select>
    </div>
    
    <div class="col-xs-4">
      <input type="submit" name="submit" class="btn btn-success" value="Apply">
      <a class="btn btn-primary" href="?source=add_post">Add New</a>
    </div>
      
    <thead>
      <tr>
        <th><input id="select_all_boxes" type="checkbox"></th>
        <th>Id</th>
        <th>Author</th>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Image</th>
        <th>Tags</th>
        <th>Comments</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        show_all_posts_admin();
        delete_posts_admin();
      ?>
    </tbody>
  </table>

</form>