<div class="x_panel">

  <?php
		if(!$is_director) die("You don't have access to this view");
	?>

  <div class="x_title">
    <h2>All in One View</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr><th class="city-name">City</th>
      <?php foreach($verticals as $group_id => $group_name) { ?><th class="bordered" colspan="3"><?php echo $group_name ?></th><?php } ?>
      <th class="bordered" colspan="3">Total</th>
      <th class="city-name bordered">City</th>
    </tr>
    <tr><th class="city-name">&nbsp;</th>
      <?php for($i = 0; $i <= count($verticals); $i++) { ?><th class="bordered">Req.</th><th>Applied</th><th>Selected</th><?php } ?>
      <th class="city-name bordered">&nbsp;</th>
    </tr>
    </thead>

    <tbody>
  <?php
  $total_verticals = [];
  $total_cities = [];
  foreach($all_cities as $city_id => $city_name) { ?>
  <tr>
    <th class="city-name"><?php echo $city_name ?></th>
    <?php foreach($verticals as $group_id => $group_name) {
      if(!isset($total_verticals[$group_id]['requirements'])) $total_verticals[$group_id]['requirements'] = 0;
      if(!isset($total_verticals[$group_id]['applications'])) $total_verticals[$group_id]['applications'] = 0;
      if(!isset($total_verticals[$group_id]['selected'])) $total_verticals[$group_id]['selected'] = 0;
      $total_verticals[$group_id]['requirements'] += i($requirements[$city_id], $group_id, 0);
      $total_verticals[$group_id]['applications'] += i($applications[$city_id], $group_id, 0);
      $total_verticals[$group_id]['selected'] += i($selected[$city_id], $group_id, 0);

      if(!isset($total_cities[$city_id]['requirements'])) $total_cities[$city_id]['requirements'] = 0;
      if(!isset($total_cities[$city_id]['applications'])) $total_cities[$city_id]['applications'] = 0;
      if(!isset($total_cities[$city_id]['selected'])) $total_cities[$city_id]['selected'] = 0;
      $total_cities[$city_id]['requirements'] += i($requirements[$city_id], $group_id, 0);
      $total_cities[$city_id]['applications'] += i($applications[$city_id], $group_id, 0);
      $total_cities[$city_id]['selected'] += i($selected[$city_id], $group_id, 0);
    ?>
    <td class="bordered"><?php echo $requirements[$city_id][$group_id] ?></td>
    <td <?php highlight(i($applications[$city_id], $group_id, 0), $requirements[$city_id][$group_id]); ?>><?php echo i($applications[$city_id], $group_id, 0); ?></td>
    <td <?php highlight(i($selected[$city_id], $group_id, 0), $requirements[$city_id][$group_id], 1); ?>><?php echo i($selected[$city_id], $group_id, 0); ?></td>
    <?php } ?>
    <td class="bordered"><?php echo $total_cities[$city_id]['requirements'] ?></td>
    <td <?php highlight($total_cities[$city_id]['applications'], $total_cities[$city_id]['requirements']); ?>><?php echo $total_cities[$city_id]['applications'] ?></td>
    <td <?php highlight($total_cities[$city_id]['selected'], $total_cities[$city_id]['applications'], 'selected'); ?>><?php echo $total_cities[$city_id]['selected'] ?></td>
    <th class="city-name bordered"><?php echo $city_name ?></th>
  </tr>
  <?php } ?>
  <tr><th class="city-name">Total</th>
    <?php
      $total_required = 0;
      $total_applied = 0;
      $total_selected = 0;
      foreach($verticals as $group_id => $group_name) {
        $total_required += $total_verticals[$group_id]['requirements'];
        $total_applied += $total_verticals[$group_id]['applications'];
        $total_selected += $total_verticals[$group_id]['selected'];
    ?>
      <td class="bordered"><?php echo $total_verticals[$group_id]['requirements'] ?></td>
      <td <?php highlight($total_verticals[$group_id]['applications'], $total_verticals[$group_id]['requirements']); ?>><?php echo $total_verticals[$group_id]['applications']; ?></td>
      <td <?php highlight($total_verticals[$group_id]['selected'], $total_verticals[$group_id]['applications'], 'selected'); ?>><?php echo $total_verticals[$group_id]['selected']; ?></td>
    <?php } ?>
      <td class="bordered"><?php echo $total_required ?></td>
      <td <?php highlight($total_applied, $total_required); ?>><?php echo $total_applied ?></td>
      <td><?php echo $total_selected; ?></td>
      <th class="bordered">Total</td>
  </tr>
  </tbody>

  <thead>
    <tr><th class="city-name">&nbsp;</th>
      <?php for($i = 0; $i <= count($verticals); $i++) { ?><th class="bordered">Req.</th><th>Applied</th><th>Selected</th><?php } ?>
      <th class="city-name bordered">&nbsp;</th>
    </tr
    <tr><th class="city-name">City</th>
      <?php foreach($verticals as $group_id => $group_name) { ?><th class="bordered" colspan="3"><?php echo $group_name ?></th><?php } ?>
      <th class="bordered" colspan="3">Total</th>
      <th class="city-name bordered">City</th>
    </tr>
  </thead>

  </table>

  <strong>Legend</strong><br />
  <span style="background-color:<?php echo $colors['red'] ?>; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>less than requirements</strong>.<br />
  <span style="background-color:<?php echo $colors['orange'] ?>; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>less than 2 x</strong> the requirements.<br />
  <span style="background-color:<?php echo $colors['green'] ?>; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>more than 2 x</strong> the requirements.<br />
  </div>
</div>

<?php

function highlight($count, $requirements, $mode = 'applied') {
  global $multiplication_factor, $colors;

  if($mode == 'applied') {
    if($count > $requirements * $multiplication_factor) echo " style='color:{$colors['green']}; font-weight:bold;'";
    else if($count > $requirements) echo " style='color:{$colors['orange']}; font-weight:bold;'";
    else echo " style='color:{$colors['red']}; font-weight:bold;'";
  } else {
    if($count >= $requirements) echo " style='color:{$colors['green']}; font-weight:bold;'";
    else if($count > 1) echo " style='color:{$colors['orange']}; font-weight:bold;'";
    else echo " style='color:{$colors['red']}; font-weight:bold;'";
  }
}
