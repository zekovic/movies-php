<!DOCTYPE html>
<html>
<head>
<?php 
print_site();

$data = Info::$result->details;

$genres_url_arr = [];
foreach ($data['genre'] as $j => $item) {
	$genre_name = trim($item['genre_name']);
	$genres_url_arr[] = "<a target='_blank' href='/movie/genre/$genre_name'>$genre_name</a>";
}
$year = Info::$result->release_date ? substr(Info::$result->release_date, 0, 4) : "";



?>
<h2><?= print_title(Info::$page_title); ?></h2>


<div class=movie-info>
	<div>
		<h2><?= Info::$result->title ?></h2>
		<div class=movie-info-tagline><?= Info::$result->tagline ?></div>
		<div>
			<span class=movie-info-date><a target='_blank' href="/movie/year/<?= $year ?>"><?= Info::$result->release_date ?></a></span>
			<span class=movie-info-rating><?= Info::$result->vote_average ?> / 10</span>
		</div>
		<div><span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span></div>
	</div>
	<div>
		<?= Info::$result->overview ?>
	</div>
	<div class=tab-wrap for=movie_panels>
		<span for=movie_overview class=selected>Overview</span>
		<span for=movie_cast>Cast</span>
		<span for=movie_crew>Crew</span>
	</div>
	<div class=tab-panels-wrap id=movie_panels>
		<div class="tab" id=movie_overview>
			<div>
				<span><?php foreach ($data['country'] as $i => $item) { echo $item['country_iso_code']." "; } ?> / English</span>
				<span><?php echo Info::$result->runtime; ?></span>
			</div>
			<div>Budget: <?= Info::$result->budget ?></div>
			<div>Revenue: <?= Info::$result->revenue ?> </div>
			<div>Directing: Directors Name</div>
			<div>
				<span>Actor Name1</span> 
				<span>Actor Name2</span> 
				<span>Actor Name3</span> 
			</div>
		</div>
		<div class="tab movie-info-cast" id=movie_cast style="display: none;">
			<?php
			foreach ($data['cast'] as $i => $item) {
				$href = "/actor/".urlencode(str_replace(" ", "-", mb_strtolower($item['person_name'])));
				echo "<a href='$href' target='_blank'><span><b>{$item['character_name']}</b> ({$item['person_name']})</span></a> ";
			}
			?>
		</div>
		<div class="tab movie-info-crew" id=movie_crew style="display: none;">
			<?php
			$crew_arr = [];
			foreach ($data['crew'] as $i => $item) {
				$dep_name = $item['department_name'];
				if (!isset($crew_arr[$dep_name])) {
					$crew_arr[$dep_name] = [];
				}
				$crew_arr[$dep_name][] = $item;
			}
			foreach ($crew_arr as $i => $department) {
				echo "<div><b>$i</b></div>";
				foreach ($department as $j => $item) {
					$href = "/crew/".urlencode(str_replace(" ", "-", mb_strtolower($item['person_name'])));
					echo "<a href='$href' target='_blank'><div><b>{$item['person_name']}</b> - {$item['job']}</div></a> ";
				}
			}
			?>
		</div>
	</div>
</div>
<?php

//var_dump(Info::$result);
//echo "<pre>".json_encode([$data['country'], $data['company'], $data['language'], $data['keywords'], ], JSON_PRETTY_PRINT)."</pre>";

if (Info::$result) {
	/*foreach (Info::$result as $i => $item) {
		if ($item != '') {
		?>
		<div class=movie-item id=<?= $i ?>>
			<span class=movie-title><?= "$i: $item" ?></span>
		</div>
		
	<?php
		}
	}*/
} ?>


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