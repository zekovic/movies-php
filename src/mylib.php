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
	/*if (!isset($_REQUEST['filter'])) {
		return null;
	}*/
	$request = isset($_REQUEST['filter']) ? json_decode($_REQUEST['filter'], true) : null;
	if (!$request) {
		return $argument ? false : [];
	}
	if ($argument) {
		return isset($request[$argument]) ? $request[$argument] : false;
	}
	return $request;
}

function create_pagination($page, $current, $total, $items_per_page) {
	if ($items_per_page == 0) { $items_per_page = 1; }
	$url = "";
	
	$page_count = ceil($total / $items_per_page);
	
	$html = "";
	
	if ($page_count <= 7) {
		for ($i = 1; $i <= $page_count; $i++) {
			$html .= "<a href='{$url}$i'><span>$i</span></a>";
		}
	}
	
	if ($page_count > 7 && $page_count <= 20) {
		for ($i = 1; $i <= 3; $i++) {
			$html .= "<a href='{$url}$i'><span>$i</span></a>";
		}
		$html .= " . . . ";
		for ($i = $page_count - 2; $i <= $page_count; $i++) {
			$html .= "<a href='{$url}$i'><span>$i</span></a>";
		}
	}
	
	if ($page_count > 20) {
		for ($i = 1; $i <= 3; $i++) {
			$html .= "<a href='{$url}$i'><span>$i</span></a>";
		}
		for ($i = 10; $i < $page_count; $i += 20) {
			$html .= " . . . ";
			$html .= "<a href='{$url}$i'><span>$i</span></a>";
		}
		$html .= " . . . ";
		for ($i = $page_count - 2; $i <= $page_count; $i++) {
			$html .= "<a href='{$url}$i'><span>$i</span></a>";
		}
	}
	
	return $html;
}

?>