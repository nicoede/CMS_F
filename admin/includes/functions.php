<?php


session_start(); 

  function confirm($result){
    global $connection;
    if(!$result){
      die("QUERY FAILED" . mysqli_error($connection));
    }
  }
  
  function add_post_admin(){
    include "../s3/init.php"; 
    global $connection;
    if(isset($_POST['create_post'])){
      	$file = $_FILES['image'];

        $name = $file['name'];
        if($name == ''){
          $post_image = 'post.png';
        }else{
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
        }
      
      $post_title = $_POST['post_title'];
      $post_author = $_POST['post_author'];
      $post_category_id = $_POST['post_category_id'];
      $post_status = $_POST['post_status'];
      
     
      
      $post_tags = $_POST['post_tags'];
      $post_content = $_POST['post_content'];
      $post_date = date('d-m-y');
      
      $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) VALUES ({$post_category_id}, '{$post_title}', '{$post_author}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}') ";
      $add_posts_query = mysqli_query($connection, $query);
      
      confirm($add_posts_query);
      
      header("Refresh: 0.1; url=posts.php");
    }
  }
  
  function show_all_posts_admin(){
    include "../s3/init.php";
    global $connection;
    
    $objs = $s3->getIterator('ListObjects', [
        'Prefix' => 'cms/',
        'Bucket' => $config['s3']['bucket']
    ]);
    
    $query = "SELECT * FROM posts ";
    $select_posts = mysqli_query($connection, $query);
    
    while($row = mysqli_fetch_assoc($select_posts)){
      $post_id = $row['post_id'];
      $post_author = $row['post_author'];
      $post_title = $row['post_title'];
      $post_category_id = $row['post_category_id'];
      $post_status = $row['post_status'];
      $post_image = $row['post_image'];
      $post_tags = $row['post_tags'];
      $post_comment_count = $row['post_comment_count'];
      $post_date = $row['post_date'];
      
      echo "<tr>";
        
        ?>
        <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>
        <?php
        
        echo "<td>{$post_id}</td>";
        echo "<td>{$post_author}</td>";
        echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
        
        $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
        $select_categories_id = mysqli_query($connection, $query);
        
        while($row = mysqli_fetch_assoc($select_categories_id)){
          $cat_id = $row['cat_id'];
          $cat_title = $row['cat_title'];
          echo "<td>{$cat_title}</td>";
        }
        
        echo "<td>{$post_status}</td>";
        echo "<td><img width='100' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$post_image}' alt='image'></td>";
        echo "<td>{$post_tags}</td>";
        echo "<td>{$post_comment_count}</td>";
        echo "<td>{$post_date}</td>";
        echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
        echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_post_link_class' >Delete</a></td>";
      echo "</tr>";
    }
  }
  
  function delete_posts_admin(){
    global $connection;
    if(isset($_GET['delete'])){
      $the_post_id = $_GET['delete'];
      $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
      $delete_query = mysqli_query($connection, $query);
      confirm($delete_query);
      header("Refresh: 0.1; url=posts.php");
    }
  }
  
  function insert_category(){
    global $connection;
    
    if(isset($_POST['submit'])){
      $cat_title = $_POST['cat_title'];
      
      if($cat_title == "" || empty($cat_title)){
        $suc = 1;
      }else{
        $query = "INSERT INTO categories(cat_title) ";
        $query .= "VALUE('{$cat_title}') ";
        
        $create_category_query = mysqli_query($connection, $query);
        
        confirm($create_category_query);
        $suc = 0;
      }
      return $suc;
    }
  }
  
  function find_all_categories(){
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    
    while($row = mysqli_fetch_assoc($select_categories)){
      $cat_id = $row['cat_id'];
      $cat_title = $row['cat_title'];
      echo "<tr>";
      echo "<td>{$cat_id}</td>";
      echo "<td>{$cat_title}</td>";
      echo "<td><a rel='$cat_id' href='javascript:void(0)' class='delete_category_link_class' >Delete</a></td>";
      echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
      echo "</tr>";
    }
  }
  
  function delete_categorie(){
    global $connection;
    if(isset($_GET['delete'])){
      $get_cat_id = $_GET['delete'];
      $query = "DELETE FROM categories WHERE cat_id = {$get_cat_id} ";
      $delete_query = mysqli_query($connection, $query);
      confirm($delete_query);
      header("Refresh: 0.1; url=categories.php");
    }
  }
  
  function show_all_comments_admin(){
    global $connection;
    $query = "SELECT * FROM comments";
    $select_comments = mysqli_query($connection, $query);
    
    while($row = mysqli_fetch_assoc($select_comments)){
      $comment_id = $row['comment_id'];
      $comment_post_id = $row['comment_post_id'];
      $comment_author = $row['comment_author'];
      $comment_content = $row['comment_content'];
      $comment_email = $row['comment_email'];
      $comment_status = $row['comment_status'];
      $comment_date = $row['comment_date'];
      
      echo "<tr>";
        echo "<td>{$comment_id}</td>";
        echo "<td>{$comment_author}</td>";
        echo "<td>{$comment_content}</td>";
        echo "<td>{$comment_email}</td>";
        echo "<td>{$comment_status}</td>";
        
        $query = "SELECT * FROM posts WHERE post_id = $comment_post_id ";
        $select_post_id_query = mysqli_query($connection, $query);
        while($row = mysqli_fetch_assoc($select_post_id_query)){
          $post_id = $row['post_id'];
          $post_title = $row['post_title'];
          echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
        }
        echo "<td>{$comment_date}</td>";
        echo "<td><a href='comments.php?approve={$comment_id}'>Approve</a></td>";
        echo "<td><a href='comments.php?disapprove={$comment_id}'>Disapprove</a></td>";
        echo "<td><a class='comment_delete_class' href='comments.php?delete={$comment_id}&c_p_id={$comment_post_id}'>Delete</a></td>";
      echo "</tr>";
    }
  }
  
  function disapprove_comments(){
    global $connection;
    if(isset($_GET['disapprove'])){
      $get_disapprove_id = $_GET['disapprove'];
      
      $query = "UPDATE comments SET comment_status = 'disapproved' WHERE comment_id = $get_disapprove_id ";
      $disapprove_comment_query = mysqli_query($connection, $query);
      
      header("Refresh: 0.5; url=comments.php");
    }
  }
  
   function approve_comments(){
    global $connection;
    if(isset($_GET['approve'])){
      $get_approve_id = $_GET['approve'];
      
      $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $get_approve_id ";
      $approve_comment_query = mysqli_query($connection, $query);
      
      header("Location: comments.php");
    }
  }
  
  function delete_comment_admin(){
    global $connection;
    if(isset($_GET['delete'])){
      $get_comment_id = $_GET['delete'];
      $cpd = $_GET['c_p_id'];
      
      $query = "DELETE FROM comments WHERE comment_id = {$get_comment_id} ";
      $delete_comment_query = mysqli_query($connection, $query);
      
      confirm($delete_comment_query);
      
      $quesedane = $_GET['p_id'];
      $query_count = "UPDATE posts set post_comment_count = post_comment_count - 1 ";
      $query_count .= "WHERE post_id = $cpd ";
      $update_comment_count = mysqli_query($connection, $query_count);
      header("Refresh: 0.5; url=comments.php");
    }
  }
  
  function show_all_users_admin(){
    global $connection;
    $query = "SELECT * FROM users ";
    $select_users = mysqli_query($connection, $query);
    
    while($row = mysqli_fetch_assoc($select_users)){
      $user_id = $row['user_id'];
      $username = $row['username'];
      $user_firstname = $row['user_firstname'];
      $user_lastname = $row['user_lastname'];
      $user_email = $row['user_email'];
      $user_role = $row['user_role'];
      $user_date = $row['user_date'];
      
      if($username !== $_SESSION['username']){
        echo "<tr>";
          echo "<td>{$user_id}</td>";
          echo "<td>{$username}</td>";
          echo "<td>{$user_firstname}</td>";
          
          $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
          $select_categories_id = mysqli_query($connection, $query);
          
          echo "<td>{$user_lastname}</td>";
          echo "<td>{$user_email}</td>";
          echo "<td>{$user_role}</td>";
          echo "<td>{$user_date}</td>";
          
          echo "<td><a href='users.php?source=edit_user&u_id={$user_id}'>Edit</a></td>";
          echo "<td><a class='user_delete_class' href='users.php?delete={$user_id}'>Delete</a></td>";
        echo "</tr>";
      }else{
        echo "<h1>No users are currently subscribed</h1>";
      }
    }
  }
  
   function add_user_admin(){
    global $connection;
    if(isset($_POST['create_user'])){
      $username = $_POST['username'];
      $user_firstname = $_POST['user_firstname'];
      $user_lastname = $_POST['user_lastname'];
      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];
      $user_role = $_POST['user_role'];
      $user_image = $_FILES['user_image']['name'];
      $user_image_tmp = $_FILES['user_image']['tmp_name'];
      $user_date = date('d-m-y');

      move_uploaded_file($user_image_tmp, "../images/$user_image" );
      
      $query = "SELECT randSalt FROM users";
      $select_randsalt_query = mysqli_query($connection, $query);
      confirm($select_randsalt_query);
      
      $row = mysqli_fetch_array($select_randsalt_query);
      $salt = $row['randSalt'];
      $hashed_password = crypt($user_password, $salt);
      
      $query = "INSERT INTO users(username, user_firstname, user_lastname, user_email, user_password, user_role, user_image, user_date) ";
      $query .= "VALUES ('{$username}', '{$user_firstname}', '{$user_lastname}', '{$user_email}', '{$hashed_password}', '{$user_role}', '{$user_iamge}', now()) ";
      $add_user_query = mysqli_query($connection, $query);
      
      confirm($add_user_query);
    }
  }
  
  function delete_user_admin(){
    global $connection;
    if(isset($_GET['delete'])){
      $get_user_id = $_GET['delete'];
      
      $query = "DELETE FROM users WHERE user_id = {$get_user_id} ";
      $delete_comment_query = mysqli_query($connection, $query);
      
      confirm($connection);
      
      header("Refresh: 0.5; url=users.php");
    }
  }
?>