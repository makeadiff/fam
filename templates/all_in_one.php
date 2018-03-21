<div class="x_panel">
  <div class="x_title">
    <h2>All in One View</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <tr><th>City</th>
      <?php foreach($verticals as $group_id => $group_name) { ?><th class="bordered" colspan="2"><?php echo $group_name ?></th><?php } ?>
      <th class="bordered" colspan="2">Total</th>
    </tr>
    <tr><th>&nbsp;</th>
      <?php for($i = 0; $i <= count($verticals); $i++) { ?><th class="bordered">Req.</th><th>Applied</th><!-- <th>Selected</th> --><?php } ?>
    </tr>

  <?php 
  $total_verticals = [];
  $total_cities = [];

  foreach($all_cities as $city_id => $city_name) { ?>
  <tr><th><?php echo $city_name ?></th>
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
    <td <?php if(i($applications[$city_id], $group_id, 0) < ($requirements[$city_id][$group_id] * $multiplication_factor)) 
                  echo ' class="error-message"'; else echo ' class="success-message"'; ?>><?php echo i($applications[$city_id], $group_id, 0); ?></td>
    <!-- <td>< ?php echo i($selected[$city_id], $group_id, 0) ?></td> -->
    <?php } ?>
    <td class="bordered"><?php echo $total_cities[$city_id]['requirements'] ?></td>
    <td <?php if($total_cities[$city_id]['applications'] < ($total_cities[$city_id]['requirements'] * $multiplication_factor)) 
                  echo ' class="error-message"'; else echo ' class="success-message"'; ?>><?php echo $total_cities[$city_id]['applications'] ?></td>
  </tr>
  <?php } ?>
  <tr><th>Total</th><?php foreach($verticals as $group_id => $group_name) { ?>
      <td class="bordered"><?php echo $total_verticals[$group_id]['requirements'] ?></td>
      <td <?php if($total_verticals[$group_id]['applications'] < ($total_verticals[$group_id]['requirements'] * $multiplication_factor)) 
                  echo ' class="error-message"'; else echo ' class="success-message"'; ?>><?php echo $total_verticals[$group_id]['applications'] ?></td>
    <?php } ?>
  </table>
  </div> 
</div>
