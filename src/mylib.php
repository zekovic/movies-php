<?php

function print_site() {
	LibHtml::print_header();
	LibHtml::print_part("top_menu");
	//LibHtml::print_part("left");
}

function print_title($title) {
	echo "<div id=top_part><h2 id=title>$title</h2></div>";
}

function get_find_filter($argument = null) {
	if (!isset($_REQUEST['filter'])) {
		return null;
	}
	$request = json_decode($_REQUEST['filter'], true);
	if (!$request) {
		return $argument ? false : [];
	}
	if ($argument) {
		return isset($request[$argument]) ? $request[$argument] : false;
	}
	return $request;
}

?>