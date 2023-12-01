<!DOCTYPE html>
<html>
<head>
<?php 
//LibHtml::print_header();
print_site();
print_title("Genres");
?>
<div>
<?php
if (Info::$result) {
	foreach (Info::$result as $i => $item) { ?>
		<div><?= "{$item['genre_id']} : {$item['genre_name']}" ?></div>
<?php
}
} ?>
</div>

</head>
</html>