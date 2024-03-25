<?php 

print_site();
print_title("Select genre to explore");
?>
<div class=genres-wrap>
<?php
if (Info::$result) {
	foreach (Info::$result as $i => $item) { 
		$font_size = max(14, floor($item['movies_count'] / 70));
		$font_size_small = max(12, $font_size-3);
		$genre_name = trim($item['genre_name']);
		$genre_info = "<b>$genre_name</b> <span style='font-size: {$font_size_small}pt'>({$item['movies_count']})</span>";
		$genres_url = "<a href='/movie/genre/$genre_name'>$genre_info</a>";
		echo "<span style='white-space: nowrap; font-size: {$font_size}pt;'>$genres_url</span> ";
		echo "<div class=genre-random-movies-wrap>";
		foreach ($item['random_movies'] as $j => $movie_item) {
			echo "<span id='{$movie_item['id']}' date='{$movie_item['date']}' rating='{$movie_item['rating']}'>
					<a href='/movie/{$movie_item['id']}'>{$movie_item['title']}</a></span> ";
		}
		echo "<span><a href='/movie/genre/$genre_name'>See all...</a></span> </div>";
		?>
<?php
}
} ?>
</div>

<?php LibHtml::print_part('footer'); ?>

</body>
</html>