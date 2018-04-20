
<div class="x_panel">
	<div class="x_title">
		<h2>City Data</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<form action="task-dashboard.php" method="post" class="form-area">
		<?php $html->buildInput("city_id", "Select City", 'select', $city_id, ['options' => $all_cities, 'no_br' => true]); ?> &nbsp;
		<input type="submit" class="btn btn-success btn-xs" value="Filter" />
	</form><br />
  <!-- <a href="all_in_one.php">All In One View</a> -->
	</div>
</div>

<div class="x_panel">
	<div class="x_title">
		<h2>Vertical Wise Task Upload/Evaluated Information</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted font-13 m-b-30">

		</p>
	<?php foreach($verticals as $group_id => $title) {
					if(!$requirements[$city_id][$group_id]) continue;
	?>
		<div class="col-md-2 boxes">
			<p class="vertical-name"><a href="applicants.php?group_id=<?php echo $group_id ?>&city_id=<?php echo $city_id ?>&action=Filter"><?php echo $title ?></a></p>
			<input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="<?php
					if($total_evaluated[$group_id] > $shortlisted[$group_id]) echo $total_evaluated[$group_id];
					else echo $shortlisted[$group_id];
				?>" data-linecap="round" data-fgColor="<?php
					if($total_submitted[$group_id] > $total_evaluated[$group_id]) echo '#f6b26b';
					else echo '#26B99A';
					 ?>" value="<?php echo $total_evaluated[$group_id]; ?>" data-readOnly="true" /><br />
			Task Evaluated: <strong><?php echo $total_evaluated[$group_id]; ?></strong><br />
			Task Submitted: <strong><?php echo $total_submitted[$group_id]; ?></strong><br />
			Shortlisted Applicants: <strong><?php echo $shortlisted[$group_id] ?></strong><br />
			<hr>
		</div>
		<?php } ?>
	</div>
</div>

<div class="x_panel">
  <div class="x_title">
    <h2>Overall Task Upload/Evaluated Status</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr><th class="city-name">City</th>
      <?php foreach($verticals as $group_id => $group_name) { ?><th class="bordered" colspan="3"><?php echo $group_name ?></th><?php } ?>
      <th class="bordered" colspan="3">Total</th>
      <!-- <th class="city-name bordered">City</th> -->
    </tr>
    <tr><th class="city-name">&nbsp;</th>
      <?php for($i = 0; $i <= count($verticals); $i++) { ?>
				<th class="bordered">Shortlisted Applicants</th>
				<th>Task Submitted</th>
				<th>Task Evaluated</th>
			<?php } ?>
      <th class="city-name bordered">&nbsp;</th>
    </tr>
    </thead>

    <tbody>
  <?php
  $total_verticals = [];
  $total_cities = [];
	// dump($verticals);
	// dump($all_cities);
  foreach($all_cities as $city_id => $city_name) {
		if($city_id==0) continue;
	?>
  <tr><th class="city-name"><?php echo $city_name ?></th>
  <?php
	foreach($verticals as $group_id => $group_name) {
    if(!isset($total_verticals[$group_id]['applications'])) $total_verticals[$group_id]['applications'] = 0;
		if(!isset($total_verticals[$group_id]['submitted'])) $total_verticals[$group_id]['submitted'] = 0;
		if(!isset($total_verticals[$group_id]['evaluated'])) $total_verticals[$group_id]['evaluated'] = 0;

		if(isset($applications[$city_id][$group_id])) {
			$total_verticals[$group_id]['applications'] += i($applications[$city_id], $group_id, 0);
			$total_verticals[$group_id]['submitted'] += i($submitted[$city_id], $group_id, 0);
			$total_verticals[$group_id]['evaluated'] += i($evaluated[$city_id], $group_id, 0);
		}

    if(!isset($total_cities[$city_id]['applications'])) $total_cities[$city_id]['applications'] = 0;
		if(!isset($total_cities[$city_id]['submitted'])) $total_cities[$city_id]['submitted'] = 0;
		if(!isset($total_cities[$city_id]['evaluated'])) $total_cities[$city_id]['evaluated'] = 0;

    if(isset($applications[$city_id][$group_id])){
			$total_cities[$city_id]['applications'] += i($applications[$city_id], $group_id, 0);
			$total_cities[$city_id]['submitted'] += i($submitted[$city_id], $group_id, 0);
			$total_cities[$city_id]['evaluated'] += i($evaluated[$city_id], $group_id, 0);
		}
    ?>
    <td class="bordered"><?php echo i($applications[$city_id], $group_id, 0); ?></td>
    <td <?php highlight(i($submitted[$city_id], $group_id, 0), i($applications[$city_id], $group_id, 0)); ?>><?php echo i($submitted[$city_id], $group_id, 0); ?></td>
		<td <?php highlight(i($evaluated[$city_id], $group_id, 0), i($submitted[$city_id], $group_id, 0)); ?>><?php echo i($evaluated[$city_id], $group_id, 0); ?></td>
    <?php } ?>
    <td class="bordered"><?php echo $total_cities[$city_id]['applications'] ?></td>
    <td <?php highlight($total_cities[$city_id]['submitted'], $total_cities[$city_id]['applications']); ?>><?php echo $total_cities[$city_id]['submitted'] ?></td>
		<td <?php highlight($total_cities[$city_id]['evaluated'], $total_cities[$city_id]['submitted']); ?>><?php echo $total_cities[$city_id]['evaluated'] ?></td>
  </tr>
  <?php }
	// dump($total_verticals);
	?>
	<!-- ) -->
  <tr>
		<th class="city-name">Total</th>
      <?php
      // $total_required = 0;
      $total_applied = 0;
			$total_submitted = 0;
			$total_evaluated = 0;
      foreach($verticals as $group_id => $group_name) {
        $total_applied += $total_verticals[$group_id]['applications'];
				$total_submitted += $total_verticals[$group_id]['submitted'];
				$total_evaluated += $total_verticals[$group_id]['evaluated'];
        ?>
      <td class="bordered"><?php echo $total_verticals[$group_id]['applications']; ?></td>
      <td <?php highlight($total_verticals[$group_id]['submitted'], $total_verticals[$group_id]['applications']); ?>><?php echo $total_verticals[$group_id]['submitted']; ?></td>
			<td <?php highlight($total_verticals[$group_id]['evaluated'], $total_verticals[$group_id]['submitted']); ?>><?php echo $total_verticals[$group_id]['evaluated']; ?></td>
      <?php } ?>
      <td class="bordered"><?php echo $total_applied ?></td>
      <td <?php highlight($total_submitted, $total_applied); ?>><?php echo $total_submitted ?></td>
			<td <?php highlight($total_evaluated, $total_submitted); ?>><?php echo $total_evaluated ?></td>
      <!-- <th class="bordered">Total</th> -->
  </tr>
  </tbody>
  </table>
  </div>


	<!-- <script type="text/javascript">
	    window.onload = function () {
		    var gridViewScroll = new GridViewScroll({
		        elementID : "data-table", // String
		        freezeColumn : true, // Boolean
		        freezeColumnCount : 1
		    });
        gridViewScroll.enhance();
			};
	</script> -->
</div>

<?php

function highlight($applications, $requirements) {
    global $multiplication_factor;

    if($applications < $requirements * $multiplication_factor) echo ' class="error-message"';
    else echo ' class="success-message"';
}
