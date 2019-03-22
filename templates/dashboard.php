<?php
$colors = [
  'green'   => '#a62c37',
  'orange'  => '#f6b26b',
  'red'     => '#26B99A'
];
?><div class="x_panel">
	<div class="x_title">
		<h2>City Data</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<form action="dashboard.php" method="post" class="form-area">
		<?php $html->buildInput("city_id", "Select City", 'select', $city_id, ['options' => $all_cities, 'no_br' => true]); ?> &nbsp;
    <?php $html->buildInput("group_id", "Select Vertical", 'select', $group_id, ['options' => $all_verticals, 'no_br' => true]); ?> &nbsp;
		<input type="submit" class="btn btn-success btn-xs" value="Filter" />
	</form><br />
  <a href="all_in_one.php">All In One View</a>
	</div>
</div>

<div class="x-panel">
  <!-- top tiles -->
  <div class="row tile_count">
    <div class="fellowship-signup">
    <?php
      if($group_id!=8){
    ?>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Fellowship Sign Ups</span>
        <div class="count"><?php echo $total_filled ?></div>
        <span class="count_bottom"><i class="green"><?php echo $total_volunteers - $total_filled ?> </i> Left to Sign Up</span>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-search"></i> Fellowship Positions Open</span>
        <div class="count">
          <?php
            if($group_id==0){
              echo $requirements['total_city'][$city_id];
            }
            else{
              echo $requirements[$city_id][$group_id];
            }
          ?>
        </div>
        <span class="count_bottom"><i class="green">
          <?php
            if($group_id==0){
              echo $requirements['total_city'][$city_id] * $multiplication_factor;
            }
            else{
              echo $requirements[$city_id][$group_id] * $multiplication_factor;
            }
          ?>
          </i> Target Sign Up Count</span>
      </div>
    </div>
    <div class="mentor-signup">
    <?php
      }
      if ($group_id==0 || $group_id==GROUP_ID_MENTOR ) {
    ?>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Mentor Sign Ups</span>
        <div class="count"><?php echo $applicants[$city_id][GROUP_ID_MENTOR]; ?></div>
        <span class="count_bottom"><i class="green"><?php echo $total_volunteers - $applicants[$city_id][GROUP_ID_MENTOR]; ?> </i> Left to Sign Up</span>
      </div>

      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-search"></i> Mentor Positions Open</span>
        <div class="count"><?php echo $requirements[$city_id][GROUP_ID_MENTOR] ?></div>
        <span class="count_bottom"><i class="green"><?php echo $requirements[$city_id][GROUP_ID_MENTOR] * $mentor_multiplication_factor ?> </i> Target Sign Up Count</span>
      </div>
    </div>
    <?php
      }
    ?>
  </div>
  <!-- /top tiles -->

  <div class="x_panel">
    <div class="x_title">
      <h2>Requirements</h2>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">
      <p class="text-muted font-13 m-b-30">
        Requirement Data comes from the spreadsheet <a href="https://docs.google.com/spreadsheets/d/1FsypDbY5KDpTwD5696Hz0ZSd1UZpMyrFNauoDWvLBGQ/edit?ts=5c90f9b4#gid=675197629">Succession 2019 - Strat/Fellow Requirement</a>
      </p>
      <?php
      if($group_id == 0) {
        foreach($verticals as $this_group_id => $title) {
          $multiplication_factor_for_group = $multiplication_factor;
          if($this_group_id == GROUP_ID_MENTOR) $multiplication_factor_for_group = 1; // Special treatment for Mentors - target is the requirement count.
          if(!$requirements[$city_id][$this_group_id]) continue;
      ?>
      <div class="col-md-2 boxes">
        <p class="vertical-name"><a href="applicants.php?vertical_group_id=<?php echo $this_group_id ?>&city_id=<?php echo $city_id ?>&action=Filter"><?php echo $title ?></a></p>
        <input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="<?php
            $target = $requirements[$city_id][$this_group_id] * $multiplication_factor_for_group;
            if($applicants[$city_id][$this_group_id] > $target) echo $applicants[$city_id][$this_group_id];
            else echo $target;
          ?>" data-linecap="round" data-fgColor="<?php
            if($requirements[$city_id][$this_group_id] > $applicants[$city_id][$this_group_id]) echo $colors['green'];
            elseif(($requirements[$city_id][$this_group_id] * 2) > $applicants[$city_id][$this_group_id]) echo $colors['orange'];
            else echo $colors['red'];
             ?>" value="<?php echo $applicants[$city_id][$this_group_id] ?>" data-readOnly="true" /><br />
        Target: <strong><?php echo ($requirements[$city_id][$this_group_id] * $multiplication_factor_for_group) ?></strong><br />
        Requirement: <strong><?php echo $requirements[$city_id][$this_group_id] ?></strong><br />
        Applicant Count: <strong><?php echo $applicants[$city_id][$this_group_id] ?></strong><br />
      </div>
      <?php
        }
      } else {
        foreach($all_cities as $this_city_id => $city_name) {
          $multiplication_factor_for_group = $multiplication_factor;
          if($group_id == GROUP_ID_MENTOR) $multiplication_factor_for_group = 1; // Special treatment for Mentors - target is the requirement count.
          if($city_id and $this_city_id != $city_id) continue;
          if(!isset($requirements[$this_city_id]) or !$requirements[$this_city_id][$group_id]) continue;
           $target = $requirements[$this_city_id][$group_id] * $multiplication_factor_for_group;
      ?>
      <div class="col-md-2 boxes">
        <p class="vertical-name"><a href="applicants.php?group_id=<?php echo $group_id ?>&city_id=<?php echo $this_city_id ?>&action=Filter"><?php echo $city_name ?></a></p>
        <input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="<?php
            if($applicants[$this_city_id][$group_id] > $target) echo $applicants[$this_city_id][$group_id];
            else echo $target;
          ?>" data-linecap="round" data-fgColor="<?php
            if($requirements[$this_city_id][$group_id] > $applicants[$this_city_id][$group_id]) echo $colors['green'];
            elseif(($requirements[$this_city_id][$group_id] * 2) > $applicants[$this_city_id][$group_id]) echo $colors['orange'];
            else echo $colors['red'];
             ?>" value="<?php echo $applicants[$this_city_id][$group_id] ?>" data-readOnly="true" /><br />
        Target: <strong><?php echo ($requirements[$this_city_id][$group_id] * $multiplication_factor_for_group); ?></strong><br />
        Requirement: <strong><?php echo $requirements[$this_city_id][$group_id] ?></strong><br />
        Applicant Count: <strong><?php echo $applicants[$this_city_id][$group_id] ?></strong><br />
      </div>
      <?php }
      } ?>
    </div>

    <strong>Legend</strong><br />
    <span style="background-color:<?php echo $colors['red'] ?>; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>less than requirements</strong>.<br />
    <span style="background-color:<?php echo $colors['orange'] ?>; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>less than 2 x</strong> the requirements.<br />
    <span style="background-color:<?php echo $colors['green'] ?>; border:1px solid #000;"> &nbsp; </span>&nbsp; Number of Applicants are <strong>more than 2 x</strong> the requirements.<br />
  </div>
</div>
