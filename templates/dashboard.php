<div class="x_panel">
	<div class="x_title">
		<h2>City Data</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<form action="dashboard.php" method="post" class="form-area">
		<?php $html->buildInput("city_id", "Select City", 'select', $city_id, ['options' => $all_cities, 'no_br' => true]); ?> &nbsp; 
		<input type="submit" class="btn btn-success btn-xs" value="Filter" />
	</form>
	</div>
</div>

<!-- page content -->
<div class="right_col" role="main">
  <!-- top tiles -->
  <div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Total Sign Ups</span>
      <div class="count"><?php echo $total_filled ?></div>
      <span class="count_bottom"><i class="green"><?php echo $total_volunteers - $total_filled ?> </i> Left to Sign Up</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Fellowship Applications</span>
      <div class="count green"><?php echo $fellowship_applications ?></div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Wingmen Applications</span>
      <div class="count"><?php echo $wingman_applications; ?></div>
    </div>
  </div>
  <!-- /top tiles -->

  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Requirements</h2>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <p class="text-muted font-13 m-b-30">
          Requirement Data comes from the spreadsheet <a href="https://docs.google.com/spreadsheets/d/150mVAUvisYObaW2MVUZfi2tjbKxvd2tZalB3gfr091o/edit?ts=5aacf12d#gid=675197629">Succession 2018 - Fellow Requirement</a>
        </p>
	    <?php foreach($verticals as $group_id => $title) { ?>
        <div class="col-md-2 boxes">
          <p class="vertical-name"><?php echo $title ?></p>
          <input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="<?php echo $requirements[$city_id][$group_id] ?>" 
          	data-linecap="round" data-fgColor="#26B99A" value="<?php echo $applicants[$group_id] ?>" data-readOnly="true" /><br />
          Requirement: <strong><?php echo $requirements[$city_id][$group_id] ?></strong><br />
          Applicant Count: <strong><?php echo $applicants[$group_id] ?></strong><br />
          Selected Count: <strong><?php echo $selected[$group_id] ?></strong><br />
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

</div>
<!-- /page content -->
