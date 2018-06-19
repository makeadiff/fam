
<?php

  if($continue){
?>
    <div class="x_panel">
      <div class="x_title">
        <h2>Update On MADApp</h2>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <p> Are you sure you want to update Fellows' <b>UserGroups</b> and <b>Email</b> on MADApp </p>
        <form action="./update_madapp" method="POST">
          <input type="submit" name="approve" value="Yes"/>
          <input type="submit" name="approve" value="No"/>
        </form>
      </div>
    </div>
<?php
    exit;
  }

?>



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
      <th>Email</th>
      <th>Sex</th>
      <th>City Name</th>
      <th>Role</th>
      <th>UserGroup Updated</th>
      <th>Email Updated</th>
    </tr>
    </thead>

    <tbody>
      <?php
        foreach ($applications as $app){

          $ugp_status = '';
          $email_status = '';


          if(isset($email_ids[$app['user_id']])){
            $email = $email_ids[$app['user_id']];
          }
          else{
            $email = '';
          }

          $ugp_update_check = $sql->getOne('SELECT id FROM UserGroup UG WHERE UG.user_id='.$app['user_id'].' AND UG.group_id='.$app['group_id'].' AND UG.year='.$year);

          if(empty($ugp_update_check)){
            $ugp_add = $sql->insert('UserGroup',array(
                'user_id' => $app['user_id'],
                'group_id' => $app['group_id'],
                'year' => $year
              ));

            if($ugp_add>1){
              $ugp_status = 'Saved';
            }
            else{
              $ugp_status = 'Failed';
            }
          }
          else{
            $ugp_status = 'Updated';
          }

          $email_update_check = $sql->getOne('SELECT id FROM User U WHERE U.id='.$app['user_id'].' AND U.mad_email="'.$email.'"');

          if(empty($email_update_check) && $email!=''){
            $mad_email_add = $sql->Update('User',array(
              'mad_email' => $email
            ),'id='.$app['user_id']);

            if($mad_email_add>1){
              $email_status = 'Saved';
            }
            else{
              $email_status = 'Failed';
            }
          }
          else{
            $email_status = 'Updated';
          }

          if($email==''){
            $email_status = 'Not Found';
          }


          echo '<tr>'.
                '<td>'.$app['user_id'].'</td>'.
                '<td>'.$app['name'].'</td>'.
                '<td>'.$app['email'].'</td>'.
                '<td>'.$app['sex'].'</td>'.
                '<td>'.$app['city'].'</td>'.
                '<td>'.$app['role'].'</td>'.
                '<td><b>'.$ugp_status.'</b></td>'.
                '<td><b>'.$email_status.'</b> <em>'.$email.'</em></td>'.
              '</tr>';
        }
      ?>
    </tbody>
  </table>
  </div>
</div>

<?php

function highlight($applications, $requirements) {
    global $multiplication_factor;

    if($applications < $requirements * $multiplication_factor) echo ' class="error-message"';
    else echo ' class="success-message"';
}
