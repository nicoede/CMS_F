<?php include "includes/db.php" ?>

<div class="well">
    <h4>Latest Posts</h4>
    <?php 
    $query = "SELECT * FROM posts ORDER BY post_id DESC LIMIT 0, 3";
    $select_latest_posts_query = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($select_latest_posts_query)){
      $post_id = $row['post_id'];
      $post_title = $row['post_title'];
      $post_status = $row['post_status'];
      
      if($post_status == 'Published'){
    ?>
    <p>
        <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
    </p>
    <?php
      } 
        
    }
?>
</div>