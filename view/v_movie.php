<!DOCTYPE html>
<html>
<head>
<?php 
print_site();

print_title(Info::$page_title);
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
		$year = substr($item['release_date'], 0, 4);
		?>
		<div class=movie-item id=<?= $item['movie_id'] ?>>
			<span class=movie-title>
				<a href="/movie/<?= $item['movie_id'] ?>"><?= $item['title'] ?></a>
				<span class=movie-btn-info></span>
			</span>
			<span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span>
			<span class=movie-date><a href="/movie/year/<?= $year ?>"><?= $item['release_date'] ?></a></span>
		</div>
		
	<?php
	}
} ?>

</div>
<div class=pagination>
	<?php echo create_pagination("movie", 0, Info::$result['total'], 100); ?>
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