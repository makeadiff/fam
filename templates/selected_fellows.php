<div class="x_panel">

  <?php
		if(!$is_director) die("You don't have access to this view");
	?>

  <div class="x_title">
    <h2>Selected Fellows</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr><th class="city-name">City</th>
      <?php foreach($verticals as $group_id => $group_name) { ?><th width="150px" class="bordered"><?php echo $group_name ?></th><?php } ?>
    </tr>
    </thead>

    <tbody>
  <?php
  $total_verticals = [];
  $total_cities = [];

  foreach($all_cities as $city_id => $city_name) { ?>
  <tr><th class="city-name"><?php echo $city_name ?></th>
  <?php foreach($verticals as $group_id => $group_name) {

      $fellows = [];
			if(isset($applications[$city_id][$group_id]) && $applications[$city_id][$group_id]['fellow_names']!="") {
          $fellow_names = $applications[$city_id][$group_id]['fellow_names'];
          $fellows = explode(',',$fellow_names);
      }
			else {
        $fellow_names = '';
      }


		?>
    <td width="150px" class="bordered">
      <?php
        if(!empty($fellows)){
          echo '<ol>';
          foreach ($fellows as $fellow) {
            echo '<li>'.ucwords(strtolower($fellow)).'</li>';
          }
          echo '</ol>';
        }
      ?>
    </td>
    <?php } ?>
  </tr>
  <?php } ?>
  </tbody>
  </table>
  </div>
</div>
