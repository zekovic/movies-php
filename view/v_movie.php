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
		$genres_arr = $item['genres'] ? explode(",", $item['genres']) : [];
		$genres_url_arr = [];
		foreach ($genres_arr as $j => $genre_name) {
			$genre_name = trim($genre_name);
			$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
		}
		$year = $item['release_date'] ? substr($item['release_date'], 0, 4) : "";
		?>
		<div class=movie-item id=<?= $item['movie_id'] ?>>
			<span class=movie-title>
				<a href="/movie/<?= $item['movie_id'] ?>"><?= $item['title'] ?></a>
				<span class=movie-btn-info></span>
			</span>
			<span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span>
			<span class=movie-date-rating>
				<span class=movie-rating><?= "{$item['vote_average']}" ?></span>
				<span class=movie-date><a href="/movie/year/<?= $year ?>"><?= $item['release_date'] ?></a></span>
			</span>
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