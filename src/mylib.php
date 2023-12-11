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
	if ($current > $page_count) {
		$current = $page_count;
	}
	
	if ($page_count <= 1) {
		return "";
	}
	
	$html = "";
	$html_arr = [];
	
	if ($page_count <= 7) {
		for ($i = 1; $i <= $page_count; $i++) {
			$html_arr[] = $i;
		}
	}
	
	if ($page_count > 7 && $page_count <= 20) {
		for ($i = 1; $i <= 3; $i++) {
			$html_arr[] = $i;
		}
		for ($i = $page_count - 2; $i <= $page_count; $i++) {
			$html_arr[] = $i;
		}
	}
	
	if ($page_count > 20 && $page_count <= 100) {
		for ($i = 1; $i <= 3; $i++) {
			$html_arr[] = $i;
		}
		for ($i = 10; $i < $page_count; $i += 20) {
			$html_arr[] = $i;
		}
		for ($i = $page_count - 2; $i <= $page_count; $i++) {
			$html_arr[] = $i;
		}
	}
	
	if ($page_count > 100) {
		for ($i = 1; $i <= 3; $i++) {
			$html_arr[] = $i;
		}
		for ($i = 10; $i < $page_count; $i += floor($page_count / 2)) {
			$html_arr[] = $i;
		}
		for ($i = $page_count - 2; $i <= $page_count; $i++) {
			$html_arr[] = $i;
		}
	}
	
	if (!in_array($current - 1, $html_arr) && $current > 1) { $html_arr[] = $current -1 ; }
	if (!in_array($current, $html_arr)) { $html_arr[] = $current; }
	if (!in_array($current + 1, $html_arr) && $current < $page_count) { $html_arr[] = $current + 1; }
	sort($html_arr);
	$html_arr = array_unique($html_arr);
	
	$previous = max(1, $current - 1);
	$html .= "<a number=$previous href=''><span>&lt;</span></a>&nbsp;&nbsp;&nbsp;";
	foreach ($html_arr as $i => $number) {
		if ($i > 0 && isset($html_arr[$i - 1]) && $html_arr[$i - 1] < $number - 1) {
			$html .= " &nbsp; ";
		}
		$selected_html = $number == $current ? "class=current" : "";
		$html .= "<a $selected_html number=$number href='$number'><span>$number</span></a>";
	}
	$next = min($current + 1, $page_count);
	$html .= "&nbsp;&nbsp;&nbsp;<a number=$next href=''><span>&gt;</span></a>";
	
	return $html;
}

function format_long_number($val) {
	$val = (int)$val;
	
	if ($val < 1000) {
		return "$val";
	}
	if ($val >= 1000 && $val < 1000000) {
		return (floor($val / 100) / 10)."K";
	}
	if ($val >= 1000000 && $val < 1000000000) {
		return (floor($val / 100000) / 10)."M";
	}
	if ($val >= 1000000000) {
		return (floor($val / 100000000) / 10)."B";
	}
	
}




?>