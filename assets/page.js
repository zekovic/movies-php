
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
	
	$('.window-btn-close, .window-inner-btn-close').unbind('click').bind('click', function() {
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
		close_window('#frm_search');
		find_movies();
	});
	$('#frm_search #btn_search').unbind('click').bind('click', function() {
		find_movies();
	});
	$('#txt_find').unbind('keydown').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			close_window('#frm_search');
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
			open_window('#frm_search', {x: Math.max(window.innerWidth - 530, 2), y: $('#top_menu').height()}, null, null);
		} else {
			close_window('#frm_search');
		}
	});
	
	init_tabs();
	
	init_tags();
	
	set_pagination_links();
	
	
	//$('#btn_find_advanced').trigger('click'); ///////////////////
	
});

function init_tabs() {
	$('.tab-wrap').each(function() {
		$panels = $("#"+$(this).attr('for'));
		$panels.children(':not(.selected)').hide();
		if ($panels.children('.selected').length) {
			$panels.children('.selected').show();
		} else {
			$panels.children(':first').show();
			
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
}

function init_tags() {
	$('.tag-item').unbind('click').bind('click', function() {
		
		$(this).toggleClass('selected');
		
		var $wrap = $(this).closest('.tag-wrap');
		var tag_name = $wrap.attr('tag');
		
		if ($wrap.find('.tag-item.selected').length == 0 ||
				$wrap.find('.tag-item.selected').length == $wrap.find('.tag-item').length) {
			$('['+tag_name+']').removeClass('marked-tag');
			$('.movie-item').show();
			$wrap.find('.tag-item').removeClass('selected');
			return;
		}
		$('['+tag_name+']').closest('.movie-item').hide();
		$('['+tag_name+']').removeClass('marked-tag');
		$('.tag-item.selected').each(function() {
			var $found_items = $('['+tag_name+'="'+tag_name+'-'+$(this).attr('id')+'"]');
			$found_items.closest('.movie-item').show();
			$found_items.addClass('marked-tag');
		});
	});
}

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
	url_str = url_str.replace('&&page=', '&page=');
	
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
	args = get_find_parameters();
	if (args.error !== undefined) {
		write_find_warning(args.error);
		return;
	}
	if (!Object.keys(args.parameters).length) {
		write_find_warning("No parameters given...");
		return;
	}
	var parameters_json = JSON.stringify(args.parameters);
	var new_url = "/"+ args.path +"/find/?filter="+parameters_json;
	//console.log(new_url);
	location.href = new_url;
}

function write_find_warning(str) {
	var $el = $('#search_warning');
	$el.html(str).show();
	setTimeout(function() { $el.fadeOut(); }, 1000);
}

function get_find_parameters() {
	var url_path = 'movie';
	if ($('#frm_search').is(':hidden')) {
		var find_str = $('#txt_find').val().trim();
		if (find_str && find_str.length < 3) { return {error: "Title value is too short"}; }
		return {path: url_path, parameters: {title: prepare_url_string(find_str)}};
	} else {
		var selected_id = $('#search_panels').children(':visible').attr('id');
		var args = {};
		if (selected_id == 'search_movies') {
			url_path = 'movie';
			var title_val = prepare_url_string($('#txt_find_title').val().trim());
			if (title_val && title_val != '') { args.title = title_val; }
			if (title_val && title_val.length < 3) { return {error: "Title value is too short"}; }
			
			var year_val = parseInt($('#txt_find_year').val().trim());
			if (year_val) { args.year = year_val; }
			if (year_val === NaN || year_val < 1900 || year_val > 9999) { return {error: "Wrong year format..."}; }
			
			var keyword_val = prepare_url_string($('#txt_find_keyword').val().trim());
			if (keyword_val && keyword_val != '') { args.keyword = keyword_val; }
			if (keyword_val && keyword_val.length < 2) { return {error: "Keyword value is too short"}; }
			
			var genres_arr = [];
			$('#tags_find_genre').children('.selected').each(function() { genres_arr.push(parseInt($(this).attr('id'))); });
			if (genres_arr.length) { args.genres = genres_arr; }
		}
		if (selected_id == 'search_actors') {
			url_path = 'actor';
			var actor_val = prepare_url_string($('#txt_find_actor').val().trim());
			if (actor_val && actor_val != '') { args.actor = actor_val; }
			if (actor_val && actor_val.length < 3) { return {error: "Actor value is too short"}; }
			
			var character_val = prepare_url_string($('#txt_find_character').val().trim());
			if (character_val && character_val != '') { args.character = character_val; }
			if (character_val && character_val.length < 3) { return {error: "Character value is too short"}; }
		}
		if (selected_id == 'search_crew') {
			url_path = 'crew';
			var crew_name_val = prepare_url_string($('#txt_find_crew_name').val().trim());
			if (crew_name_val && crew_name_val != '') { args.crew_name = crew_name_val; }
			if (crew_name_val && crew_name_val.length < 3) { return {error: "Name value is too short"}; }
			
			var company_val = prepare_url_string($('#txt_find_company').val().trim());
			if (company_val && company_val != '') { args.company = company_val; }
			if (company_val && company_val.length < 3) { return {error: "Company value is too short"}; }
			
			var jobs_arr = [];
			$('#tags_find_job').children('.selected').each(function() { jobs_arr.push(parseInt($(this).attr('id'))); });
			if (jobs_arr.length) { args.jobs = jobs_arr; }
		}
		return {path: url_path, parameters: args};
	}
	return {error: "No parameters given..."};
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

