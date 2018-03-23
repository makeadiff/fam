<div class="x_panel">
  <div class="x_title">
    <h2>All in One View</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr><th class="city-name">City</th>
      <?php foreach($verticals as $group_id => $group_name) { ?><th class="bordered" colspan="2"><?php echo $group_name ?></th><?php } ?>
      <th class="bordered" colspan="2">Total</th>
      <th class="city-name bordered">City</th>
    </tr>
    <tr><th class="city-name">&nbsp;</th>
      <?php for($i = 0; $i <= count($verticals); $i++) { ?><th class="bordered">Req.</th><th>Applied</th><?php } ?>
      <th class="city-name bordered">&nbsp;</th>
    </tr>
    </thead>

    <tbody>
  <?php 
  $total_verticals = [];
  $total_cities = [];

  foreach($all_cities as $city_id => $city_name) { ?>
  <tr><th class="city-name"><?php echo $city_name ?></th>
  <?php foreach($verticals as $group_id => $group_name) {
    if(!isset($total_verticals[$group_id]['requirements'])) $total_verticals[$group_id]['requirements'] = 0;
    if(!isset($total_verticals[$group_id]['applications'])) $total_verticals[$group_id]['applications'] = 0;
    $total_verticals[$group_id]['requirements'] += $requirements[$city_id][$group_id];
    $total_verticals[$group_id]['applications'] += i($applications[$city_id], $group_id, 0);

    if(!isset($total_cities[$city_id]['requirements'])) $total_cities[$city_id]['requirements'] = 0;
    if(!isset($total_cities[$city_id]['applications'])) $total_cities[$city_id]['applications'] = 0;
    $total_cities[$city_id]['requirements'] += $requirements[$city_id][$group_id];
    $total_cities[$city_id]['applications'] += i($applications[$city_id], $group_id, 0);
    ?>
    <td class="bordered"><?php echo $requirements[$city_id][$group_id] ?></td>
    <td <?php highlight(i($applications[$city_id], $group_id, 0), $requirements[$city_id][$group_id]); ?>><?php echo i($applications[$city_id], $group_id, 0); ?></td>
    <?php } ?>
    <td class="bordered"><?php echo $total_cities[$city_id]['requirements'] ?></td>
    <td <?php highlight($total_cities[$city_id]['applications'], $total_cities[$city_id]['requirements']); ?>><?php echo $total_cities[$city_id]['applications'] ?></td>
    <th class="city-name bordered"><?php echo $city_name ?></th>
  </tr>
  <?php } ?>
  <tr><th class="city-name">Total</th>
      <?php 
      $total_required = 0;
      $total_applied = 0;
      foreach($verticals as $group_id => $group_name) {
        $total_required += $total_verticals[$group_id]['requirements'];
        $total_applied += $total_verticals[$group_id]['applications'];
        ?>
      <td class="bordered"><?php echo $total_verticals[$group_id]['requirements'] ?></td>
      <td <?php highlight($total_verticals[$group_id]['applications'], $total_verticals[$group_id]['requirements']); ?>><?php echo $total_verticals[$group_id]['applications']; ?></td>
      <?php } ?>
      <td class="bordered"><?php echo $total_required ?></td>
      <td <?php highlight($total_applied, $total_required); ?>><?php echo $total_applied ?></td>
      <th class="bordered">Total</th>
  </tr>
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
