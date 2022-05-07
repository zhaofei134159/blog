<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>黄金矿工小游戏</title>
	<style type="text/css">
		body{overflow:-Scroll;overflow-x:hidden;}
		.outer{margin:0 auto;width:90%;height:700px;}
		.upper{width:100%;height:100px;background:#FFCE31;}
		.upperLeft{width:30%;height:100%;float:left;position:relative;}
		.upperCenter{width:40%;height:100%;float:left;line-height:194px;background:white;border-radius:100px;position:relative;}
		.charDiv{position:absolute;bottom:68px;left:40%;}
		/* 14px */
		.charDiv .chart{margin:0px 10px;width:85px;height:68px;position:absolute;}
		.charDiv .hook_rope{position:absolute;left: 35px;bottom: -46px;z-index:1001;transform-origin:top left; animation: action 5s linear infinite;}
		.charDiv .rope{position:absolute;width:2px;height:54px;background:#2F3B3F;z-index: 101;}
		.charDiv .hookImg{position:absolute;width: 35px;height: 24px;bottom:-73px;left:-17px;}
		.charDiv #golds{position:absolute;width: 30px;height: 20px;bottom:-78px;left:-13px;}
		@keyframes action {
			0% {-webkit-transform: rotate(0deg);}
			25% {-webkit-transform: rotate(65deg);}
			50% {-webkit-transform: rotate(0deg);}
			75% {-webkit-transform: rotate(-65deg);}
			100% {-webkit-transform: rotate(0deg);}
		}
		.upperRight{width:30%;height:100%;float:left;position:relative;}
		.cennter{background-image:url('<?=HOME_PUBLIC_URL?>/img/pictures/brick.png');height:10px;position:relative;z-index:100;}
		.lower{width:100%;height:600px;background-image:url('<?=HOME_PUBLIC_URL?>/img/pictures/level-background-0.jpg');background-size: 100% 100%;position:relative;}
	</style>

	<meta name="keywords" content="黄金矿工 小游戏">
    <meta name="description" content="使用js、css、html实现 黄金矿工小游戏">
    
	<link rel="shortcut icon" href="<?=PUBLIC_URL?>images/favicon.ico" />
	<script src="<?=PUBLIC_URL?>js/jquery-1.9.1.min.js"></script>
</head>
<body>
	<div class="outer">
		<div class="upper">
			<div class="upperLeft"></div>
			<div class="upperCenter">
				<!-- 两个:25,55  三个:20,40,60 -->
				<div class="charDiv">
					<img src="<?=HOME_PUBLIC_URL?>/img/pictures/char1.jpg" alt="矿工" class="chart" data-str="1">
					<div class="hook_rope" data-type="1">
						<div class="rope"></div>
						<img src="<?=HOME_PUBLIC_URL?>/img/pictures/hook_mask.png" class="hookImg"  alt="">
						<img src="<?=HOME_PUBLIC_URL?>/img/pictures/diamond.png" id="golds"  alt="" style="display:none;">
					</div>
				</div>
			</div>
			<div class="upperRight"></div>
		</div>
		<div class="cennter"></div>
		<div class="lower">
		</div>
	</div>
	<script src="<?=HOME_PUBLIC_URL?>js/game/miner.js?<?=HOME_CACHE_TIME?>"></script>
	<script src="<?=HOME_PUBLIC_URL?>js/game/gameWelcome.js?<?=HOME_CACHE_TIME?>"></script>
</body>
</html>