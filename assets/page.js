
function open_window(el, pos, title, callback) {
	var $el = $(el);
	var wnd_top = 0, wnd_left = 0, wnd_height = 0; wnd_width = 0;
	if (pos === null) {
		var wnd_w = window.innerWidth / 2;
		$el.css({top: window.innerHeight / 6, width: wnd_w});
		$el.css({left: (window.innerWidth / 2) - (wnd_w / 2) });
	}
	if (pos && pos.x !== undefined && pos.y !== undefined) {
		$el.css({top: pos.y});
		$el.css({left: pos.x});
	}
	
	$('.window-modal-backbround').fadeIn(150);
	$el.fadeIn(150);
	if (title) {
		$el.find('.window-title').html(title);
	}
	if (callback) {
		callback;
	}
}

function close_window(el) {
	$('.window-modal-backbround').hide();
	$(el).hide();
}

$(document).ready(function() {
	
	select_menu_item();
	if ($('#txt_find').val() != '') {
		expand_find_txt(0);
	}
	
	$('.movie-btn-info').unbind('click').bind('click', function() {
		var $movie = $(this).closest('.movie-item');
		id = $movie.attr('id');
		
		open_window('#wnd_movie', null, $movie.find('.movie-title').text(), get_movie_details(id));
		
	});
	
	$('.window-btn-close').unbind('click').bind('click', function() {
		//$('.window-modal-backbround').hide();
		//$(this).closest('.window').hide();
		close_window($(this).closest('.window'));
	});
	$(document).unbind('keydown').bind('keydown', function(e) {
		if (e.keyCode == 27) {
			close_window('.window');
		}
	});
	$('.window').draggable({handle: '.window-titlebar'});
	
	$('#btn_find').unbind('click').bind('click', function() {
		find_movies();
	});
	$('#txt_find').unbind('keydown').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			find_movies();
		}
	});
	$('#txt_find').unbind('focus').bind('focus', function(e) {
		expand_find_txt();
	});
	$('#txt_find').unbind('blur').bind('blur', function(e) {
		if ($(this).val() == '') {
			collapse_find_txt();
		}
	});
	$('#btn_find_clear').unbind('click').bind('click', function() {
		$('#txt_find').val('');
		collapse_find_txt();
	});
	$('#btn_find_advanced').unbind('click').bind('click', function() {
		if ($('#frm_search').is(":hidden")) {
			open_window('#frm_search', {x: Math.max(window.innerWidth - 500, 2), y: $('#top_menu').height()}, null, null);
		} else {
			close_window('#frm_search');
		}
	});
	
	$('.tab-wrap > span').unbind('click').bind('click', function() {
		var $tabs = $(this).closest('.tab-wrap');
		var $panels = $('#'+$tabs.attr('for'));
		$tabs.children().removeClass('selected');
		$(this).addClass('selected');
		//$('#'+$(this).attr('for')+' > div').hide();
		$panels.children().hide();
		$panels.find('#'+$(this).attr('for')).fadeIn(150);
	});
	
	
	
	set_pagination_links();
	
});

function set_pagination_links() {
	var args_arr = location.search.split('&');
	var i = 0, found_str = "";
	for (i in args_arr) {
		if (args_arr[i].indexOf("page=") === 0 || args_arr[i].indexOf("?page=") === 0) {
			found_str = args_arr[i];
			break;
		}
	}
	var url_str_search = location.search;
	if (found_str != '') {
		url_str_search = url_str_search.replace(found_str, '');
	}
	var url_str = location.pathname + url_str_search;
	url_str = url_str + (url_str_search == '' ? '?page=' : '&page=');
	
	
	$('.pagination a').each(function() {
		$(this).attr('href', url_str + $(this).attr('number'));
	});
}

function expand_find_txt(duration) {
	if ($('#txt_find').attr('is_expanded') == 'yes') { return; }
	if (duration === undefined) {duration = 200};
	var $txt = $('#txt_find');
	if ($txt.attr('original_width') === undefined) {
		$txt.attr('original_width', $txt.width());
		$txt.css('min-width', $txt.width());
	}
	$txt.animate({'width': '35%'}, duration);
	$txt.attr('is_expanded', 'yes');
}
function collapse_find_txt() {
	if ($('#txt_find').attr('is_expanded') == 'no') { return; }
	setTimeout(function() {
		$('#txt_find').animate({'width': $('#txt_find').attr('original_width')}, 200);
		$('#txt_find').attr('is_expanded', 'no');
	}, 100);
}

function find_movies() {
	var find_str = $('#txt_find').val().trim();
	if (find_str.length < 3) {
		return;
	}
	var new_url = "/movie/find/?filter="+JSON.stringify({title: prepare_url_string(find_str)});
	location.href = new_url;
}

function select_menu_item() {
	var path_arr = window.location.pathname.split('/');
	if (path_arr.length > 1 && path_arr[1] != "") {
		var found_item = $('#top_menu [href="/'+path_arr[1]+'"]');
		if (found_item.length) {
			found_item.addClass('menu-item-selected');
		}
	}
}

function get_movie_details(id) {
	$.post('/movie/details/'+id, function(data, is_success, response_obj) {
		if (is_success != 'success') {
			return;
		}
		//console.log(JSON.parse(data));
		var data_json = JSON.parse(data);
		result = 'Movie details: </br>';
		for (i in data_json) {
			result += (i+": <b>" + data_json[i] + '</b><br/>');
		}
		
		$('#wnd_movie .window-content').html(result);
	});
}

function prepare_url_string(str) {
	//str = str.replaceAll('#', '%23');
	return encodeURIComponent(str);
}

