<?php include "modals/delete_user_modal.php"; ?>

<table class="table table-bordered table-hover">
<thead>
  <tr>
    <th>Id</th>
    <th>Username</th>
    <th>FirstName</th>
    <th>LastName</th>
    <th>Email</th>
    <th>Role</th>
    <th>Date</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
</thead>
<tbody>
  <?php 
    show_all_users_admin();
    delete_user_admin();
  ?>
</tbody>
</table>

<script>
  $(document).ready(function(){
    $(".user_delete_class").on('click', function(){
      $('#delete_user_modal_id').modal('show');
    });
  });
</script>