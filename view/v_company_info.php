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

for ($i = count($movies) - 1; $i >= 0; $i--) {
	$year = $movies[$i]['release_date'] ? substr($movies[$i]['release_date'], 0, 4) : "";
	if (!isset($budget_arr[$year])) { $budget_arr[$year] = 0; }
	if (!isset($revenue_arr[$year])) { $revenue_arr[$year] = 0; }
	$profit += $movies[$i]['revenue'];
	$budget_arr[$year] += $movies[$i]['budget'];
	$revenue_arr[$year] += $movies[$i]['revenue'];
	
}



print_title(Info::$page_title);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" 
	integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" 
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
			<br/><br/>
			<canvas id=company_profit_chart></canvas>
			
			<script type="text/javascript">
			const chart_ctx = document.getElementById('company_profit_chart');
			let bar_profit = 0;
			
			let tick_colors = {
				'light': '#555',
				'dark': '#ddd',
				'blue': '#12387f',
				'purple': '#42d39c',
				'yellow': '#e5ca61',
			};
			
			const data = {
				datasets: [
				{
					label: 'Budget',
					// data: [],
					borderColor: '#333',
					backgroundColor: '#dd3333',
					borderWidth: 1,
					maxBarThickness: 50,
					//barThickness: 10,
					//barPercentage: 0.3,
				},
				{
					label: 'Revenue',
					// data: [],
					borderColor: '#333',
					backgroundColor: '#1ea51e',
					borderWidth: 1,
					barPercentage: 1.5,
					maxBarThickness: 50,
				}
				]
			};
			
			// Config:
			let config = {
				type: 'bar',
				data: data,
				options: {
					responsive: true,
					interaction: {
						mode: 'index',
					},
					plugins: {
						legend: {
							position: 'bottom',
							align: 'start',
						},
						tooltip: {
							callbacks: {
								label: function(context) {
									return format_long_number(context.raw);
								},
								footer: function(tooltipItems) {
									let data_index = tooltipItems[0].dataIndex;
									bar_profit = config.data.datasets[1].data[data_index] - config.data.datasets[0].data[data_index];
									return 'Profit ' + format_long_number(bar_profit);
								},
							},
							footerColor: function(context) {
								return (bar_profit > 0) ? '#1ea51e' : '#dd3333';
							},
						}
					},
					scales: {
						y: {
							ticks: {
								color: tick_colors[getCookie('color_scheme')],
								callback: function(value, index, ticks) {
									return format_long_number(value);
								}
							},
						},
						x: {
							ticks: {
								color: tick_colors[getCookie('color_scheme')],
							},
							// categoryPercentage: 0.1,
							// barPercentage: 1.5,
						}
					},
					// maintainAspectRatio: false,
				},
			};
			
			config.data.labels = <?= json_encode(array_keys($budget_arr)) ?>;
			config.data.datasets[0].data = <?= json_encode(array_values($budget_arr)) ?>;
			config.data.datasets[1].data = <?= json_encode(array_values($revenue_arr)) ?>;
			
			var chart = new Chart(chart_ctx, config);
			
			</script>
			
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