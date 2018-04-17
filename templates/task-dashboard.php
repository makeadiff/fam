
<div class="x_panel">
	<div class="x_title">
		<h2>City Data</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<form action="dashboard.php" method="post" class="form-area">
		<?php $html->buildInput("city_id", "Select City", 'select', $city_id, ['options' => $all_cities, 'no_br' => true]); ?> &nbsp;
		<input type="submit" class="btn btn-success btn-xs" value="Filter" />
	</form><br />
  <a href="all_in_one.php">All In One View</a>
	</div>
</div>

<div class="x_panel">
	<div class="x_title">
		<h2>Vertical Wise Task Upload Information</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted font-13 m-b-30">
			Requirement Data comes from the spreadsheet <a href="https://docs.google.com/spreadsheets/d/150mVAUvisYObaW2MVUZfi2tjbKxvd2tZalB3gfr091o/edit?ts=5aacf12d#gid=675197629">Succession 2018 - Fellow Requirement</a>
		</p>
	<?php foreach($verticals as $group_id => $title) {
					if(!$requirements[$city_id][$group_id]) continue;
	?>
		<div class="col-md-2 boxes">
			<p class="vertical-name"><a href="applicants.php?group_id=<?php echo $group_id ?>&city_id=<?php echo $city_id ?>&action=Filter"><?php echo $title ?></a></p>
			<input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="<?php
					$target = $requirements[$city_id][$group_id] * $multiplication_factor;
					if($applicants[$group_id] > $target) echo $applicants[$group_id];
					else echo $target;
				?>" data-linecap="round" data-fgColor="<?php
					if($requirements[$city_id][$group_id] > $applicants[$group_id]) echo '#a62c37';
					elseif(($requirements[$city_id][$group_id] * 2) > $applicants[$group_id]) echo '#f6b26b';
					else echo '#26B99A';
					 ?>" value="<?php echo $applicants[$group_id] ?>" data-readOnly="true" /><br />
			Requirement: <strong><?php echo $requirements[$city_id][$group_id] ?></strong><br />
			Applicant Count: <strong><?php echo $applicants[$group_id] ?></strong><br />
			Target: <strong><?php echo ($requirements[$city_id][$group_id] * $multiplication_factor) ?></strong><br />
		</div>
		<?php } ?>
	</div>
</div>

<div class="x_panel">
  <div class="x_title">
    <h2>Task Upload Status</h2>
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
