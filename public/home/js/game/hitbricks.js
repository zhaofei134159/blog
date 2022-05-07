$(function(){
	// 初始化 砖块
	brickInit(1);
})

// 边框
var outerTop = parseInt($('.outer').position().top);
var outerBottom = outerTop + parseInt($('.outer').css('height'));
var outerLeft = parseInt($('.outer').position().left);
var outerRight = outerLeft + parseInt($('.outer').css('width'));

// 背景色
var outerBackground = '#8BB9E5';
// 颜色组合
var colorArr = ['#FA3B1A','#F76C00','#F2F00A','#0FF20A','#17E3C6','#0A34D8','#E80AC1'];
// 小球运动
var ballInterval = null;
// 移动速度
var speed = 10;
// 球的直径
var diameter = 17;
// 板子的宽高
var trolleyWidth = 120;
var trolleyHeight = 20;
// 砖块宽高
var brickWidth = 75;
var brickHeight = 20;

/**
* 初始化 砖块
* shut 关
*/ 
function brickInit(shut){
	html = '';
	if(shut==1){
		var rows = 16;
		for(i=1;i<=rows;i++){
			for(j=1;j<=i;j++){
				var rand = Math.floor(Math.random()*7);
				html += '<div class="brick" style="background:'+colorArr[rand]+';" data-i="'+i+'" data-j="'+j+'"></div>';
			}
			html += '<div style="clear:both"></div>';
		}
	}
	$('#brickDiv').html(html);
}

// 小车移动事件 发射球
document.onkeydown = function(event){
	var e = window.event || event;
	var trolleyLeft = parseInt($('#trolley').position().left);
	var ballLeft = parseInt($('.ball').position().left);

	if (e.keyCode == 37) { // 左
		var left_cur = trolleyLeft - 20;
		var left_ball_cur = ballLeft - 20;
		if(left_cur <= outerLeft){
			left_cur = outerLeft;
		}
		$('#trolley').css('left',left_cur+'px');

		// 如果没有发射球
		if(ballInterval==null){
			$('.ball').css('left',left_ball_cur+'px');
		}
	}else if (e.keyCode == 39) { // 右
		var left_cur = trolleyLeft + 20;
		var left_ball_cur = ballLeft + 20;
		if(left_cur + trolleyWidth >= outerRight){
			left_cur = outerRight - trolleyWidth - 10;
		}

		$('#trolley').css('left',left_cur+'px');
		// 如果没有发射球
		if(ballInterval==null){
			$('.ball').css('left',left_ball_cur+'px');
		}
	}else if(e.keyCode == 32){
		ballfly();
		return false;
	}

}

// 球飞
function ballfly(){

	// 判断已经启动 则退出
	if(ballInterval!=null){
		return false;
	}

	var brick = $('.brick');

	var abeam = '';
	var vertical = '';
	ballInterval = setInterval(function(){
		var trolleyTop = parseInt($('#trolley').position().top);
		var trolleyLeft = parseInt($('#trolley').position().left);
		var trolleyRight = trolleyLeft + trolleyWidth;
		var trolleyBottom = trolleyTop + trolleyHeight;

		var ballLeft = parseInt($('.ball').position().left);
		var ballTop = parseInt($('.ball').position().top);
		var ballRight = ballLeft + diameter;
		var ballBottom = ballTop + diameter;

		var ball_cur_Left = ballLeft;
		var ball_cur_Right = ballLeft + diameter;
		var ball_cur_Top = ballTop;
		var ball_cur_Bottom = ballTop + diameter;

		// 判断球在板子上
		if(ballLeft >= trolleyLeft && ballRight <= trolleyRight && ballBottom >= trolleyTop){
			// 如果在板子的左侧 则向左侧飞 (板子左侧 + 75 是否 小于等于 球的左侧)
			if(trolleyLeft + trolleyWidth / 2 <= ball_cur_Left){
				ball_cur_Left += speed;
				abeam = '+';
			}else{
				ball_cur_Left -= speed;
				abeam = '-';
			}
			ball_cur_Top -= speed;
			vertical = '-';
		}else{
			// 是否碰到顶部
			if(ballTop < outerTop - speed && vertical == '-'){
				ball_cur_Top += speed;
				vertical = '+';
			}else if(ballBottom > outerBottom - speed && vertical == '+'){
				// 是否碰到底部
				ball_cur_Top -= speed;
				vertical = '-';
			}else{
				if(vertical == '-'){
					ball_cur_Top -= speed;
				}else{
					ball_cur_Top += speed;
				}
			}

			// 是否碰到左侧
			if(ballLeft < outerLeft - speed && abeam == '-'){
				ball_cur_Left += speed;
				abeam = '+';
			}else if(ballRight > outerRight - speed && abeam == '+'){
				ball_cur_Left -= speed;
				abeam = '-';
			}else{
				if(abeam == '-'){
					ball_cur_Left -= speed;
				}else{
					ball_cur_Left += speed;
				}
			}
		}

		// 判断是否撞击到砖块了
		for (var i=0;i<brick.length;i++)
		{
			// 如果被撞击过 就跳过
			if(brick.eq(i).attr('hit')=='1'){
				continue;
			}

			var ranking = brick.eq(i).attr('data-j');
			var brickTop = parseInt(brick.eq(i).position().top);
			var brickLeft = parseInt(brick.eq(i).position().left);
			var brickBottom = brickTop + brickHeight;
			var brickRight = brickLeft + brickWidth;

			// 四个方向撞击
			// 上
			if(ball_cur_Bottom >= brickTop - 10 && ball_cur_Left >= brickLeft && ball_cur_Right <= brickRight && ball_cur_Top <= brickTop){
				ball_cur_Left = ballLeft;
				ball_cur_Top = ballTop;

				if(abeam == '-'){
					ball_cur_Left -= speed;
					abeam = '-';
				}else{
					ball_cur_Left += speed;
					abeam = '+';
				}
				if(vertical == '+'){
					ball_cur_Top -= speed;
					vertical = '-';
				}else{
					ball_cur_Top += speed;
					vertical = '+';
				}
				brick.eq(i).css('background',outerBackground).attr('hit','1');
			}

			// 下
			if(ball_cur_Top <= brickBottom && ball_cur_Left >= brickLeft && ball_cur_Right <= brickRight && ball_cur_Bottom >= brickBottom){
				ball_cur_Left = ballLeft;
				ball_cur_Top = ballTop;

				if(abeam == '-'){
					ball_cur_Left -= speed;
					abeam = '-';
				}else{
					ball_cur_Left += speed;
					abeam = '+';
				}
				if(vertical == '+'){
					ball_cur_Top -= speed;
					vertical = '-';
				}else{
					ball_cur_Top += speed;
					vertical = '+';
				}

				brick.eq(i).css('background',outerBackground).attr('hit','1');
			}

			// 左
			if(ball_cur_Top >= brickTop && ball_cur_Bottom <= brickBottom && ball_cur_Right >= brickLeft - 10 && ball_cur_Left <= brickLeft){
				ball_cur_Left = ballLeft;
				ball_cur_Top = ballTop;

				if(abeam == '+'){
					ball_cur_Left -= speed;
					abeam = '-';
				}else{
					ball_cur_Left += speed;
					abeam = '+';
				}
				if(vertical == '-'){
					ball_cur_Top -= speed;
					vertical = '-';
				}else{
					ball_cur_Top += speed;
					vertical = '+';
				}

				brick.eq(i).css('background',outerBackground).attr('hit','1');
			}

			// 右
			if(ball_cur_Top >= brickTop && ball_cur_Bottom <= brickBottom && ball_cur_Left <= brickRight + 10 && ball_cur_Right >= brickRight){
				ball_cur_Left = ballLeft;
				ball_cur_Top = ballTop;

				if(abeam == '+'){
					ball_cur_Left -= speed;
					abeam = '-';
				}else{
					ball_cur_Left += speed;
					abeam = '+';
				}
				if(vertical == '-'){
					ball_cur_Top -= speed;
					vertical = '-';
				}else{
					ball_cur_Top += speed;
					vertical = '+';
				}
				brick.eq(i).css('background',outerBackground).attr('hit','1');
			}

		}

		if(ball_cur_Top > trolleyBottom){
			alert('you die！');
			clearInterval(ballInterval);
			window.location.reload();
		}

		$('.ball').eq(0).css('left',ball_cur_Left+'px').css('top',ball_cur_Top+'px');
	},60)
}