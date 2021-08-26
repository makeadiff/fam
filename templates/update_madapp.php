<?php if(!$continue) { ?>
<div class="x_panel">
  <div class="x_title">
    <h2>Update On MADApp</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
    <p> Are you sure you want to update Fellows' <b>UserGroups</b> and <b>Email</b> on MADApp </p>
    <form action="./update_madapp.php" method="POST">
      <input type="submit" name="continue" value="Yes"/>
      <input type="submit" name="continue" value="No"/>
    </form>
  </div>
</div>
<?php } else { ?>

<div class="x_panel">
  <div class="x_title">
    <h2>Update on MADApp</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr>
      <th>ID</th>
      <th>Fellow Name</th>
      <th>City Name</th>
      <th>Role</th>
      <th>UserGroup Updated</th>
      <th>Email Updated</th>
    </tr>
    </thead>

    <tbody>
      <?php
        foreach ($selected as $app){
          echo '<tr>'.
                '<td>'.$app['user_id'].'</td>'.
                '<td>'.$app['name'].'</td>'.
                '<td>'.$app['city'].'</td>'.
                '<td>'.$app['role'].'</td>'.
                '<td><b>'.$group_updated[$app['user_id']].'</b></td>'.
                '<td><b>'.$email_updated[$app['user_id']].'</b></td>'.
              '</tr>';
        }
      ?>
    </tbody>
  </table>
  </div>
</div>
<?php } ?>