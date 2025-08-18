<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= Info::$site_title ?></title>
<link rel="icon" type="image/x-icon" href="/assets/img/favicon.png">
<script type="text/javascript" src="/assets/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="/assets/jquery-ui.min.js"></script>

<script type="text/javascript" src="/assets/page.js?a=<?= time() ?>"></script>
<link rel="stylesheet" type="text/css" href="/assets/style.css?a=<?= time() ?>">
</head>
<body>

<style type="text/css">
.window { position: fixed; background: #d1f6ff; border: 1px solid #5b708f; border-radius: 6px;
	width: 50%; height: 50%; z-index: 1000; left: 25%; top: 15%; }
.window * { font-size: 10pt; }
.window-titlebar { display: block; cursor: move; background: #193a89; color: #bfdffb; height: 24px; overflow: hidden; border-radius: 5px 5px 0px 0px;
	white-space: nowrap; position: relative; }
.window-title { display: inline; cursor: move; vertical-align: sub; font-weight: bold; padding: 0px 10px; }
.window-btn-close { display: inline; float: right; width: 36px; height: 24px; cursor: pointer; text-align: center; line-height: 20px;
	position: absolute; top: 0px; right: 0px; background-color: #193a89; }
.window-btn-close:HOVER { background-color: #77a7c1; color: #274463; }
.window-content { position: relative; height: 95%; display: block; padding: 10px; /*background: #fff;*/ }
.window-modal-backbround { display: none; position: fixed; width: 100%; height: 100%; padding: 0px; margin: 0px; background-color: #9bc2d1ad; top: 0px; left: 0px; z-index: 990; }
</style>

<div>
	<div class=window id=wnd_movie style="display: none;">
		<div class=window-titlebar>
			<div class=window-title></div>
			<div class=window-btn-close>x</div>
		</div>
		<div class=window-content></div>
	</div>
	<div class=window-modal-backbround style="display: none;"></div>
</div>

<div id=all_wrap>
