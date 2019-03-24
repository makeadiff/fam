<div class="x_panel">
	<div class="x_title">
		<h2>Parameters</h2>
		<div class="clearfix"></div>
	</div>

 	<div class="x_content">
 		<dl><?php
// $participation_content = load($config['site_url'] . '../apps/participation-profile-update/?user_id=' . $appicant_id);
$participation_content = '{"credit":"-2","user_credit":"-2","vertical":"Ed Support","training":"","cpp":"signed","city_circle":"0\/0","shelter_sensitisation":"1\/1"}';
$participation = json_decode($participation_content);

foreach ($participation as $key => $value) {
	if($key == 'cpp') {
		$title = 'Child Protection Policy Signed';
		$value = ucfirst($value);
	} else $title = ucfirst(format($key));

	echo "<dt>" . $title . "</dt>";
	echo "<dd>" . $value . "</dd>";
}
?>
		</dl>
	</div>
</div>