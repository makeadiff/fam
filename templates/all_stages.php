<div class="x_panel">
	<div class="x_title">
		<h2>All Stages</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<ul>
			<?php foreach ($all_stages as $stage) { ?>
			<li><?php echo $stage['name']; ?>
				<?php
				$all_categories = $fam->getCategories($stage['id']);
				if($all_categories) echo "<ul>";
				foreach ($all_categories as $category) { ?>
					<li><a href="bulk_evaluate.php?stage_id=<?php echo $stage['id'] ?>&category_id=<?php echo $category['id'] ?>"><?php echo $category['name'] ?></a></li>
				<?php }
				if($all_categories) echo "</ul>"; ?>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>