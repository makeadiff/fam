<div class="x_panel">
  <div class="x_title">
    <h2>Selected Fellows</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <form name="shelter_selection" action="./shelter_selection.php" method="POST">
    <input type="hidden" name ="user_id" value="<?php echo $user_id; ?>">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr>
      <th class="city-name">City</th>
      <th class="Shelter Ops Fellow"> Shelter Operations Fellow & Shelter Assignment</th>
      <!-- <th class="Shelter Ops Fellow"> Shelter Assigned</th> -->
    </tr>
    </thead>

    <tbody>
  <?php
  $total_verticals = [];
  $total_cities = [];

  foreach($all_cities as $city_id => $city_name) { ?>
  <tr><th class="city-name"><?php echo $city_name ?></th>
  <?php foreach($verticals as $group_id => $group_name) {
			if(isset($applications[$city_id][$group_id])) $fellow_names = $applications[$city_id][$group_id];
			else $fellow_names = '';

		?>

    <td class="bordered">
      <?php foreach ($fellow_names as $fellow){ ?>
        <div class="row">
          <div class="col-sm-6">
            <?php echo $fellow['fellow']; ?>
          </div>
          <div class="col-sm-6">
            <?php create_shelter($sql,$city_id,$fellow['id'],$fellow['shelter_id']); ?>
          </div>
        </div>
      <?php } ?>
    </td>
    <?php


    ?>
    <?php } ?>
  </tr>
  <?php } ?>

  </tbody>
  </table>
    <input type="submit" value="Save">
  </form>
  </div>
</div>

<?php

function highlight($applications, $requirements) {
    global $multiplication_factor;

    if($applications < $requirements * $multiplication_factor) echo ' class="error-message"';
    else echo ' class="success-message"';
}

function create_shelter($sql,$city_id,$user_id,$selected_id=0){
  $shelters = $sql->getAll('SELECT id as shelter_id,name FROM Center WHERE status=1 AND city_id ='.$city_id);

  $output  = '<select class="shelter_selection" name="shelter_id_'.$user_id.'">';
  $output .= '<option value="0">Assign Shelter</option>';
  foreach ($shelters as $shelter) {
    if($shelter['shelter_id']==$selected_id){
      $output .= '<option value='.$shelter['shelter_id'].' selected>'.$shelter['name'].'</option>';
    }
    else{
      $output .= '<option value='.$shelter['shelter_id'].'>'.$shelter['name'].'</option>';
    }

  }

  $output .= '</select>';

  echo $output;
}
