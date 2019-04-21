
<link href="<?php echo $config['site_home'] ?>css/all_in_one.css" rel="stylesheet">

<div class="x_panel">
	<div class="x_title">
		<h2>City Data</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<form action="task-dashboard.php" method="post" class="form-area">
		<?php $html->buildInput("city_id", "Select City", 'select', $city_id, ['options' => $all_cities, 'no_br' => true]); ?> &nbsp;
		<?php $html->buildInput("group_id", "Select Role", 'select', $group_id, ['options' => $all_verticals, 'no_br' => true]); ?> &nbsp;
		<?php $html->buildInput("task_type", "Task Type", 'select', $task_type, ['options' => $all_tasks, 'no_br' => true]); ?> &nbsp;
		<?php $html->buildInput("evaluation_status", "Evaluation Status", 'select', $evaluation_status, ['options' => $evaluation_statuses, 'no_br' => true]); ?> &nbsp;
		<input type="submit" class="btn btn-success btn-xs" value="Filter" />
	</form><br />
  <!-- <a href="all_in_one.php">All In One View</a> -->
	</div>
</div>


<div class="x-panel">

	<div class="row tile_count">
    <div class="fellowship-signup">
    <?php if($group_id != 8) { ?>
	    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i>
		<?php 	if($task_type!='vertical'){ ?>
					Total Fellowship Sign Ups
		<?php 	}else{ ?>
					Total Eligible Applications
		<?php 	} ?>
				</span>
        <div class="count">
				<?php
					if($group_id==0 && $city_id==0){
						echo $all_applied;
					}
					else if($city_id==0 && $group_id!=0){
						echo $total_verticals[$group_id]['applications'];
					}
					elseif($city_id!=0 && $group_id==0){
						echo $total_cities[$city_id]['applications'];
					}
					else{
						echo i($applications[$city_id], $group_id, 0);
					}
				?>
				</div>
	    </div>

			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Fellowship Profiles Open</span>
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
	    </div>

			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Expected Tasks</span>
        <div class="count">
					<?php
						if($group_id==0 && $city_id==0){
							echo $all_applied - $all_not_required;;
						}
						else if($city_id==0 && $group_id!=0){
							echo $total_verticals[$group_id]['applications'] - $total_verticals[$group_id]['not_required'];
						}
						elseif($city_id!=0 && $group_id==0){
							echo $total_cities[$city_id]['applications'] - $total_cities[$city_id]['not_required'];
						}
						else{
							if($task_type!='vertical')
								echo i($applications[$city_id], $group_id, 0) - (i($ctl_ctl_applicants[$city_id], $group_id, 0) + i($nonctl_fellow_applicants[$city_id], $group_id, 0));
							else
								echo i($applications[$city_id], $group_id, 0) - i($same_vertical_applicants[$city_id], $group_id, 0);
						}
					?>
				</div>
	    </div>

	    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
	        <span class="count_top"><i class="fa fa-user"></i> Tasks Submitted</span>
	        <div class="count">
	        <?php
				if($group_id==0 && $city_id==0){
					echo $all_submitted;
				}
				else if($city_id==0 && $group_id!=0){
					echo $total_submitted[$group_id];
				}
				elseif($city_id!=0 && $group_id==0){
					echo $total_cities[$city_id]['submitted'];
				}
				else{
					echo i($submitted[$city_id], $group_id, 0);
				}
	        ?>
	        </div>
	    </div>

		<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
	        <span class="count_top"><i class="fa fa-user"></i> Tasks Evaluated</span>
	        <div class="count">
	          <?php
							if($group_id==0 && $city_id==0){
								echo $all_evaluated;
							}
							else if($city_id==0 && $group_id!=0){
								echo $total_evaluated[$group_id];
							}
							elseif($city_id!=0 && $group_id==0){
								echo $total_cities[$city_id]['evaluated'];
							}
							else{
								echo i($evaluated[$city_id], $group_id, 0);
							}
	          ?>
	        </div>
	    </div>

    <?php } ?>
  	</div>
	</div>
</div>

<div class="x_panel">
	<div class="x_title">
		<?php if($group_id==0){ ?>
			<h2>Vertical Wise Task Upload/Evaluated Information</h2>
		<?php } else { ?>
			<h2>City Wise Task Upload/Evaluated Information</h2>
		<?php } ?>
		<br/>
		<p>Applicant who're <strong>NOT REQUIRED</strong> to submit the tasks are Fellows who are applying for any fellowship roles except for City Team Lead &amp; and City Team Leads who're reapplying for City Team Lead Role</p>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted font-13 m-b-30">

		</p>
<?php
	if($group_id==0){
		foreach($verticals as $this_group_id => $title){
			if(!$requirements[$city_id][$this_group_id]) continue;
			if($this_group_id == 8) continue;

			if($city_id==0)
				$expected = $total_verticals[$id]['applications'] - $total_verticals[$this_group_id]['not_required'];
			else if($city_id!=0 && $task_type=='vertical')
				$expected = i($applications[$city_id],$this_group_id,0) - i($same_vertical_applicants[$city_id], $this_group_id, 0);
			else
				$expected = i($applications[$city_id],$this_group_id,0) - (i($ctl_ctl_applicants[$city_id], $this_group_id, 0) + i($nonctl_fellow_applicants[$city_id], $this_group_id, 0));
?>
					<div class="col-md-2 boxes">
						<p class="vertical-name">
							<a href="task-status.php?group_id=<?php echo $this_group_id ?>&city_id=<?php echo $city_id ?>&task-status=submitted&action=Filter">
								<strong><?php echo $title ?></strong>
							</a>
						</p>
<?php
			if($evaluation_status=='all'){
?>
								<input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="
<?php
				if(i($submitted[$city_id], $this_group_id, 0) > $expected)
					echo i($submitted[$city_id], $this_group_id, 0);
				else
					echo $expected;
?>
									" data-linecap="round" data-fgColor="
<?php
				if((i($submitted[$city_id], $this_group_id, 0) < $expected/2) || i($applications[$city_id],$this_group_id,0)==0)
					echo $colors['red'];
				else if((i($submitted[$city_id], $this_group_id, 0) < $expected) || i($applications[$city_id],$this_group_id,0)==0)
					echo $colors['orange'];
				else
					echo $colors['green'];
?>
									" value="<?php echo i($submitted[$city_id], $this_group_id, 0); ?>" data-readOnly="true" /><br />
<?php
			}
			else{
?>
								<input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="
<?php
				if(i($evaluated[$city_id],$group_id,0) > i($submitted[$city_id], $this_group_id, 0))
					echo i($evaluated[$city_id],$group_id,0);
				else
					echo i($submitted[$city_id], $this_group_id, 0);
?>
									" data-linecap="round" data-fgColor="
<?php
				if((i($evaluated[$city_id],$group_id,0) < i($submitted[$city_id], $this_group_id, 0)/2) || i($submitted[$city_id], $this_group_id, 0)==0)
					echo $colors['red'];
				else if((i($evaluated[$city_id],$group_id,0) < i($submitted[$city_id], $this_group_id, 0)) || i($submitted[$city_id], $this_group_id, 0)==0)
					echo $colors['orange'];
				else
					echo $colors['green'];
?>
									" value="<?php echo i($evaluated[$city_id],$group_id,0); ?>" data-readOnly="true" /><br />
<?php
			}
?>
						Total Applicants: <strong><?php echo i($applications[$city_id], $this_group_id, 0); ?></strong><br />
						Task(s) Expected: <strong><?php echo $expected ?></strong><br />
						Task(s) Submitted: <strong><?php echo i($submitted[$city_id], $this_group_id, 0); ?></strong><br />
						Task(s) Evaluated: <strong><?php echo i($evaluated[$city_id], $this_group_id, 0); ?></strong><br />
						<hr>
					</div>
<?php
		}
	}else{
		foreach($all_cities as $this_city_id => $title) {

			if($this_city_id == 0 || $this_city_id >= 26) continue;
			if(!$requirements[$this_city_id][$group_id]) continue;
			if($city_id!=0 && $city_id!=$this_city_id) continue;

			else if($task_type=='vertical')
				$expected = i($applications[$this_city_id],$group_id,0) - i($same_vertical_applicants[$this_city_id], $group_id, 0);
			else
				$expected = i($applications[$this_city_id], $group_id, 0) - (i($ctl_ctl_applicants[$this_city_id], $group_id, 0) + i($nonctl_fellow_applicants[$this_city_id], $group_id, 0));
?>
					<div class="col-md-2 boxes">
						<p class="vertical-name"><a href="task-status.php?group_id=<?php echo $this_city_id ?>&city_id=<?php echo $this_city_id ?>&action=Filter"><strong><?php echo $title ?></strong></a></p>
<?php
			if($evaluation_status=='all'){
?>
							<input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="
<?php
				if(i($submitted[$this_city_id], $group_id, 0) > i($applications[$this_city_id], $group_id, 0))
					echo i($submitted[$this_city_id], $group_id, 0);
				else
					echo i($applications[$this_city_id], $group_id, 0);
?>
								" data-linecap="round" data-fgColor="
<?php
				if((i($submitted[$this_city_id], $group_id, 0) < i($applications[$this_city_id], $group_id, 0)/2) && $expected != 0)
					echo $colors['red'];
				else if((i($submitted[$this_city_id], $group_id, 0) < i($applications[$this_city_id], $group_id, 0)) && $expected != 0)
					echo $colors['orange'];
				else
					echo $colors['green'];
?>
						  	" value="<?php echo i($submitted[$this_city_id], $group_id, 0); ?>" data-readOnly="true" /><br />
<?php
			}
			else{
?>
							<input class="knob" data-width="100" data-height="120" data-angleOffset="0" data-min="0" data-max="
<?php
			if(i($evaluated[$this_city_id], $group_id, 0) > i($submitted[$this_city_id], $group_id, 0))
				echo i($evaluated[$this_city_id], $group_id, 0);
			else
				echo i($submitted[$this_city_id], $group_id, 0);
?>
								" data-linecap="round" data-fgColor="
<?php
			if((i($evaluated[$this_city_id], $group_id, 0) < i($submitted[$this_city_id], $group_id, 0)/2) && $expected != 0 )
				echo $colors['red'];
			else if((i($evaluated[$this_city_id], $group_id, 0) < i($submitted[$this_city_id], $group_id, 0)) && $expected != 0 )
				echo $colors['orange'];
			else
				echo $colors['green'];
?>
								" value="<?php echo i($evaluated[$this_city_id], $group_id, 0); ?>" data-readOnly="true" /><br />
<?php
			}
?>

						Total Applicants: <strong><?php echo i($applications[$this_city_id], $group_id, 0); ?></strong><br />
						Task(s) Expected: <strong><?php echo $expected; ?></strong><br />
						Task(s) Submitted: <strong><?php echo i($submitted[$this_city_id], $group_id, 0); ?></strong><br />
						Task(s) Evaluated: <strong><?php echo i($evaluated[$this_city_id], $group_id, 0); ?></strong><br />

			<hr>
			</div>
	<?php
		}
	}
	?>
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
      <?php foreach($verticals as $group_id => $group_name) { if($group_id == 8) continue; ?><th class="bordered" colspan="4"><?php echo $group_name ?></th><?php } ?>
      <th class="bordered" colspan="4">Total</th>
      <!-- <th class="city-name bordered">City</th> -->
    </tr>
    <tr><th class="city-name">&nbsp;</th>
      <?php for($i = 0; $i <= count($verticals)-1; $i++) { ?>
				<th class="bordered">Total Applicants</th>
				<th>Task(s) Expected</th>
				<th>Task(s) Submitted</th>
				<th>Task(s) Evaluated</th>
			<?php } ?>
      <!-- <th class="city-name bordered">&nbsp;</th> -->
    </tr>
    </thead>

    <tbody>
  <?php
	  foreach($all_cities as $city_id => $city_name) {
			if($city_id==0) continue;
	?>
  <tr><th class="city-name"><?php echo $city_name ?></th>
  <?php
			foreach($verticals as $group_id => $group_name) {
				if($group_id == 8) continue;
				$expected = i($applications[$city_id], $group_id, 0) - (i($ctl_ctl_applicants[$city_id], $group_id, 0) + i($nonctl_fellow_applicants[$city_id], $group_id, 0));
  ?>
		    <td class="bordered"><?php echo i($applications[$city_id], $group_id, 0); ?></td>
				<td><?php echo $expected; ?></td>
		    <td <?php highlight(i($submitted[$city_id], $group_id, 0), i($applications[$city_id], $group_id, 0)); ?>><?php echo i($submitted[$city_id], $group_id, 0); ?></td>
				<td <?php highlight(i($evaluated[$city_id], $group_id, 0), i($submitted[$city_id], $group_id, 0)); ?>><?php echo i($evaluated[$city_id], $group_id, 0); ?></td>
  <?php
			}
	?>
		    <td class="bordered"><?php echo $total_cities[$city_id]['applications'] ?></td>
				<td><?php echo $total_cities[$city_id]['applications'] - $total_cities[$city_id]['not_required']; ?></td>
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

      foreach($verticals as $group_id => $group_name) {
				if($group_id == 8) continue;
  ?>
	      <td class="bordered"> <?php echo $total_verticals[$group_id]['applications']; ?></td>
				<td> <?php echo $total_verticals[$group_id]['applications'] - $total_verticals[$group_id]['not_required']; ?></td>
	      <td <?php highlight($total_verticals[$group_id]['submitted'], $total_verticals[$group_id]['applications']); ?>><?php echo $total_verticals[$group_id]['submitted']; ?></td>
				<td <?php highlight($total_verticals[$group_id]['evaluated'], $total_verticals[$group_id]['submitted']); ?>><?php echo $total_verticals[$group_id]['evaluated']; ?></td>
	      <?php } ?>
	      <td class="bordered"><?php echo $all_applied ?></td>
				<td><?php echo $all_applied - $all_not_required; ?></td>
	      <td <?php highlight($all_submitted, $all_applied); ?>><?php echo $all_submitted ?></td>
				<td <?php highlight($all_evaluated, $all_submitted); ?>><?php echo $all_evaluated ?></td>
      <!-- <th class="bordered">Total</th> -->
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
