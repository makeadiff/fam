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
 
<div class="x-panel">
  <!-- top tiles -->
  <div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Total Sign Ups</span>
      <div class="count"><?php echo $total_filled ?></div>
      <span class="count_bottom"><i class="green"><?php echo $total_volunteers - $total_filled ?> </i> Left to Sign Up</span>
    </div>

    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-search"></i> Positions Open</span>
      <div class="count"><?php echo $requirements['total_city'][$city_id] ?></div>
      <span class="count_bottom"><i class="green"><?php echo $requirements['total_city'][$city_id] * $multiplication_factor ?> </i> Target Sign Up Count</span>
    </div>
  </div>
  <!-- /top tiles -->
 
  <div class="x_panel">
    <div class="x_title">
      <h2>Requirements</h2>
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

    <strong>Legend</strong><br />
    <span style="background-color:#a62c37; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>less than requirements</strong>.<br />
    <span style="background-color:#f6b26b; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>less than 2 x</strong> the requirements.<br />
    <span style="background-color:#26B99A; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>more than 2 x</strong> the requirements.<br />
  </div>
</div>
