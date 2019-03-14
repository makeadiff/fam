<div class="x_panel">
  <div class="x_title">
    <h2>Select Vertical</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  	<form action="selected_in_vertical.php" method="post">
	<?php $html->buildInput("vertical_id", 'Vertical', 'select', $vertical_id, ['options' => $verticals, 'no_br' => 1]); ?> &nbsp;
	<button class="btn btn-success btn-sm" value="Filter" name="action">Filter</button>
	</form>
  </div>
</div>

<?php
showUsers('Strats', $strats);
showUsers('Fellows', $fellows);

function showUsers($title, $users) { ?>
<div class="x_panel">
  <div class="x_title">
    <h2>Selected <?php echo $title ?></h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
  <table class="table table-striped" id="data-table">
    <thead>
    <tr><th width="50">#</th><th width="300">Name</th><th width="400">Contact</th><th class="city-name">City</th></tr>
    </thead>

    <tbody>
  <?php
  $count = 0;
  foreach($users as $user) {
  	$count++; ?>
  	<tr><td><?php echo $count ?></td><td title="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></td>
  		<td><?php echo $user['phone'] . '<br />'. $user['email'] . '<br />' . $user['mad_email']; ?></td>
  		<td class="city-name"><?php echo $user['city'] ?></td>
    </tr>
  <?php } ?>
  </tbody>
  </table>
  </div>
</div>
<?php } ?>