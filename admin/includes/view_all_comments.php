<table class="table table-bordered table-hover">
<thead>
  <tr>
    <th>Id</th>
    <th>Author</th>
    <th>Comment</th>
    <th>Email</th>
    <th>Status</th>
    <th>In Response of</th>
    <th>Date</th>
    <th>Approve</th>
    <th>Disapprove</th>
    <th>Delete</th>
  </tr>
</thead>
<tbody>
  <?php 
    show_all_comments_admin();
    delete_comment_admin();
    approve_comments();
    disapprove_comments();
  ?>
</tbody>
</table>