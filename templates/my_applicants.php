<div class="x_panel">

<div class="x_title">
<h2>Applicants Assigned to <?php echo $user['name'] ?></h2>
<div class="clearfix"></div>

</div>

<div class="x_content">
<p class="text-muted font-13 m-b-30">
  These are the applicants that you have to evaluate. If any more people are assigned to you, they'll automatically appear here.
</p>

<?php
require 'templates/partials/applicants_table.php'; 
?>
</div>

</div>
