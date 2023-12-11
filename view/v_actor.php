<?php 
print_site();

print_title(Info::$page_title);
?>

<h2>Actor</h2>

<?php
var_dump(Info::$result['person']->actor_info);
?>

<?php LibHtml::print_part('footer'); ?>
</body>
</html>