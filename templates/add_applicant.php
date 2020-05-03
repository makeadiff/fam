<div class="x_panel">

	<?php
		if(!$is_director) die("You don't have access to this view");
	?>

<?php if($action!='Add'){ ?>
	<div class="x_title">
		<h2>Search Volunteer/Alumni</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="add_applicant.php" method="get" class="form-horizontal form-label-left">
			<!-- Search by User ID -->
			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="id" class="form-control has-feedback-left" placeholder="User ID" value="<?php echo i($QUERY, 'id'); ?>" />
				<span class="fa fa-male form-control-feedback left" aria-hidden="true"></span>
	    </div>
			<!-- Search by Name -->
			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="name" class="form-control has-feedback-left" placeholder="Name" value="<?php echo i($QUERY, 'name'); ?>" />
				<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
	    </div>
			<!-- Search by Email -->
			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="email" class="form-control has-feedback-left" placeholder="Email" value="<?php echo i($QUERY, 'email'); ?>" />
				<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
      </div>
			<!-- Search By Phone -->
			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="phone" class="form-control has-feedback-left" placeholder="Phone" value="<?php echo i($QUERY, 'phone'); ?>" />
				<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
      </div>

			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<label>Select City</label>
				<select name="city_id" class="form-control">
				<?php
					$selected = '';
					foreach ($all_cities_user as $key => $value) {
						if($city_id==$key){
							$selected = 'selected';
						}
						else{
							$selected = '';
						}
				?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
				<?php
					}
				?>
				</select>
      </div>
		<?php $html->buildInput("action", "&nbsp", 'submit', 'Search', ['class' => 'btn btn-primary']); ?>
		</form>
	</div>
<?php
		}
		else{
			if(isset($user)){
?>
	<div class="x_title">
		<h2><?php echo $user['name']; ?></h2>
		<div class="clearfix"></div>
	</div>
	<form action="add_applicant.php" method="get" class="form-horizontal form-label-left">
		<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
		<div class="x_content">
	<?php
		for($i=1; $i<=3; $i++){
	?>
			<div class="mb-20">
				<p>Fellowship Preference <?php echo $i; ?></p>
				<select class="form-control" name="preference_<?php echo $i; ?>" <?php echo ($i==1? 'required': ''); ?>>
					<option value="">Select Role</option>
	<?php
		foreach ($verticals as $key => $value) {
	?>
					<option value="<?php echo $key?>"><?php echo $value; ?></option>
	<?php
		}
	?>
				</select>
			</div>
	<?php
		}
	?>
		</div>

<?php
			}
		$html->buildInput("fellowship_city_id", "Select City", 'select', $city_id, ['options' => $all_cities]);
		echo "<br/>";
		$html->buildInput("action", "&nbsp", 'submit', 'Add Application', ['class' => 'btn btn-primary']);
		}
?>
	</form>
</div>

<?php if($applicants) { ?>
<div class="x_panel">
	<div class="x_title">
		<h2>Results</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
	<?php
	$count = 0;
	require 'templates/partials/user_list.php';
	?>
	</div>
</div>
<?php } ?>
