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
			if(isset($applications[$city_id][$group_id])) $fellow_names = $applications[$city_id][$group_id];
			else $fellow_names = '';


		?>
    <td width="150px" class="bordered"><?php echo ucwords(strtolower(str_replace(',',' <br> ',$fellow_names))) ?></td>
    <?php } ?>
  </tr>
  <?php } ?>
  </tbody>
  </table>
  </div>
</div>
