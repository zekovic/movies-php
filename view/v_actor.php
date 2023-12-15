<?php 
print_site();

print_title(Info::$page_title);

$person = Info::$result['person'];
$movie_list = Info::$result['movie_list'];
?>

<div class=person-info>
	<div>
		<h2><?= $person->person_name ?></h2>
	</div>
</div>

<?php
var_dump($person->person_movies);
var_dump($movie_list);
?>

<?php LibHtml::print_part('footer'); ?>
</body>
</html>