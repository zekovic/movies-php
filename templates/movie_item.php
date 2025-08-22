<?php 

$genres_arr = $item['genres'] ? explode(",", $item['genres']) : [];
$genres_url_arr = [];
foreach ($genres_arr as $j => $genre_name) {
	$genre_name = trim($genre_name);
	$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
}
$year = $item['release_date'] ? substr($item['release_date'], 0, 4) : "";
$item['year'] = $item['release_date'] ? substr($item['release_date'], 0, 4) : "";
$item['genres_list'] = implode(" ", $genres_url_arr);
?>

<span class=movie-title>
	<a href="/movie/<?= $item['movie_id'] ?>"><?= $item['title'] ?></a>
	<span class='movie-btn-hover movie-btn-info'></span>
	<span class='movie-btn-hover movie-btn-google'></span>
</span>
<span class=movie-genres><?= $item['genres_list'] ?>&nbsp;</span>
<span class=movie-date-rating>
	<span class=movie-rating><?= "{$item['vote_average']}" ?></span>
	<span class=movie-date><a href="/movie/year/<?= $item['year'] ?>"><?= $item['release_date'] ?></a></span>
</span>

