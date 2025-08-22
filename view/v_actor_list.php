<?php 
print_site();

print_title(Info::$page_title);


$pagination_html = create_pagination("actor", Info::$page_number, Info::$result['total'], \GlobVars::$pagination_size);
echo "<div class=pagination>$pagination_html</div>";
?>
<div class=list-wrap>
<?php

if (Info::$result && Info::$result['list']) {
	foreach (Info::$result['list'] as $i => $item) { 
		//$genres_arr = $item['genres'] ? explode(",", $item['genres']) : [];
		$genres_url_arr = [];
		/*foreach ($genres_arr as $j => $genre_name) {
			$genre_name = trim($genre_name);
			$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
		}*/
		$year = $item['release_date'] ? substr($item['release_date'], 0, 4) : "";
		$characters = explode("/", $item['character_name']);
		?>
		<div class=movie-item id=<?= $item['movie_id'] ?>>
			<span class=movie-title>
				<a href="/movie/<?= $item['movie_id'] ?>"><?= $item['title'] ?></a>
				<span class='movie-btn-hover movie-btn-info'></span>
				<span class='movie-btn-hover movie-btn-google'></span>
			</span>
			<span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span>
			<span class=movie-date-rating>
				<span class=movie-rating><?= "{$item['vote_average']}" ?></span>
				<span class=movie-date><a href="/movie/year/<?= $year ?>"><?= $item['release_date'] ?></a></span>
			</span>
			<div class=movie-actor-cast>
				<?php
				echo "<b>".$item['person_name']."</b> as ";
				foreach ($characters as $j => $character) {
					echo "<div>".trim($character)."</div>";
				}
				?>
			</div>
		</div>
		
	<?php
	}
} ?>

</div>
<div class=pagination>
	<?php echo $pagination_html; 
		//var_dump(Info::$result); //exit;
	?>
</div>

<?php LibHtml::print_part('footer'); ?>
</body>
</html>