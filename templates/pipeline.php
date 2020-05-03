<div class="x_panel">
	<div class="x_title">
		<h2>Fellowship Pipeline: Stage-wise evaluation Data.</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<form action="dashboard.php" method="get" class="form-area">
		<?php $html->buildInput("city_id", "Select City", 'select', $city_id, ['options' => $all_cities, 'no_br' => true]); ?> &nbsp;
    <?php $html->buildInput("group_id", "Select Profile", 'select', $group_id, ['options' => $all_verticals, 'no_br' => true]); ?> &nbsp;
		<input type="submit" class="btn btn-success btn-xs" value="Filter" />
	</form><br />
	</div>
</div>

<div class="x-panel">
  <!-- top tiles -->
  <div class="row tile_count">
    <div class="fellowship-signup">
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Sign Ups</span>
        <div class="count"><?php echo $total_filled; ?></div>
      </div>
		<?php
			foreach($evaluator_phases as $key => $value) {
		?>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Evaluation Phase <?php echo $key;?></span>
        <div class="count">
				<?php
					$count = 0;
					foreach ($value['stage_id'] as $id) {
						$count += $stage_data[$id]['count'];
					}


					echo $count;
			  ?>
				</div>
				<span class="count_bottom"><?php echo round($count/$total_filled*100,2).'%'; ?> <br/><i class="green"><?php echo $value['name']; ?> </i></span>
      </div>
		<?php
			}
		?>
    </div>
  </div>
</div>
