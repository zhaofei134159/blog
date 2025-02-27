$(function(){
	// 动态生成底层物品
	goldInit(1);
})

// 外边框
var outer = $('.outer');
var outerTop = parseInt(outer.offset().top);
var outerBottom = parseInt(outer.offset().top) + parseInt(outer.css('height'));
var outerLeft = parseInt(outer.offset().left);
var outerRight = parseInt(outer.offset().left) + parseInt(outer.css('width'));

// 其他元素
var hook_rope = $('.hook_rope');
var rope = $('.rope');
var hookImg = $('.hookImg');
var golds = $('#golds');
var lower = $('.lower');
var lowerWidth = parseInt(lower.css('width'));
var lowerHeight = parseInt(lower.css('height'));
var lowerTop = parseInt(lower.offset().top);

// 拉住物品后
var grabInterval = null;
// 绳子伸长 缩短
var ropeElongationInterval = null;
// 绳子缩短 
var ropeShortenInterval = null;


// 动态生成底层物品
function goldInit(shut){

	html = '';
	if(shut==1){
		for(j=1;j<=3;j++){
			// if(j==1){
			// 	var width = 100;
			// 	var height = 80;
			// 	var src = '/public/home/img/pictures/small_gold.png';
			// 	var rows = 3;
			// }else if(j==2){
			// 	var width = 200;
			// 	var height = 150;
			// 	var src = '/public/home/img/pictures/big_gold.png';
			// 	var rows = 5;
			// }else 
			if(j==3){
				var width = 30;
				var height = 20;
				var src = '/public/home/img/pictures/diamond.png';
				var rows = 10;
			}
			for(i=1;i<=rows;i++){
				var vertical = Math.floor(Math.random() * (outerBottom - lowerTop) + lowerTop);
				var abeam = Math.floor(Math.random() * (outerRight - outerLeft) + outerLeft);

				if(vertical>300){
					vertical = outerBottom - vertical;
				}
				if(vertical < 150){
					vertical = 150;
				}
				if(abeam>300){
					abeam = outerRight - abeam;
				}

				html += '<img src="'+src+'" class="gold" data-i="'+i+'" style="width: '+width+'px;height: '+height+'px;position: absolute;left: '+abeam+'px;top: '+vertical+'px;"/>';
			}
		}
	}
	$('.lower').html(html);
}


document.onkeydown = function(event){
	var e = window.event || event;
	// 空格放下绳子
	if(e.keyCode == 32){
		reachClaw();
		return false;
	}
}

// 伸出爪子
function reachClaw(){
	// 已经伸爪了 
	if(hook_rope.attr('data-type') == '2' && (ropeElongationInterval != null || grabInterval!=null || ropeShortenInterval!=null)) {
		return false;
	}
	var gold = $('.gold');

	// 暂停动画
	hook_rope.attr('data-type','2');
	hook_rope.css('animation-play-state','paused');

	// 延长钩锁
	var i = 10;
	ropeElongationInterval = setInterval(function(){
		var ropeHeight = parseInt(rope.css('height'));
		var ropeHeightNow = ropeHeight + i;
		rope.css('height',ropeHeightNow+'px');

		var hookBottom = parseInt(hookImg.css('bottom'));
		var hookBottomNow = hookBottom - i;
		hookImg.css('bottom', hookBottomNow+'px');

		var goldsBottom = parseInt(golds.css('bottom'));
		var goldsBottomNow = goldsBottom - i;
		golds.css('bottom', goldsBottomNow+'px');

		var hookImgTop = parseInt(hookImg.offset().top);
		var hookImgBottom = parseInt(hookImg.offset().top) + parseInt(hookImg.css('height'));
		var hookImgLeft = parseInt(hookImg.offset().left);
		var hookImgRight = parseInt(hookImg.offset().left) + parseInt(hookImg.css('width'));

		// 超出边界 需要缩回
		if(hookImgRight<=outerLeft || hookImgTop>=outerBottom || hookImgLeft>=outerRight){
			clearInterval(ropeElongationInterval);
			ropeElongationInterval = null;

			ropeShortenInterval = setInterval("ropeShortenFun()",30);
		}

		// 所有物品
		for (var g=0;g<gold.length;g++)
		{
			var goldTop = parseInt(gold.eq(g).offset().top);
			var goldLeft = parseInt(gold.eq(g).offset().left);
			var goldBottom = goldTop + parseInt(gold.eq(g).css('height'));
			var goldRight = goldLeft + parseInt(gold.eq(g).css('width'));

			// console.log('爪子顶部: '+hookImgTop);
			// console.log('爪子左侧: '+hookImgLeft);
			// console.log('爪子右侧: '+hookImgRight);
			// console.log('爪子底部: '+hookImgBottom);
			// console.log('钻石顶部: '+goldTop);
			// console.log('钻石左侧: '+goldLeft);
			// console.log('钻石右侧: '+goldRight);
			// console.log('钻石底部: '+goldBottom);
			// console.log('======================================');

			// 判断是否抓住物品
			if(hookImgBottom>goldTop && hookImgBottom<goldBottom && ((hookImgLeft<=goldLeft && hookImgRight>=goldRight) || (hookImgLeft>goldLeft && hookImgLeft<goldRight) || (hookImgRight>goldLeft && hookImgRight<goldRight))){
				clearInterval(ropeElongationInterval);
				ropeElongationInterval = null;

				gold.eq(g).remove();
				golds.show();

				// 人物动画
				grabInterval = setInterval("grabObject()",200);
				// 绳子动画
				ropeShortenInterval = setInterval("ropeShortenFun()",200);

				break;
			}
		}


	},30);
}

// 绳子缩短
function ropeShortenFun(){
	var i = 10;

	var ropeHeight = parseInt(rope.css('height'));
	var ropeHeightNow = ropeHeight - i;
	var hookBottom = parseInt(hookImg.css('bottom'));
	var hookBottomNow = hookBottom + i;
	var goldsBottom = parseInt(golds.css('bottom'));
	var goldsBottomNow = goldsBottom + i;

	if(ropeHeightNow <= 54){
		ropeHeightNow = 54;
	}
	if(hookBottomNow >= -73){
		hookBottomNow = -73;
	}
	if(goldsBottomNow >= -78){
		goldsBottomNow = -78;
	}
	rope.css('height',ropeHeightNow+'px');
	hookImg.css('bottom', hookBottomNow+'px');
	golds.css('bottom', goldsBottomNow+'px');


	var hookImgTop = parseInt(hookImg.offset().top);
	var hookImgBottom = parseInt(hookImg.offset().top) + parseInt(hookImg.css('height'));
	var hookImgLeft = parseInt(hookImg.offset().left);
	var hookImgRight = parseInt(hookImg.offset().left) + parseInt(hookImg.css('width'));

	// 回到初始位置 继续动画
	if(ropeHeightNow <= 54 && ropeElongationInterval == null){
		// 绳子动画
		clearInterval(ropeShortenInterval);
		ropeShortenInterval = null;

		// 启动动画
		hook_rope.attr('data-type','1');
		hook_rope.css('animation-play-state','running');
		$('#golds').hide();
	}
}

// 拉住物体
function grabObject(soc){
	var chart = $('.chart');
	if(chart.attr('data-str') == 1){
		chart.attr('src','/public/home/img/pictures/char2.jpg');
		$('.charDiv').css('bottom','68px');
		chart.attr('data-str','2');
	}else{
		chart.attr('src','/public/home/img/pictures/char1.jpg');
		$('.charDiv').css('bottom','68px');
		chart.attr('data-str','1');
	}

	var ropeHeight = parseInt(rope.css('height'));
	// 
	if(ropeHeight <= 54 && ropeElongationInterval == null){
		// 任务动画
		clearInterval(grabInterval);
		grabInterval = null;

		$('.chart').attr('src','/public/home/img/pictures/char1.jpg');
		$('.charDiv').css('bottom','68px');
		$('.chart').attr('data-str','1');
	}
}
