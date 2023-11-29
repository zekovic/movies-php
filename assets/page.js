$(document).ready(function() {
	
	select_menu_item();
	
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
	
	$('.window').draggable({handle: '.window-titlebar'});
});

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