<?php
/*
* 紫微斗数排盘
* 1. 公元纪年对应的天干（公元纪年的尾数）
* 4 5 6 7 8 9 0 1 2 3
* 甲乙丙丁戊己庚辛壬癸
*/
include_once './common.php';
include_once './conf/ziwei.fun.php';
include_once S_PATH.'/../class/lunar/vendor/autoload.php';
use com\nlf\calendar\util\LunarUtil;
use com\nlf\calendar\Lunar;
use com\nlf\calendar\Solar;

# 确定农历的生辰
$date = '1994-07-02 05:30:00';
// $date = '一九九四年五月二十四 05:30:00';

// $date = '一九九四年三月二十四 07:30:00';
// $date = '一九九四年十一月二十四 19:30:00';

$data = array();
$data['date'] = $date;
$data['dateType'] = 1; # 日期类别 1为阳历 2为阴历
// $data['date'] = $date;
// $data['dateType'] = 2; # 日期类别 1为阳历 2为阴历

$data['place'][] = '山西省';
$data['place'][] = '阳泉';
$data['place'][] = '郊区';

class purpleStarAstrology extends common{
	public $tianGan = array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸');
	public $diZhi = array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥');
	public $palaceAll = array('命宫','兄弟宫','夫妻宫','子女宫','财帛宫','疾厄宫','迁移宫','奴仆宫','官禄宫','田宅宫','福德宫','父母宫');

	// 中国 以都以东经120°位基准
	public $east_longitude_benchmark = 120;
	public $params = array();
	public $numToChinese = array('零','一','二','三','四','五','六','七','八','九','十','十一','十二','十三','十四','十五','十六','十七','十八','十九','二十','二十一','二十二','二十三','二十四','二十五','二十六','二十七','二十八','二十九','三十','三十一');
	public $chineseToNum = array();
	public $solarDate = '';
	public $solarDateTime = '';
	public $dateTimeData = array('solar'=>array(), 'lunar'=>array());
	public $longitude = 120; # 默认经度
	public $dateTimeframe = array();

    public function __construct($data){
        $parentData = parent::__construct();
        $this->chineseToNum = array_flip($this->numToChinese);

        # 以下时间是夏令时 当时时间被拨快了一小时 需要注意 
        $this->dateTimeframe[] = array('start'=>'1986-04-13 02:00:00', 'end'=>'1986-09-14 02:00:00');
        $this->dateTimeframe[] = array('start'=>'1987-04-12 02:00:00', 'end'=>'1987-09-13 02:00:00');
        $this->dateTimeframe[] = array('start'=>'1988-04-10 02:00:00', 'end'=>'1988-09-11 02:00:00');
        $this->dateTimeframe[] = array('start'=>'1989-04-09 02:00:00', 'end'=>'1989-09-10 02:00:00');
        $this->dateTimeframe[] = array('start'=>'1990-04-08 02:00:00', 'end'=>'1990-09-09 02:00:00');
        $this->dateTimeframe[] = array('start'=>'1991-04-07 02:00:00', 'end'=>'1991-09-08 02:00:00');

        # 参数赋值
        $this->params = $data;

        # 计算阳历日期
    	$this->solarDate = $this->solarDateSearch();

        # 计算真太阳时
        $this->solarDateTime = $this->true_solar_time();

        # 查询阳历数据
        $this->lunar_calendar();

        # 查询每宫的天干
        $this->palace_Gan();

        # 定命身宫
        $this->set_life_body_palace();

        # 排十二人事宫
        $this->put_in_order_palace();

        # 起五行局
        $this->five_branches_bureau();

        // var_dump($this->dateTimeData);
    }

    # 计算阳历日期
    public function solarDateSearch () {
        if ($this->params['dateType'] == 2) {
			$dateArr = explode(' ', str_replace(':',' ',str_replace('日',' ',str_replace('月',' ',str_replace('年',' ',$this->params['date'])))));
			$year = $dateArr['0'];
			$month = $dateArr['1'];
			$day = $dateArr['2'];
			$yearChina = mbStrSplit($year);

			$yearNum = $this->chineseToNum[$yearChina['0']].$this->chineseToNum[$yearChina['1']].$this->chineseToNum[$yearChina['2']].$this->chineseToNum[$yearChina['3']];
			$lunar = Lunar::fromYmd((int)$yearNum, (int)$this->chineseToNum[$month], (int)$this->chineseToNum[$day]);
			// 转阳历
			$solarDate = $lunar->getSolar()->toString();
		}else{
			$solarDate = substr($this->params['date'],0,10);
		}

		return $solarDate;
    } 

	# 计算时间
	/*
		* 中国
		* 北京时间 != 真太阳时
		* 需要换算：以东经120°为基准，每减少1°减去4分钟，每增加1°增加4分钟
		*
		* 注：(以下时间范围需调慢1小时，夏令时)
		* 1986.04.13.02 ~ 1986.09.14.02
		* 1987.04.12.02 ~ 1987.09.13.02
		* 1988.04.10.02 ~ 1988.09.11.02
		* 1989.04.09.02 ~ 1989.09.10.02
		* 1990.04.08.02 ~ 1990.09.09.02
		* 1991.04.07.02 ~ 1991.09.08.02
	*/
	public function true_solar_time () {
		$cityArea = array();
		/*foreach($this->params['place'] as $key=>$val){
			$level = $key + 1;

			$where = "1 and level={$level} and district='{$val}'";
			if(empty($cityArea)){
				$where .= " and pid=1";
			}else if(isset($cityArea[$key-1]['id']) && !empty($cityArea[$key-1]['id'])){
				$where .= " and pid=".$cityArea[$key-1]['id'];
			}

			$this->mysqlLink->where($where);
			$this->mysqlLink->limit(1);
			$city = $this->mysqlLink->select('ziwei_city_district');

			$cityArea[$key] = !empty($city) ? $city[0] : array('id'=>0);
		}*/

		$mylongitude = 120;
		foreach($cityArea as $city){
			if(!empty($city['longitude'])){
				$mylongitude = (int)$city['longitude'];
			}
		}

		# 计算真太阳时
		$longitudeDiff = $this->longitude - $mylongitude;
		$paramDateTime = $this->solarDate.' '.substr($this->params['date'],-8,8);
		$timeStamp = strtotime($paramDateTime);

		# 真太阳时间戳
		$solarTimeStamp = $timeStamp + ( $longitudeDiff * 4 );

		# 判断是否是夏令时, 当时时间被拨快了1小时，所以需要调整回去
		foreach($this->dateTimeframe as $val){
			$startTime = strtotime($val['start']);
			$endTime = strtotime($val['end']);
			if ($solarTimeStamp >= $startTime && $solarTimeStamp < $endTime) {
				$solarTimeStamp -= 3600;
			}
		}

		$solarDateTime = date('Y-m-d H:i:s', $solarTimeStamp);

		return $solarDateTime;
	}

	# 阳历、阴历日期转换
	public function lunar_calendar () {
		$date = $this->solarDateTime;
	
		$dateArr = explode(' ', str_replace(':',' ',str_replace('-',' ',$date)));

		$dateTimeData['solar']['datetime'] = $dateArr['0'].'-'.$dateArr['1'].'-'.$dateArr['2'].' '.$dateArr['3'].':'.$dateArr['4'].':'.$dateArr['5'];
		$dateTimeData['solar']['year'] = (int)$dateArr['0'];
		$dateTimeData['solar']['month'] = (int)$dateArr['1'];
		$dateTimeData['solar']['day'] = (int)$dateArr['2'];
		$dateTimeData['solar']['hour'] = (int)$dateArr['3'];
		$dateTimeData['solar']['minute'] = (int)$dateArr['4'];
		$dateTimeData['solar']['second'] = (int)$dateArr['5'];

		$solar = Solar::fromDate(new DateTime($dateTimeData['solar']['year'].'-'.$dateTimeData['solar']['month'].'-'.$dateTimeData['solar']['day']));
		$hourShiChen = LunarUtil::convertTime($dateArr['3'].':'.$dateArr['4']);

		$dateTimeData['lunar']['datetime'] = $solar->getLunar()->toString().' '.$dateArr['3'].':'.$dateArr['4'].':'.$dateArr['5'];
		$dateTimeData['lunar']['year'] = $solar->getLunar()->getYearInChinese();
		$dateTimeData['lunar']['yearGan'] = $solar->getLunar()->getYearGan();
		$dateTimeData['lunar']['yearZhi'] = $solar->getLunar()->getYearZhi();
		$dateTimeData['lunar']['month'] = $solar->getLunar()->getMonthInChinese();
		$dateTimeData['lunar']['monthGan'] = $solar->getLunar()->getMonthGan();
		$dateTimeData['lunar']['monthZhi'] = $solar->getLunar()->getMonthZhi();
		$dateTimeData['lunar']['day'] = $solar->getLunar()->getDayInChinese();
		$dateTimeData['lunar']['dayGan'] = $solar->getLunar()->getDayGan();
		$dateTimeData['lunar']['dayZhi'] = $solar->getLunar()->getDayZhi();
		$dateTimeData['lunar']['hour'] = (int)$dateArr['3'];
		$dateTimeData['lunar']['minute'] = (int)$dateArr['4'];
		$dateTimeData['lunar']['second'] = (int)$dateArr['5'];
		$dateTimeData['lunar']['hourShiChen'] = $hourShiChen;

		$this->dateTimeData = $dateTimeData;
	}

	# 确定每个宫位的天干
	/*
		* 甲、己 - 寅为丙
		* 乙、庚 - 寅为戊
		* 丙、辛 - 寅为庚
		* 丁、壬 - 寅为壬
		* 戊、癸 - 寅为甲
	*/
	public function palace_Gan() {
		# 组合地支数组 (以寅为首)
		$tianGanTwo = array_merge($this->tianGan, $this->tianGan, $this->tianGan);
		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		$diZhiTwo = array_splice($diZhiTwo,2);
		array_splice($diZhiTwo,-10);
		$diZhiTwo = array_merge($diZhiTwo);

		# 组合宫干
		$this->dateTimeData['palace'] = array();
		if(in_array($this->dateTimeData['lunar']['yearGan'],array('甲','己'))){
			$tianGanTwo = array_splice($tianGanTwo,2);
		}else if(in_array($this->dateTimeData['lunar']['yearGan'],array('乙','庚'))){
			$tianGanTwo = array_splice($tianGanTwo,4);
		}else if(in_array($this->dateTimeData['lunar']['yearGan'],array('丙','辛'))){
			$tianGanTwo = array_splice($tianGanTwo,6);
		}else if(in_array($this->dateTimeData['lunar']['yearGan'],array('丁','壬'))){
			$tianGanTwo = array_splice($tianGanTwo,8);
		}else if(in_array($this->dateTimeData['lunar']['yearGan'],array('戊','癸'))){
			$tianGanTwo = array_splice($tianGanTwo,10);
		}
		$tianGanTwo = array_merge($tianGanTwo);

		foreach($diZhiTwo as $key=>$val){
			$this->dateTimeData['palace'][$val]['zhi'] = $val;
			$this->dateTimeData['palace'][$val]['gan'] = $tianGanTwo[$key];
		}
	}

	# 定命身宫 (不算 生日)
	/*
	* 自寅宫起 顺时针到生月 (每月过一宫) 在以此为起点，沿十二宫方向
	* 命宫: 逆时针 数到生时 (每时过一宫)
	* 身宫: 顺时针 数到生时 (每时过一宫)
	*/
	public function set_life_body_palace() {
		# 地支前加一位
		$diZhiCur = $this->diZhi;
		array_unshift($diZhiCur,'');

		$lunarMonth = $this->dateTimeData['lunar']['month'];
		if($lunarMonth == '正'){
			$lunarMonth = '一';
		}
		if($lunarMonth == '冬'){
			$lunarMonth = '十一';
		}
		if($lunarMonth == '腊'){
			$lunarMonth = '十二';
		}
		$monthNum = array_search($lunarMonth, $this->numToChinese);
		$shiChenNum = array_search($this->dateTimeData['lunar']['hourShiChen'], $diZhiCur);
		$lifePalace = $monthNum - ($shiChenNum - 1); # 命宫
		$bodyPalace = $monthNum + ($shiChenNum - 1); # 身宫
	
		if ($lifePalace <= 0) {
			$lifePalace = 12 + $lifePalace;
		}

		$palace = array_keys($this->dateTimeData['palace']);
		$palace = array_merge($palace, $palace);
		foreach($palace as $key=>$val){
			if (($key + 1) == $lifePalace) {
				$this->dateTimeData['palace'][$val]['palaceName'] = '命宫';
			}
			if (($key + 1) == $bodyPalace) {
				$this->dateTimeData['palace'][$val]['palaceNameShen'] = '身宫';
			}
		}
	}

	# 排十二人事宫 (逆时针)
	public function put_in_order_palace(){
		# 查询命宫地支
		$lifePalace = '';
		foreach($this->dateTimeData['palace'] as $key=>$val){
			if(isset($val['palaceName']) && $val['palaceName'] == '命宫'){
				$lifePalace = $val['zhi'];
				break;
			}
		}

		# 逆时针
		$diZhiDao = array_reverse($this->diZhi);
		$diZhiDaoTwo = array_merge($diZhiDao, $diZhiDao);
		$lifeZhiNum = array_search($lifePalace, $diZhiDaoTwo);

		$diZhiAll = array_splice($diZhiDaoTwo, $lifeZhiNum);

		$num = 0;
		foreach($diZhiAll as $key=>$val){
			if($num >= 12){
				break;
			}
			$num ++;

			$this->dateTimeData['palace'][$val]['palaceName'] = $this->palaceAll[$key];
		}
	}

	# 起五行局
	public function five_branches_bureau() {
        var_dump($this->dateTimeData);

	}



	# 紫微星宫图 展示一个人的十二宫等数据
	public function ziwei_astrogram () {
		$shenPalace = array();
		foreach($this->diZhi as $dzKey=>$dzVal){
			$shenPalace[$dzVal]['name'] = '';
			$shenPalace[$dzVal]['class'] = '';
			if(isset($this->dateTimeData['palace'][$dzVal]['palaceNameShen'])){
				$shenPalace[$dzVal]['name'] = $this->dateTimeData['palace'][$dzVal]['palaceNameShen'];
				$shenPalace[$dzVal]['class'] = 'palace-shen';
			}
		}

		$style = 
		'<style>
			.main{width: 70%;height: 600px;margin: 0 auto;}
			.line{width: 100%;height:25%;}
			.empress-top{width: 48%;height:100%;border:solid 2px black;border-top:solid 1px black;border-bottom:solid 1px white;float:left}
			.empress-footer{width: 48%;height:100%;border:solid 2px black;border-bottom:solid 1px black;border-top:solid 1px white;float:left}
			.palace{width: 24%;height:100%;border:solid 1px black;float:left;position: relative;}
			.palace-ganzhi{display: inline-block;float: right;position: absolute;bottom:5px;right: 5px;font-size: 14px;}
			.palace-gan{float:left;}
			.palace-zhi{float:left;}
			.palace-name{position: absolute;bottom: 5px;left: 35%;background: #8F6F47;padding: 2px 5px;color: white;font-size: 13px;}
			.palace-shen{position: absolute;bottom: 30px;right: 5px;background: red;padding: 2px 5px;color: white;font-size: 12px}
		</style>';

		$html = 
		'<div class="main">
			<div class="line">
				<div class="palace">

					<div class="palace-name">'.$this->dateTimeData['palace']['巳']['palaceName'].'</div>
					<div class="'.$shenPalace['巳']['class'].'">'.$shenPalace['巳']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['巳']['gan'].'</div>
						<div class="palace-zhi">巳</div>
					</div>
				</div>
				<div class="palace">

					<div class="palace-name">'.$this->dateTimeData['palace']['午']['palaceName'].'</div>
					<div class="'.$shenPalace['午']['class'].'">'.$shenPalace['午']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['午']['gan'].'</div>
						<div class="palace-zhi">午</div>
					</div>
				</div>
				<div class="palace">

					<div class="palace-name">'.$this->dateTimeData['palace']['未']['palaceName'].'</div>
					<div class="'.$shenPalace['未']['class'].'">'.$shenPalace['未']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['未']['gan'].'</div>
						<div class="palace-zhi">未</div>
					</div>
				</div>
				<div class="palace">

					<div class="palace-name">'.$this->dateTimeData['palace']['申']['palaceName'].'</div>
					<div class="'.$shenPalace['申']['class'].'">'.$shenPalace['申']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['申']['gan'].'</div>
						<div class="palace-zhi">申</div>
					</div>
				</div>
			</div>
			<div class="line">
				<div class="palace">

					<div class="palace-name">'.$this->dateTimeData['palace']['辰']['palaceName'].'</div>
					<div class="'.$shenPalace['辰']['class'].'">'.$shenPalace['辰']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['辰']['gan'].'</div>
						<div class="palace-zhi">辰</div>
					</div>
				</div>
				<div class="empress-top"></div>
				<div class="palace">

					<div class="palace-name">'.$this->dateTimeData['palace']['酉']['palaceName'].'</div>
					<div class="'.$shenPalace['酉']['class'].'">'.$shenPalace['酉']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['酉']['gan'].'</div>
						<div class="palace-zhi">酉</div>
					</div>
				</div>
			</div>
			<div class="line">
				<div class="palace">
				
					<div class="palace-name">'.$this->dateTimeData['palace']['卯']['palaceName'].'</div>
					<div class="'.$shenPalace['卯']['class'].'">'.$shenPalace['卯']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['卯']['gan'].'</div>
						<div class="palace-zhi">卯</div>
					</div>
				</div>
				<div class="empress-footer"></div>
				<div class="palace">
				
					<div class="palace-name">'.$this->dateTimeData['palace']['戌']['palaceName'].'</div>
					<div class="'.$shenPalace['戌']['class'].'">'.$shenPalace['戌']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['戌']['gan'].'</div>
						<div class="palace-zhi">戌</div>
					</div>
				</div>
			</div>
			<div class="line">
				<div class="palace">
				
					<div class="palace-name">'.$this->dateTimeData['palace']['寅']['palaceName'].'</div>
					<div class="'.$shenPalace['寅']['class'].'">'.$shenPalace['寅']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['寅']['gan'].'</div>
						<div class="palace-zhi">寅</div>
					</div>
				</div>
				<div class="palace">
				
					<div class="palace-name">'.$this->dateTimeData['palace']['丑']['palaceName'].'</div>
					<div class="'.$shenPalace['丑']['class'].'">'.$shenPalace['丑']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['丑']['gan'].'</div>
						<div class="palace-zhi">丑</div>
					</div>
				</div>
				<div class="palace">
				
					<div class="palace-name">'.$this->dateTimeData['palace']['子']['palaceName'].'</div>
					<div class="'.$shenPalace['子']['class'].'">'.$shenPalace['子']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['子']['gan'].'</div>
						<div class="palace-zhi">子</div>
					</div>
				</div>
				<div class="palace">
				
					<div class="palace-name">'.$this->dateTimeData['palace']['亥']['palaceName'].'</div>
					<div class="'.$shenPalace['亥']['class'].'">'.$shenPalace['亥']['name'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['亥']['gan'].'</div>
						<div class="palace-zhi">亥</div>
					</div>
				</div>
			</div>
		</div>';

		return $style.$html;
	}
}


$purpleStar = new purpleStarAstrology($data);

# 紫微星宫图
$html = $purpleStar->ziwei_astrogram();
echo $html;