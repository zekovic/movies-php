<?php 
print_site();

print_title("Companies");
$pagination_html = create_pagination("company", Info::$page_number, Info::$result['total'], \GlobVars::$pagination_size);
echo "<div class=pagination>$pagination_html</div>";
?>
<div class=list-wrap>
<?php

if (Info::$result && Info::$result['list']) {
	foreach (Info::$result['list'] as $i => $item) { 
		?>
		<div class=movie-item id=<?= $item['company_id'] ?>>
			<span class=movie-title>
				<a href="/company/<?= $item['company_id'] ?>"><?= $item['company_name'] ?></a>
			</span>
		</div>
		
	<?php
	}
} ?>

</div>
<div class=pagination>
	<?php echo $pagination_html; ?>
</div>

<?php LibHtml::print_part('footer'); ?>
</body>
</html>