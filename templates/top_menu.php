<div id=top_menu>
	<a href="/"><span >Home</span></a>
	<a href="/movie"><span>Movies</span></a>
	<a href="/genre"><span>Genres</span></a>
	<a><span>Actors</span></a>
	<a><span>Crew</span></a>
	<a><span>Companies</span></a>
	<span id=find_wrap>
	</span>
		<input id=txt_find type="text" placeholder="search" value="<?= get_find_filter('title') ?>"/>
		<span id=wrap_txt_btn_find>
			<span id=btn_find_advanced title='Advanced search'></span>
			<span id=btn_find_clear title='Clear'></span>
		</span>
	<span id=btn_find></span>
	<a><span>User</span></a>
	<a><span>Settings</span></a>
</div>


<div>
	<div class=window id=frm_search style="display: none; border-radius: 0px 0px 6px 6px; width: 350px; height: 250px;">
		<div class=window-content>
			<input id=txt_find_title type="text" placeholder="Title" />
			<br/>
			<input id=txt_find_actor type="text" placeholder="Actors" />
			<br/>
			<input id=txt_find_company type="text" placeholder="Company" />
			<br/>
		</div>
	</div>
</div>