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
      <span class="count_top"><i class="fa fa-user"></i> Mentor Applications</span>
      <div class="count"><?php echo $mentor_applications ?></div>
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
	    <?php foreach($verticals as $slug => $title) { ?>
        <div class="col-md-2">
          <p class="vertical-name"><?php echo $title ?></p>
          <input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="<?php echo $requirements[$slug] ?>" 
          	data-linecap="round" data-fgColor="#26B99A" value="<?php echo $applicants[$slug] ?>">
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

</div>
<!-- /page content -->
