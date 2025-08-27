<?php 

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
	$href_company = LibHtml::string_to_permalink($item['company_name']);
	$company_arr[] = "<a href=/company/{$item['company_id']}-$href_company>{$item['company_name']}</a>";
}


$year = $movie->release_date ? substr($movie->release_date, 0, 4) : "";

$budget_width = 0; $revenue_width = 0;
if ($movie->budget > 0 && $movie->revenue > 0) {
	$budget_revenue_total = $movie->budget + $movie->revenue;
	$budget_width = $movie->budget / $budget_revenue_total * 200;
	$revenue_width = $movie->revenue / $budget_revenue_total * 200;
}
?>

<style type="text/css">
.window-content { padding: 0px; }
.window-content .movie-info { overflow: auto; height: 100%; padding: 0px 10px; }
.window-content .movie-info > div { width: 100%; }
.window-content .movie-info-budget, .window-content .movie-info-revenue { width: inherit; max-width: 300px; }
</style>

<div class=movie-info>
	<div>
		
		<div class=movie-info-tagline><?= $movie->tagline ?></div>
		<div>
			<span class=movie-info-date><a href="/movie/year/<?= $year ?>"><?= $movie->release_date ?></a></span>
			<span class=movie-info-rating><?= "$movie->vote_average / 10 ($movie->vote_count)" ?></span>
		</div>
		<div><span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span></div>
		
		<br/>
		<span class=movie-info-language>
			<?php foreach ($data['country'] as $i => $item) {
				$iso = $item['country_iso_code'];
				$iso_lower = strtolower($iso);
				echo "<span class=movie-info-country-img style='background-image: url(/assets/img/flags/{$iso_lower}.svg)'>$iso</span>";
			} ?> / 
			<?= implode(", ", $lang_arr) ?>
		</span>
		<span class=movie-info-duration>Duration: <?php echo LibTime::hours_to_hh_mm($movie->runtime); ?></span>
		
		<br/><br/>
		<?= $movie->overview ?>
	</div>
		
	<div id=movie_overview>
		<span class=movie-info-company><?= implode(", ", $company_arr) ?></span>
		<br/><br/>
		<span class=movie-info-url><?= $movie->homepage ? "<a target=_blank href='$movie->homepage'>$movie->homepage</a>" : '' ?></span>
		<br/><br/>
		<div class=movie-info-budget>
			Budget: <span><?= $movie->budget ? "$".format_long_number($movie->budget) : '' ?><br/>
				<span class="movie-info-budget-bar" style="width: <?= $budget_width ?>px;"></span>
			</span>
		</div>
		<div class=movie-info-revenue>
			Revenue: <span><?= $movie->revenue ? "$".format_long_number($movie->revenue) : '' ?><br/>
				<span class="movie-info-revenue-bar" style="width: <?= $revenue_width ?>px;"></span>
			</span>
		</div>
		<div>
		<?php
			// echo " - ";
			foreach ($data['keywords'] as $i => $item) {
				$keyword_url = LibHtml::string_for_url($item['keyword_name']);
				echo "<span><a href='/movie/keyword/$keyword_url'>{$item['keyword_name']}</a></span> ";
			}
		?>
		</div>
	</div>
</div>

