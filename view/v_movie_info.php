<!DOCTYPE html>
<html>
<head>
<?php 
print_site();

$movie = Info::$result;
$data = Info::$result->details;

$genres_url_arr = [];
foreach ($data['genre'] as $j => $item) {
	$genre_name = trim($item['genre_name']);
	$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
}
$lang_arr = [];
foreach ($data['language'] as $i => $item) {
	$lang_arr[] = $item['language_name'];
}
$lang_arr = array_unique($lang_arr);

$company_arr = [];
foreach ($data['company'] as $i => $item) {
	$href_company = LibHtml::string_for_url($item['company_name']);
	$company_arr[] = "<a href=/company/$href_company>{$item['company_name']}</a>";
}

$cast_html = "";
$cast_summary = "Cast: <br/>";
foreach ($data['cast'] as $i => $item) {
	$href = "/actor/{$item['person_id']}-".LibHtml::string_to_permalink($item['person_name']);
	$item_html = "<a href='$href'><span><b>{$item['character_name']}</b> ({$item['person_name']})</span></a> ";
	$cast_html .= $item_html;
	if ($i < 5) {
		$cast_summary .= $item_html;
	}
	if ($i == 5) {
		$cast_summary .= "...";
	}
}

$crew_arr = [];
foreach ($data['crew'] as $i => $item) {
	$dep_name = $item['department_name'];
	if (!isset($crew_arr[$dep_name])) {
		$crew_arr[$dep_name] = [];
	}
	$crew_arr[$dep_name][] = $item;
}
$crew_html = "";
$crew_summary = isset($crew_arr['Directing']) ? "Directing: " : "";
foreach ($crew_arr as $i => $department) {
	$crew_html .= "<div><b>$i</b></div>";
	foreach ($department as $j => $item) {
		$href = "/crew/{$item['person_id']}-".LibHtml::string_to_permalink($item['person_name']);
		$item_html = "<a href='$href'><div><b>{$item['person_name']}</b> - {$item['job']}</div></a> ";
		$crew_html .= $item_html;
		if ($i == "Directing") {
			$crew_summary .= $item_html;
		}
	}
}

$year = $movie->release_date ? substr($movie->release_date, 0, 4) : "";

?>
<h2><?= print_title(Info::$page_title); ?></h2>


<div class=movie-info>
	<div>
		<h2><?= $movie->title ?></h2>
		<div class=movie-info-tagline><?= $movie->tagline ?></div>
		<div>
			<span class=movie-info-date><a href="/movie/year/<?= $year ?>"><?= $movie->release_date ?></a></span>
			<span class=movie-info-rating><?= "$movie->vote_average / 10 ($movie->vote_count)" ?></span>
		</div>
		<div><span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span></div>
	</div>
	<div>
		<?= $movie->overview ?>
	</div>
	<div class=tab-wrap for=movie_panels>
		<span for=movie_overview class=selected>Overview</span>
		<span for=movie_cast>Cast</span>
		<span for=movie_crew>Crew</span>
	</div>
	<div class=tab-panels-wrap id=movie_panels>
		<div class="tab" id=movie_overview>
			<div>
				<span class=movie-info-language>
					<?php foreach ($data['country'] as $i => $item) { echo $item['country_iso_code']." "; } ?> / 
					<?= implode(", ", $lang_arr) ?>
				</span>
				<span class=movie-info-duration>Duration: <?php echo LibTime::hours_to_hh_mm($movie->runtime); ?></span>
			</div>
			<div class=movie-info-company><?= implode(", ", $company_arr) ?></div>
			<div class=movie-info-url><?= $movie->homepage ? "<a href='$movie->homepage'>$movie->homepage</a>" : '' ?></div>
			<div class=movie-info-budget>Budget: <span><?= $movie->budget ? "$".format_long_number($movie->budget) : '' ?></span></div>
			<div class=movie-info-revenue>Revenue: <span><?= $movie->revenue ? "$".format_long_number($movie->revenue) : '' ?></span></div>
			<div><?= $crew_summary ?></div>
			<div><?= $cast_summary ?></div>
		</div>
		<div class="tab movie-info-cast" id=movie_cast style="display: none;">
			<?= $cast_html; ?>
		</div>
		<div class="tab movie-info-crew" id=movie_crew style="display: none;">
			<?= $crew_html; ?>
		</div>
	</div>
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