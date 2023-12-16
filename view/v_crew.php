<?php 
print_site();

print_title(Info::$page_title);

$person = Info::$result['person'];
$movie_list = Info::$result['movie_list'];

$movies_count = count($movie_list);
$count_label = "$movies_count movie";
if ($movies_count > 1) { $count_label.= "s"; }

$job_tags_arr = [];
foreach ($person->person_movies as $i => $item) {
	foreach ($item as $j => $job) {
		$job_tags_arr[$job['department_name']] = $job['department_id'];
	}
}

?>

<div class=person-info>
	<div>
		<h2><?= $person->person_name ?></h2>
		<h5>Found in production of <?= $count_label ?></h5>
		<div class=tag-wrap tag=job><?php
			foreach ($job_tags_arr as $i => $item) {
				echo "<span class=tag-item id=$item>$i</span>";
			}
		?></div>
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
	$movie_jobs = $person->person_movies[$item['movie_id']];
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
		<div class=movie-jobs>
			<?php
			foreach ($movie_jobs as $j => $job) {
				echo "<div job='job-{$job['department_id']}'>{$job['department_name']} - {$job['job']}</div>";
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

if (count($person->actor_movies)) {
	$movie_titles = array_column($person->actor_movies, 'title');
	$movie_titles_str = implode(",<br/> ", $movie_titles);
	$href = "/actor/{$person->person_id}-".LibHtml::string_to_permalink($person->person_name);
	echo "<h4>Also actor in movies:</h4> <div><a href='$href'>$movie_titles_str</a></div>";
}

?>
</div>

<?php LibHtml::print_part('footer'); ?>

<div>
	<div class=window id=wnd_movie style="display: none;">
		<div class=window-titlebar>
			<div class=window-title></div>
			<div class=window-btn-close>x</div>
		</div>
		<div class=window-content></div>
	</div>
	<div class=window-modal-backbround style="display: none;"></div>
</div>

</body>
</html>