
<style type="text/css">


#top_menu {
	position: fixed;
	z-index: 10000;
	font-size: 0px;
	top: 0px;
	left: 0px;
	cursor: default;
	/*
	padding: 7px 0px;
	margin: 5px;
	float: right;
	*/
	text-align: right;
	width: 100%;
	background-color: #0269c1;
	/*border-radius: 5px;*/
}
#top_menu * { font-size: 11pt; }

#top_menu > a {
	display: inline-block;
	cursor: pointer;
	padding: 8px 12px;
	
	margin: 0px 0px 0px 0px;
	color: #bfcbef;
	
	background: rgb(14,14,112);
	background: linear-gradient(0deg, rgba(14,14,112,1) 20%, rgba(44,107,218,1) 87%);
}
#top_menu > a:HOVER {
	background: rgb(14,14,54);
	background: linear-gradient(0deg, rgba(14,14,54,1) 20%, rgba(23,70,154,1) 87%);
}

#top_menu > a.separator {
	cursor: default;
	min-width: 20px;
}

#top_menu .menu-item-selected {
	color: #ebeb81;
}

#top_menu #find_wrap {
	/*text-align: left;*/
	
}
#top_menu #wrap_txt_btn_find {
	margin: 0px 5px 0px -53px;
	text-align: right;
}
#top_menu #txt_find {
	border-radius: 5px;
	border-width: 0px;
	height: 24px;
	padding: 0px 10px;
	margin: 0px 10px;
	font-size: 11pt;
	outline: none;
}
#top_menu #btn_find_advanced {
	display: inline-block;
	background: 0px 0px no-repeat url(/assets/img/Dots_Horizontal_Square_light.svg);
	width: 24px;
	height: 24px;
	margin: 0px 0px -6px -5px;
	cursor: pointer;
}
#top_menu #wrap_txt_btn_find svg path{
  fill: #888;
}
#top_menu #btn_find_clear {
	display: inline-block;
	background: 0px 0px no-repeat url(/assets/img/Multiply_Square_light.svg);
	width: 24px;
	height: 24px;
	margin: 0px 3px -6px -5px;
	cursor: pointer;
}
#top_menu #btn_find {
	display: inline-block;
	background: 0px 0px no-repeat url(/assets/img/Search_Square.svg);
	width: 24px;
	height: 24px;
	margin: 0px 8px -6px -5px;
	cursor: pointer;
}

#frm_search { display: none; border-radius: 0px 0px 6px 6px; width: 500px; height: 395px; max-width: 90%; max-height: 90%; }
#search_panels { margin: 25px 0px 25px 0px; }
#search_panels > div > div { margin: 5px 0px 5px 0px; }
#search_panels label { display: inline-block; margin: 5px 10px 0px 0px; vertical-align: top; width: 20%; text-align: right; }
#search_panels input { display: inline-block; width: 60%; height: 20px; padding: 2px 10px; }
#search_panels select { display: inline-block; width: 18%; height: 25px; padding: 2px 10px; margin: 0px 0px 0px -5px; }
#search_panels .input-area { display: inline-block; width: 60%; max-height: 160px; overflow: auto; padding: 2px 24px 2px 0px; }

#search_warning { position: absolute; bottom: 20px; left: 20px; color: #ff5500; text-align: right; width: 55%;}
#search_bottom_wrap { position: absolute; bottom: 20px; right: 20px; /*float: right;*/ }

</style>

<div id=top_menu>
	<a href="/movie"><span>Movies</span></a>
	<a href="/genre"><span>Genres</span></a>
	<a href="/company"><span>Companies</span></a>
	<span id=find_wrap>
	</span>
		<input id=txt_find type="text" placeholder="search" value="<?= get_find_filter('title') ?>"/>
		<span id=wrap_txt_btn_find>
			<span id=btn_find_advanced title='Advanced search'></span>
			<span id=btn_find_clear title='Clear'></span>
		</span>
	<span id=btn_find></span>
	<a><span>User</span></a>
</div>


<div>
	<div class=window id=frm_search>
		<div class=window-content>
			
			<div class=tab-wrap for=search_panels>
				<span for=search_movies class=selected>Movies</span>
				<span for=search_actors>Actors</span>
				<span for=search_crew>Crew</span>
			</div>
			<div id=search_panels>
				<div id=search_movies>
					<div><label for=txt_find_title>Title:</label><input id=txt_find_title type="text" placeholder="title" /></div>
					<div><label for=txt_find_year>Year:</label><input id=txt_find_year type="number" placeholder="year" /></div>
					<div><label for=txt_find_keyword>Keyword:</label><input id=txt_find_keyword type="text" placeholder="keyword" /></div>
					<div>
						<?php 
						$languages = get_languages();
						$countries = get_contries();
						?>
						<label for=combo_find_language>Language:</label>
						<select id=combo_find_language>
							<option value="">---</option>
							<?php foreach ($languages as $i => $item) { echo "<option value='{$item['language_code']}'>{$item['language_name']}</option>"; }  ?>
						</select>
						<label for=combo_find_country>Country:</label>
						<select id=combo_find_country>
							<option value="">---</option>
							<?php foreach ($countries as $i => $item) { echo "<option value='{$item['country_iso_code']}'>{$item['country_name']}</option>"; }  ?>
						</select>
					</div>
					<div><label for=tags_find_genre>Genre:</label><span class='tag-wrap input-area' id=tags_find_genre tag=search_genre>
						<?php 
						$genres = get_genres();
						foreach ($genres as $i => $item) {
							echo "<span class=tag-item id='{$item['genre_id']}'>{$item['genre_name']}</span>";
						}
						?>
					</span></div>
				</div>
				<div id=search_actors>
					<div><label for=txt_find_actor>Actor:</label><input id=txt_find_actor type="text" placeholder="actor" /></div>
					<div><label for=txt_find_character>Character:</label><input id=txt_find_character type="text" placeholder="character" /></div>
				</div>
				<div id=search_crew>
					<div><label for=txt_find_crew_name>Name:</label><input id=txt_find_crew_name type="text" placeholder="name" /></div>
					<div><label for=txt_find_company>Company:</label><input id=txt_find_company type="text" placeholder="company" /></div>
					<div><label for=tags_find_job>Job:</label><span class='tag-wrap input-area' id=tags_find_job tag=search_job>
						<?php 
						$jobs = get_departments();
						foreach ($jobs as $i => $item) {
							echo "<span class=tag-item id='{$item['department_id']}'>{$item['department_name']}</span>";
						}
						?>
					</span></div>
				</div>
			</div>
			<div id=search_warning></div>
			<div id=search_bottom_wrap>
				<span class=btn id=btn_search>Search</span>
				<span class='btn window-inner-btn-close' id=btn_close_search>Close</span>
			</div>
		</div>
	</div>
</div>
