<?php 
print_site();

print_title(Info::$page_title);

$person = Info::$result['person'];
$movie_list = Info::$result['movie_list_actor'];

$movies_count = count($movie_list);
$count_label = "$movies_count movie";
if ($movies_count > 1) { $count_label.= "s"; }

?>

<div class=person-info>
	<div>
		<h2><?= $person->person_name ?></h2>
		<h5>Acting in <?= $count_label ?></h5>
	</div>
</div>

<div class=list-wrap>
<?php

foreach ($movie_list as $i => $item) { 
	$genres_arr = $item['genres'] ? explode(",", $item['genres']) : [];
	$genres_url_arr = [];
	foreach ($genres_arr as $j => $genre_name) {
		$genre_name = trim($genre_name);
		$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
	}
	$year = $item['release_date'] ? substr($item['release_date'], 0, 4) : "";
	
	$characters = explode("/", $person->actor_movies[$item['movie_id']]['character_name']);
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
		<div class=movie-actor-cast>
			<?php
			foreach ($characters as $j => $character) {
				echo "<div>".trim($character)."</div>";
			}
			?>
		</div>
	</div>
	
<?php
}
?>

</div>

<div class=movie-item>
<?php

if (count($person->person_movies)) {
	$movie_titles = array_column(Info::$result['movie_list'], 'title');
	$movie_titles_str = implode(",<br/> ", $movie_titles);
	$href = "/crew/{$person->person_id}-".LibHtml::string_to_permalink($person->person_name);
	echo "<h4>Also working in production of movies:</h4> <div><a href='$href'>$movie_titles_str</a></div>";
}

?>
</div>

<?php LibHtml::print_part('footer'); ?>

</body>
</html>