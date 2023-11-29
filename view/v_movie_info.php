<!DOCTYPE html>
<html>
<head>
<?php 
print_site();

?>
<h2><?= Info::$page_title ?></h2>
<div class=list-wrap>
<?php
if (Info::$result) {
	foreach (Info::$result as $i => $item) {
		if ($item != '') {
		?>
		<div class=movie-item id=<?= $i ?>>
			<span class=movie-title><?= $item ?></span>
		</div>
		
	<?php
		}
	}
} ?>

</div>

<div>
	<div class=window style="display: none;">
		<div class=window-titlebar>
			<div class=window-title></div>
			<div class=window-btn-close>x</div>
		</div>
		<div class=window-content></div>
	</div>
</div>
<?php LibHtml::print_part('footer'); ?>
</head>
</html>