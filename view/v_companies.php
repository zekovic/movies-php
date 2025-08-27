<?php 
print_site();

print_title(Info::$page_title);
$pagination_html = create_pagination("company", Info::$page_number, Info::$result['total'], \GlobVars::$pagination_size);
echo "<div class=pagination>$pagination_html</div>";
?>
<div class=list-wrap>
<?php

if (Info::$result && Info::$result['list']) {
	foreach (Info::$result['list'] as $i => $item) { 
		
		$genres_arr = $item['genres'] ? explode(",", $item['genres']) : [];
		$genres_url_arr = [];
		foreach ($genres_arr as $j => $genre_name) {
			$genre_name = trim($genre_name);
			$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
		}
		$item['genres_list'] = implode(" ", $genres_url_arr);
		
		$href = "/company/{$item['company_id']}-".LibHtml::string_to_permalink($item['company_name']);
		
		?>
		<div class=movie-item id=<?= $item['company_id'] ?>>
			<span class=movie-title>
				<a href="<?= $href ?>"><?= $item['company_name'] ?></a>
				<span class='movie-btn-hover movie-btn-info'></span>
			</span>
			<span class=movie-genres><?= $item['genres_list'] ?>&nbsp;</span>
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