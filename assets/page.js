$(document).ready(function() {
	
	select_menu_item();
	if ($('#txt_find').val() != '') {
		expand_find_txt(0);
	}
	
	$('.movie-btn-info').unbind('click').bind('click', function() {
		var $movie = $(this).closest('.movie-item');
		id = $movie.attr('id');
		console.log(id);
		
		//$('.window').css({top: window.pageYOffset + 150});
		var wnd_w = window.innerWidth / 2;
		$('.window').css({top: window.innerHeight / 6, width: wnd_w});
		$('.window').css({left: (window.innerWidth / 2) - (wnd_w / 2) });
		//$('.window').css({left: 100 });
		$('.window').fadeIn(50);
		$('.window-content').html("Movie ID: " + id);
		$('.window-title').html($movie.find('.movie-title').text());
		get_movie_details(id);
	});
	
	$('.window-btn-close').unbind('click').bind('click', function() {
		$(this).closest('.window').hide();
	});
	
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
	
	$('.window').draggable({handle: '.window-titlebar'});
	
	set_pagination_links();
	
});

function set_pagination_links() {
	var url_str = location.href + (location.search == '' ? '?' : '&');
	url_str += 'page=';
	
	$('.pagination a').each(function() {
		$(this).attr('href', url_str + $(this).text());
	});
}

function expand_find_txt(duration) {
	if (duration === undefined) {duration = 200};
	var $txt = $('#txt_find');
	if ($txt.attr('original_width') === undefined) {
		$txt.attr('original_width', $txt.width());
		$txt.css('min-width', $txt.width());
	}
	$txt.animate({'width': '35%'}, duration);
}
function collapse_find_txt() {
	setTimeout(function() {
		$('#txt_find').animate({'width': $('#txt_find').attr('original_width')}, 200);
	}, 100);
}

function find_movies() {
	var find_str = $('#txt_find').val().trim();
	if (find_str.length < 3) {
		return;
	}
	var new_url = "/movie/find/?filter="+JSON.stringify({title: find_str});
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
		
		$('.window-content').html(result);
	});
}