<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>打砖块小游戏</title>
	<style type="text/css">
		body{overflow:-Scroll;overflow-x:hidden;}
		.outer{margin:0 auto;width:90%;height:700px;background:#8BB9E5;position:relative;}
		#brickDiv{width:90%;margin:0px auto;padding-top:30px;}
		.brick{width: 75px;height: 20px;float:left;line-height: 20px;border-radius: 4px;margin:1px;}
		#trolleyDiv{}
		.ball{width: 17px;height: 17px;border-radius: 10px;background: white;position:absolute;bottom:50px;left:49%;}
		#trolley{width: 120px;height: 20px;border-radius: 10px;background: #343939;position:absolute;bottom:30px;left:45%;}
	</style>

	<meta name="keywords" content="打砖块 小游戏">
    <meta name="description" content="使用js、css、html实现 打砖块小游戏">
    
	<link rel="shortcut icon" href="<?=PUBLIC_URL?>images/favicon.ico" />
	<script src="<?=PUBLIC_URL?>js/jquery-1.9.1.min.js"></script>
</head>
<body>
	<div class="outer">
		<div id="brickDiv"></div>
		<div id="trolleyDiv">
			<div class="ball"></div>
			<div id="trolley"></div>
		</div>
	</div>
	<script src="<?=HOME_PUBLIC_URL?>js/game/hitbricks.js?<?=HOME_CACHE_TIME?>"></script>
	<script src="<?=HOME_PUBLIC_URL?>js/game/gameWelcome.js?<?=HOME_CACHE_TIME?>"></script>
</body>
</html>
