<?php 
print_site();

print_title(Info::$page_title);


$pagination_html = create_pagination("actor", Info::$page_number, Info::$result['total'], \GlobVars::$pagination_size);
echo "<div class=pagination>$pagination_html</div>";
?>
<div class=list-wrap>
<?php
$current_person = -1;
if (Info::$result && Info::$result['list']) {
	foreach (Info::$result['list'] as $i => $item) { 
		if ($item['person_id'] != $current_person) {
			$current_person = $item['person_id'];
			$href = "/crew/{$item['person_id']}-".LibHtml::string_to_permalink($item['person_name']);
			$item_html = "<a href='$href'>{$item['person_name']}</a> ";
			echo "<div class=person-item-begin>$item_html</div>";
		}
		//$genres_arr = $item['genres'] ? explode(",", $item['genres']) : [];
		$genres_url_arr = [];
		/*foreach ($genres_arr as $j => $genre_name) {
			$genre_name = trim($genre_name);
			$genres_url_arr[] = "<a href='/movie/genre/$genre_name'>$genre_name</a>";
		}*/
		$jobs_arr = $item['jobs'] ? explode("\n", $item['jobs']) : [];
		foreach ($jobs_arr as $j => $job) {
			$job_arr = explode("@", $job);
			if (count($job_arr) > 2) {
				$jobs_arr[$j] = $job_arr[2];
			}
		}
		$jobs_str = implode(",<br/>", $jobs_arr);
		
		$companies_arr = $item['companies'] ? explode("\n", $item['companies']) : [];
		foreach ($companies_arr as $j => $company) {
			$company_arr = explode("@", $company);
			if (count($company_arr) > 1) {
				$href_company = LibHtml::string_for_url("{$company_arr[0]}-{$company_arr[1]}");
				//$href_company = LibHtml::string_for_url($company_arr[1]);
				$companies_arr[$j] = "<a href=/company/$href_company>{$company_arr[1]}</a>";
			}
		}
		$companies_str = implode(", ", $companies_arr);
		$year = $item['release_date'] ? substr($item['release_date'], 0, 4) : "";
		?>
		<div class=movie-item id=<?= $item['movie_id'] ?>>
			<span class=movie-title>
				<a href="/movie/<?= $item['movie_id'] ?>"><?= $item['title'] ?></a>
				<span class=movie-btn-info></span>
			</span>
			<div class=crew-name><?= /*"<b>".$item['person_name']."</b> as:<br/> ".*/$jobs_str ?></div><br/>
			<div class=movie-info-company>
				<div><b>Production:</b></div>
				<?= $companies_str ?>
			</div>
			<span class=movie-genres><?= implode(" ", $genres_url_arr) ?>&nbsp;</span>
			<span class=movie-date-rating>
				<span class=movie-rating><?= "{$item['vote_average']}" ?></span>
				<span class=movie-date><a href="/movie/year/<?= $year ?>"><?= $item['release_date'] ?></a></span>
			</span>
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