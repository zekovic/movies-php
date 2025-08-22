<?php 
print_site();

print_title(Info::$page_title);
$pagination_html = create_pagination("movie", Info::$page_number, Info::$result['total'], \GlobVars::$pagination_size);
echo "<div class=pagination>$pagination_html</div>";
?>
<div class=list-wrap>
<?php

if (Info::$result && Info::$result['list']) {
	foreach (Info::$result['list'] as $i => $item) { 
		?>
		<div class=movie-item id=<?= $item['movie_id'] ?>>
			<?php include GlobVars::$root_folder."/templates/movie_item.php"; ?>
		</div>
		
	<?php
	}
} ?>

</div>
<div class=pagination>
	<?php echo $pagination_html; ?>
</div>

<?php LibHtml::print_part('footer'); ?>
</body>
</html>