<!DOCTYPE html>
<html>
<head>
<?php 
print_site();

$company = Info::$result;
$data = Info::$result->details;
$movies = $data['movies'];

$genres_url_arr = [];
foreach ($data['genre'] as $i => $item) {
	$genre_name = trim($item['genre_name']);
	$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
}

$profit = 0;
$budget_arr = [];
$revenue_arr = [];
$money_max = 0;

for ($i = count($movies) - 1; $i >= 0; $i--) {
	$year = $movies[$i]['release_date'] ? substr($movies[$i]['release_date'], 0, 4) : "";
	if (!isset($budget_arr[$year])) { $budget_arr[$year] = 0; }
	if (!isset($revenue_arr[$year])) { $revenue_arr[$year] = 0; }
	$profit += $movies[$i]['revenue'];
	$budget_arr[$year] += $movies[$i]['budget'];
	$revenue_arr[$year] += $movies[$i]['revenue'];
	
}

foreach ($budget_arr as $time => $budget_val) {
	$money_max = max($money_max, $budget_val, $revenue_arr[$time]);
}

$panel_height = 350;
$height_ratio = $money_max ? (($panel_height * 0.9) / $money_max) : 1;

print_title(Info::$page_title);
?>
<div class=movie-info>
	<div>
		<h2><?= $company->company_name ?></h2>
	</div>
	<div class=tab-wrap for=company_panels>
		<span for=company_overview class=selected>Overview</span>
		<span for=company_movies>Movies</span>
	</div>
	<div class=tab-panels-wrap id=company_panels>
		<div class="tab" id=company_overview>
			Genres: <br/>
			<?= implode(" ", $genres_url_arr) ?> <br/><br/>
			Total revenue: <?= format_long_number($profit) ?>
			<div id=company_profit_graph style="height: <?= $panel_height ?>px;">
				<?php 
				foreach ($budget_arr as $time => $budget_val) {
					$revenue_val = $revenue_arr[$time];
					$height = (int)($budget_val * $height_ratio);
					$year_profit = format_long_number($revenue_val - $budget_val);
					$year_earn = $year_profit > 0 ? "+" : "";
					$bar_html = "<span class=bar-text>$time</br>{$year_earn}{$year_profit}</span>";
					echo "<span class='profit-bar profit-bar-budget' style='height: {$height}px;' val='$budget_val'></span>";
					$height = (int)($revenue_val * $height_ratio);
					echo "<span class='profit-bar profit-bar-revenue' style='height: {$height}px;' val='{$revenue_val}'>$bar_html</span>";
					// echo "<span class='profit-bar profit-bar-space'>$time</span>";
				}
				?>
			</div>
			
		</div>
		<div class="tab movie-info-crew" id=company_movies style="display: none;">
			<?php
			foreach ($movies as $i => $item) { 
				$countries_arr = $item['countries'] ? explode(',', $item['countries']) : [];
				$profit = 0;
				$profit_type = "";
				if ($item['budget'] > 0 && $item['revenue'] > 0) {
					$profit = (int)($item['revenue']) - (int)($item['budget']);
					if ($profit == 0) { $profit_type = "profit-equal"; }
					if ($profit > 0) { $profit_type = "profit-up"; }
					if ($profit < 0) { $profit_type = "profit-down"; }
				}
				?>
				<div class=movie-item id=<?= $item['movie_id'] ?>>
					<?php include GlobVars::$root_folder."/templates/movie_item.php"; ?>
					<div class=movie-countries>
					<?php 
						foreach ($countries_arr as $j => $iso) {
						$iso_lower = strtolower(trim($iso));
						echo "<span class=movie-info-country-img style='background-image: url(/assets/img/flags/{$iso_lower}.svg)'>$iso</span>";
					} ?>
					</div>
					<?php if ($profit_type) { ?>
					<div>
						Budget/Revenue 
						<span class="profit <?= $profit_type ?>">
							<?= format_long_number($item['budget']) ?> / <?= format_long_number($item['revenue']) ?>
						</span>
					</div>
					<?php } ?>
				</div>
				
			<?php
			}
			?>
		</div>
	</div>
	
</div>

<?php LibHtml::print_part('footer'); ?>
</head>
</html>