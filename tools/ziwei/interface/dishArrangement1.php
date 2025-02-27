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
// $date = '一九九四年十一月二十四 19:30:00';

$data = array();
$data['date'] = $date;
$data['dateType'] = 1; # 日期类别 1为阳历 2为阴历
$data['sex'] = 1; # 日期类别 1为男 2为女
// $data['date'] = $date;
// $data['dateType'] = 2; # 日期类别 1为阳历 2为阴历

$data['place'][] = '山西省';
$data['place'][] = '阳泉';
$data['place'][] = '郊区';

class purpleStarAstrology extends common{
	public $tianGan = array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸');
	public $diZhi = array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥');
	public $palaceAll = array('命宫','兄弟宫','夫妻宫','子女宫','财帛宫','疾厄宫','迁移宫','奴仆宫','官禄宫','田宅宫','福德宫','父母宫');
	public $fiveBranchesBureau = array('水'=>array('num'=>2, 'chinese'=>'水二局'),'木'=>array('num'=>3, 'chinese'=>'木三局'),'金'=>array('num'=>4, 'chinese'=>'金四局'),'土'=>array('num'=>5, 'chinese'=>'土五局'),'火'=>array('num'=>6, 'chinese'=>'火六局'));

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

        # 定紫微星宫位
        $this->ziwei_star_palace();

        # 排紫微系其他甲级星耀
        $this->ziwei_other_star();

        # 定天府星宫位
        $this->tianfu_star_palace();

		# 安天府星其他甲级星耀
		$this->tianfu_other_star();

		# 安六级星
		$this->six_lucky_stars();

		# 安禄存 和 天马
		$this->anlucun_and_tianma();

		# 安六煞星
		$this->six_brake_stars();

		# 定四化
		$this->set_fore_turn();

		# 丙丁级星耀
		$this->little_star_shine();

		# 丙丁级其他星耀
		$this->little_star_other();

		# 起大限
		$this->rise_deadline();

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
		$dateTimeData['lunar']['yearNum'] = $solar->getLunar()->getYear();
		$dateTimeData['lunar']['monthNum'] = $solar->getLunar()->getMonth();
		$dateTimeData['lunar']['dayNum'] = $solar->getLunar()->getDay();
		$dateTimeData['lunar']['dayGan'] = $solar->getLunar()->getDayGan();
		$dateTimeData['lunar']['dayZhi'] = $solar->getLunar()->getDayZhi();
		$dateTimeData['lunar']['hour'] = (int)$dateArr['3'];
		$dateTimeData['lunar']['minute'] = (int)$dateArr['4'];
		$dateTimeData['lunar']['second'] = (int)$dateArr['5'];
		$dateTimeData['lunar']['hourShiChen'] = $hourShiChen;
		$dateTimeData['lunar']['hourShiChenNum'] = array_search($hourShiChen,$this->diZhi) + 1;

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
				$this->dateTimeData['lifePlaceZhi'] = $val;
			}
			if (($key + 1) == $bodyPalace) {
				$this->dateTimeData['palace'][$val]['palaceNameShen'] = '身宫';
				$this->dateTimeData['bodyPlaceZhi'] = $val;
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

	# (二)
	# 起五行局 
	/*
	* 五行局由命宫所在宫位的天干和地支决定的，而非你出生哪一年的天干地支纪年
	* 方法一： 六十甲子纳音歌
	* 方法二： 掌法心算
	*/
	public function five_branches_bureau() {
		# 获取命宫的干支
		$lifePalaceGanZhiStr = '';
		foreach($this->dateTimeData['palace'] as $pkey=>$pval){
			if($pval['palaceName'] == '命宫'){
				$lifePalaceGanZhiStr = $pval['gan'] . $pval['zhi'];
				break;
			}
		}

		// 方法一：
		$sixtyArr = array('甲子、乙丑海中金','丙寅、丁卯炉中火','戊辰、己巳大林木','庚午、辛未路旁土','壬申、癸酉剑锋金','甲戌、乙亥山头火','丙子、丁亥涧下水','戊寅、己卯城头土','庚辰、辛巳白腊金','壬午、癸未扬柳木','甲申、乙酉泉中水','丙戌、丁亥屋上土','戊子、己丑霹雳火','庚寅、辛卯松柏木','壬辰、癸巳长流水','甲午、乙未沙中金','丙申、丁酉山下火','戊戌、己亥平地木','庚子、辛丑壁上土','壬寅、癸卯金箔金','甲辰、乙巳佛灯火','丙午、丁未天河水','戊申、己酉大驿土','庚戌、辛亥钗钏金','壬子、癸丑桑柘木','甲寅、乙卯大溪水','丙辰、丁巳沙中土','戊午、己未天上火','庚申、辛酉石榴木','壬戌、癸亥大海水');
		$fiveBranches = '';
		foreach($sixtyArr as $key=>$val){
			if(strpos($val, $lifePalaceGanZhiStr) !== false){
				$fiveBranches = mb_substr($val, -1);
				break;
			}
		}

		# 方法二 暂时不写了

		# 归入数组中
		$this->dateTimeData['fiveBranchesBureau'] = $this->fiveBranchesBureau[$fiveBranches];
	}

	# 定紫微星宫位
	/*
	* 农历生日数字 / 五行局数 = 商数（紫微星所在宫位）, 分以下两种情况
	* 1. 可以整除，则商数即是紫微星所坐落的位置
	* 2. 不能整除，生日数需加上一个虚借的最小整数
	*	a. 如果所借为奇数，则商数减去该数为紫微星所在宫位
	*	b. 如果所借为偶数，则商数加上该数为紫微星所在宫位
	*/
	public function ziwei_star_palace(){
		$lunarDay = $this->dateTimeData['lunar']['dayNum'];
		$fiveBranchesNum = $this->dateTimeData['fiveBranchesBureau']['num'];

		# 判断是否可以被整除
		if (($lunarDay % $fiveBranchesNum) == 0) {
			# 整除
			$quotient = $lunarDay / $fiveBranchesNum;
		}else{
			# 不可整除
			$borrowMinNum = 0;
			for($i=0; $i<$fiveBranchesNum; $i++){
				$newlunarDay = $lunarDay + $i;
				if(($newlunarDay % $fiveBranchesNum) == 0){
					$borrowMinNum = $i;
					break;
				}
			}

			# 判断虚借的数 是偶数 还是奇数
			if(($borrowMinNum % 2) == 0){
				# 偶数
				$quotient = ($lunarDay + $borrowMinNum) / $fiveBranchesNu + $borrowMinNum;
			}else{
				# 奇数
				$quotient = ($lunarDay + $borrowMinNum) / $fiveBranchesNu - $borrowMinNum;
			}
		}

		if($quotient > 12){
			$quotient = $quotient % 12;
		}
		if($quotient < 0){
			$quotient = 13 + ($quotient % 12);
		}
		$this->dateTimeData['ziweiStarPalace'] = $quotient;

		$num = 1;
		foreach($this->dateTimeData['palace'] as $key=>$val){
			if($num == $quotient){
				$this->dateTimeData['palace'][$key]['starMain'][] = '紫微星';
				break;
			}
			$num++;
		}
	}

	# 按紫微星其他甲级星耀
	/*
	* 口诀：紫微逆去宿天机，隔一太阳武曲移，天同接连空两格，廉贞居处正相宜。
	* 公式: （逆排）紫微 -> 天机 -> 口太阳 -> 武曲 -> 天同 -> 口口廉贞
	*/
	public function ziwei_other_star(){
		$ziweiStarNum = $this->dateTimeData['ziweiStarPalace'];
		$tianjiNum = ($ziweiStarNum - 1) <= 0 ? 12 + ($ziweiStarNum - 1) : $ziweiStarNum - 1;
		$taiyangNum = ($ziweiStarNum - 3) <= 0 ? 12 + ($ziweiStarNum - 3) : $ziweiStarNum - 3;
		$wuquNum = ($ziweiStarNum - 4) <= 0 ? 12 + ($ziweiStarNum - 4) : $ziweiStarNum - 4;
		$tiantongNum = ($ziweiStarNum - 5) <= 0 ? 12 + ($ziweiStarNum - 5) : $ziweiStarNum - 5;
		$lianzhenNum = ($ziweiStarNum - 8) <= 0 ? 12 + ($ziweiStarNum - 8) : $ziweiStarNum - 8;

		$num = 1;
		foreach($this->dateTimeData['palace'] as $key=>$val){
			if($num == $tianjiNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '天机星';
			}
			if($num == $taiyangNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '太阳星';
			}
			if($num == $wuquNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '武曲星';
			}
			if($num == $tiantongNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '天同星';
			}
			if($num == $lianzhenNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '廉贞星';
			}
			$num++;
		}
	}

	# (三)
	# 定天府星宫位
	/*
	* 紫微和天府之间的关系
	* 丑卯、辰子、巳亥、午戌、未酉 斜相对，
	* 寅申 住同宫
	*/
	public function tianfu_star_palace(){
		$ziweiTianfuPalace = array('子'=>'辰','丑'=>'卯','寅'=>'寅','卯'=>'丑','辰'=>'子','巳'=>'亥','午'=>'戌','未'=>'酉','申'=>'申','酉'=>'未','戌'=>'午','亥'=>'巳');

		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		$diZhiTwo = array_splice($diZhiTwo,2);
		array_splice($diZhiTwo,-10);
		$diZhiTwo = array_merge($diZhiTwo);

		# 获取紫微星所属地支
		$ziweiZhi = '';
		foreach($this->dateTimeData['palace'] as $key=>$val){
			if(!empty($val['starMain']) && in_array('紫微星', $val['starMain'])){
				$ziweiZhi = $val['zhi'];
				break;
			}
		}

		$tianfuZhi = $ziweiTianfuPalace[$ziweiZhi];

		$this->dateTimeData['palace'][$tianfuZhi]['starMain'][] = '天府星';
		$this->dateTimeData['tianfuStarPalace'] = array_search($tianfuZhi, $diZhiTwo) + 1;
	}

	# 安天府星其他甲级星耀
	/*
	* 口诀：天府顺行有太阴，贪狼坐后巨门临，随来天相天梁继，七杀空三是破军。
	* 公式: （顺排）天府 -> 太阴 -> 贪狼 -> 巨门 -> 天相 -> 天梁 -> 七杀 -> 口口口破军
	*/
	public function tianfu_other_star(){
		$tianfuStarNum = $this->dateTimeData['tianfuStarPalace'];
		$taiyinNum = ($tianfuStarNum + 1) > 12 ? ($tianfuStarNum + 1) - 12 : $tianfuStarNum + 1;
		$tanlangNum = ($tianfuStarNum + 2) > 12 ? ($tianfuStarNum + 2) - 12 : $tianfuStarNum + 2;
		$jumenNum = ($tianfuStarNum + 3) > 12 ? ($tianfuStarNum + 3) - 12 : $tianfuStarNum + 3;
		$tianxiangNum = ($tianfuStarNum + 4) > 12 ? ($tianfuStarNum + 4) - 12 : $tianfuStarNum + 4;
		$tianliangNum = ($tianfuStarNum + 5) > 12 ? ($tianfuStarNum + 5) - 12 : $tianfuStarNum + 5;
		$qishaNum = ($tianfuStarNum + 6) > 12 ? ($tianfuStarNum + 6) - 12 : $tianfuStarNum + 6;
		$pojunNum = ($tianfuStarNum + 10) > 12 ? ($tianfuStarNum + 10) - 12 : $tianfuStarNum + 10;

		$num = 1;
		foreach($this->dateTimeData['palace'] as $key=>$val){
			if($num == $taiyinNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '太阴星';
			}
			if($num == $tanlangNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '贪狼星';
			}
			if($num == $jumenNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '巨门星';
			}
			if($num == $tianxiangNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '天相星';
			}
			if($num == $tianliangNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '天梁星';
			}
			if($num == $qishaNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '七杀星';
			}
			if($num == $pojunNum){
				$this->dateTimeData['palace'][$key]['starMain'][] = '破军星';
			}
			$num++;
		}
	}

	# 安六级星
	/*
	* 左辅、右弼、文曲、文昌、天魁、天钺
	* 左辅：由辰宫起正月，顺数至生月即止
	* 右弼：由戌宫起正月，逆数至生月即止
	* 文曲：由辰宫起子时，顺数至生时即止
	* 文昌：由戌宫起子时，逆数至生时即止
	*
	* 天魁、天钺（生年天干起）口诀记忆：甲戊庚牛羊，乙己鼠猴乡，丙丁猪鸡位，六辛逢虎马，壬癸兔蛇藏。
	*/
	public function six_lucky_stars(){
		# 从辰宫开始
		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		$diZhiTwo = array_splice($diZhiTwo,4);
		array_splice($diZhiTwo,-8);
		$chenDiZhiTwo = array_merge($diZhiTwo);

		# 从戌宫开始
		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		$diZhiTwo = array_splice($diZhiTwo,10);
		array_splice($diZhiTwo,-2);
		$xuDiZhiTwo = array_merge($diZhiTwo);

		# 口诀
		$kuiyueArr = array('甲'=>array('丑'=>'天魁','未'=>'天钺'),'乙'=>array('子'=>'天魁','申'=>'天钺'),'丙'=>array('亥'=>'天魁','酉'=>'天钺'),'丁'=>array('亥'=>'天魁','酉'=>'天钺'),'戊'=>array('丑'=>'天魁','未'=>'天钺'),'己'=>array('子'=>'天魁','申'=>'天钺'),'庚'=>array('丑'=>'天魁','未'=>'天钺'),'辛'=>array('寅'=>'天魁','午'=>'天钺'),'壬'=>array('卯'=>'天魁','巳'=>'天钺'),'癸'=>array('卯'=>'天魁','巳'=>'天钺'));

		# 左辅
		$zuofuStarNum = $this->dateTimeData['lunar']['monthNum'] - 1;
		$zuofuStarZhi = $chenDiZhiTwo[$zuofuStarNum];
		$this->dateTimeData['palace'][$zuofuStarZhi]['star'][] = '左辅星';
		$this->dateTimeData['zuofuStar'] = $zuofuStarZhi;
		# 右弼
		$youbiStarNum = 12 - ($this->dateTimeData['lunar']['monthNum'] - 1);
		$youbiStarZhi = $xuDiZhiTwo[$youbiStarNum];
		$this->dateTimeData['palace'][$youbiStarZhi]['star'][] = '右弼星';
		$this->dateTimeData['youbiStar'] = $youbiStarZhi;
		# 文曲
		$wenquStarNum = $this->dateTimeData['lunar']['hourShiChenNum'] - 1;
		$wenquStarZhi = $chenDiZhiTwo[$wenquStarNum];
		$this->dateTimeData['palace'][$wenquStarZhi]['star'][] = '文曲星';
		$this->dateTimeData['wenquStar'] = $wenquStarZhi;
		# 文昌
		$wenchangStarNum = 12 - ($this->dateTimeData['lunar']['hourShiChenNum'] - 1);
		$wenchangStarZhi = $xuDiZhiTwo[$wenchangStarNum];
		$this->dateTimeData['palace'][$wenchangStarZhi]['star'][] = '文昌星';
		$this->dateTimeData['wenchangStar'] = $wenchangStarZhi;

		# 天魁、天钺
		foreach($kuiyueArr[$this->dateTimeData['lunar']['yearGan']] as $zhi=>$star){
			$this->dateTimeData['palace'][$zhi]['star'][] = $star.'星';
		}
	}

	# (四)
	# 安禄存 和 天马
	/*
	* 禄存 (生年天干)：甲禄到寅宫，乙禄居卯府，丙戊禄在巳，丁己禄居午，庚禄定居申，辛禄酉上补，壬禄亥中藏，癸禄居在子。
	* 天马 (生年地支)：申子辰人马居寅，寅午戌人马居申，亥卯未人马居巳，巳酉丑人马居亥。
	*/
	public function anlucun_and_tianma(){
		$lucunArr = array('甲'=>'寅','乙'=>'卯','丙'=>'巳','丁'=>'午','戊'=>'巳','己'=>'午','庚'=>'申','辛'=>'酉','壬'=>'亥','癸'=>'子');
		$tianmaArr = array('子'=>'寅','丑'=>'亥','寅'=>'申','卯'=>'巳','辰'=>'寅','巳'=>'亥','午'=>'申','未'=>'巳','申'=>'寅','酉'=>'亥','戌'=>'申','亥'=>'巳');

		$yearGan = $this->dateTimeData['lunar']['yearGan'];
		$yearZhi = $this->dateTimeData['lunar']['yearZhi'];

		$lucunPalace = $lucunArr[$yearGan];
		$tianmaPalace = $tianmaArr[$yearZhi];

		$this->dateTimeData['palace'][$lucunPalace]['star'][] = '禄存星';
		$this->dateTimeData['palace'][$tianmaPalace]['star'][] = '天马星';

		# 禄存星、天马星所在宫位num
		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		$diZhiTwo = array_splice($diZhiTwo,2);
		array_splice($diZhiTwo,-10);
		$diZhiTwo = array_merge($diZhiTwo);

		$this->dateTimeData['lucunStarPalace'] = array_search($lucunPalace, $diZhiTwo);
		$this->dateTimeData['tianmaStarPalace'] = array_search($tianmaPalace, $diZhiTwo);
	}

	# 安六煞星
	/*
	* 擎羊、陀罗、火星、铃星、地空、地劫
	* 擎羊、陀罗：禄存星的前一宫（顺时针一格）就是擎羊，后一宫（逆时针一格）就是陀罗
	* 火星： a. 生年地支找到起点，寅午戌 -> 丑宫起、申子辰 -> 寅宫起、巳酉丑 -> 卯宫起、亥卯未 -> 酉宫起。
	*       b. 从起点宫位起子时，顺时针数到生时止。
	* 铃星： a. 生年地支找到起点，寅午戌 -> 卯宫起，申子辰、巳酉丑、亥卯未 -> 戌宫起
	*       b. 从起点宫位起子时，顺时针数到生时为止。
	* 地空： 由亥宫起子时, 逆时针数到申时止。
	* 地劫： 由亥宫起子时, 顺时针数到生时止。
	*/
	public function six_brake_stars(){
		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		$diZhiTwo = array_splice($diZhiTwo,2);
		array_splice($diZhiTwo,-10);
		$diZhiTwo = array_merge($diZhiTwo);

		# 擎羊、陀罗
		$qingyangStarPalaceNum = ($this->dateTimeData['lucunStarPalace'] + 1) > 12 ? 12 - ($this->dateTimeData['lucunStarPalace'] + 1) : $this->dateTimeData['lucunStarPalace'] + 1;
		$tuoluoStarPalaceNum = ($this->dateTimeData['lucunStarPalace'] - 1) < 0 ? 12 + ($this->dateTimeData['lucunStarPalace'] - 1) : $this->dateTimeData['lucunStarPalace'] - 1;

		$qingyangStarPalace = $diZhiTwo[$qingyangStarPalaceNum];
		$tuoluoStarPalace = $diZhiTwo[$tuoluoStarPalaceNum];

		$this->dateTimeData['palace'][$qingyangStarPalace]['star'][] = '擎羊星';
		$this->dateTimeData['palace'][$tuoluoStarPalace]['star'][] = '陀罗星';

		# 火星
		$huoxingArr = array('子'=>'寅','丑'=>'卯','寅'=>'丑','卯'=>'酉','辰'=>'寅','巳'=>'卯','午'=>'丑','未'=>'酉','申'=>'寅','酉'=>'卯','戌'=>'丑','亥'=>'酉');

		$huoxingStart = $huoxingArr[$this->dateTimeData['lunar']['yearZhi']];
		$huoxingTwo = array_merge($this->diZhi, $this->diZhi);
		$huoxingZhiArr = array_splice($huoxingTwo, array_search($huoxingStart, $huoxingTwo));
		$huoxingStarPalace = '';
		$huoNum = 1;
		foreach($huoxingZhiArr as $key=>$val){
			if($huoNum == $this->dateTimeData['lunar']['hourShiChenNum']){
				$huoxingStarPalace = $val;
			}
			$huoNum++;
		}
		$this->dateTimeData['palace'][$huoxingStarPalace]['star'][] = '火星';

		# 铃星
		$lingxingArr = array('子'=>'戌','丑'=>'戌','寅'=>'卯','卯'=>'戌','辰'=>'戌','巳'=>'戌','午'=>'卯','未'=>'戌','申'=>'戌','酉'=>'戌','戌'=>'卯','亥'=>'戌');

		$lingxingStart = $lingxingArr[$this->dateTimeData['lunar']['yearZhi']];
		$lingxingTwo = array_merge($this->diZhi, $this->diZhi);
		$lingxingZhiArr = array_splice($lingxingTwo, array_search($lingxingStart, $lingxingTwo));
		$lingxingStarPalace = '';
		$lingNum = 1;
		foreach($lingxingZhiArr as $key=>$val){
			if($lingNum == $this->dateTimeData['lunar']['hourShiChenNum']){
				$lingxingStarPalace = $val;
			}
			$lingNum++;
		}
		$this->dateTimeData['palace'][$lingxingStarPalace]['star'][] = '铃星';

		# 地空、地劫
		$diTwo = array_merge($this->diZhi, $this->diZhi);
		$dijieArr = array_splice($diTwo, array_search('亥', $diTwo));
		$dikongArr = array_reverse($dijieArr);

		$dikongStarPalace = '';
		$dijieStarPalace = '';
		$kongNum = 1;
		foreach($dikongArr as $key=>$val){
			if($kongNum == $this->dateTimeData['lunar']['hourShiChenNum']){
				$dikongStarPalace = $val;
			}
			$kongNum++;
		}
		$jieNum = 1;
		foreach($dijieArr as $key=>$val){
			if($jieNum == $this->dateTimeData['lunar']['hourShiChenNum']){
				$dijieStarPalace = $val;
			}
			$jieNum++;
		}
		$this->dateTimeData['palace'][$dijieStarPalace]['star'][] = '地劫星';
		$this->dateTimeData['palace'][$dikongStarPalace]['star'][] = '地空星';
	}

	# 定四化
	/*
	* 生年天干
	* 甲： 廉贞化禄，破军化权、武曲化科、太阳化忌。
	* 乙： 天机化禄，天梁化权、紫微化科、太阴化忌。
	* 丙： 天同化禄，天机化权、文昌化科、廉贞化忌。
	* 丁： 太阴化禄，天同化权、天机化科、巨门化忌。
	* 戊： 贪狼化禄，太阴化权、右弼化科、天机化忌。
	* 己： 武曲化禄，贪狼化权、天梁化科、文曲化忌。
	* 庚： 太阳化禄，武曲化权、太阴化科、天同化忌。
	* 辛： 巨门化禄，太阳化权、文曲化科、文昌化忌。
	* 壬： 天梁化禄，紫微化权、左辅化科、武曲化忌。
	* 癸： 破军化禄，巨门化权、太阳化科、贪狼化忌。
	*/
	public function set_fore_turn(){
		$foreTurn = array(
				'甲' => array('廉贞'=>'禄','破军'=>'权','武曲'=>'科','太阳'=>'忌'),
				'乙' => array('天机'=>'禄','天梁'=>'权','紫微'=>'科','太阴'=>'忌'),
				'丙' => array('天同'=>'禄','天机'=>'权','文昌'=>'科','廉贞'=>'忌'),
				'丁' => array('太阴'=>'禄','天同'=>'权','天机'=>'科','巨门'=>'忌'),
				'戊' => array('贪狼'=>'禄','太阴'=>'权','右弼'=>'科','天机'=>'忌'),
				'己' => array('武曲'=>'禄','贪狼'=>'权','天梁'=>'科','文曲'=>'忌'),
				'庚' => array('太阳'=>'禄','武曲'=>'权','太阴'=>'科','天同'=>'忌'),
				'辛' => array('巨门'=>'禄','太阳'=>'权','文曲'=>'科','文昌'=>'忌'),
				'壬' => array('天梁'=>'禄','紫微'=>'权','左辅'=>'科','武曲'=>'忌'),
				'癸' => array('破军'=>'禄','巨门'=>'权','太阳'=>'科','贪狼'=>'忌'),
		);

		$yearGan = $this->dateTimeData['lunar']['yearGan'];

		foreach($this->dateTimeData['palace'] as $key=>$val){
			foreach($foreTurn[$yearGan] as $forestar=>$turn){
				if(!empty($val['starMain']) && in_array($forestar.'星', $val['starMain'])){
					$this->dateTimeData['palace'][$key]['foreTurn'][$forestar.'星'] = $turn;
				}
			}
		}
	}

	# (五)
	# 丙丁级星耀
	/*
	* 红鸾、天喜、天姚、咸池、天刑
	* 红鸾： 由卯宫起子年，逆数至生年即止。(生年地支)
	* 天喜： 由酉宫起子年，逆数至生年即止。(生年地支)
	* 天姚： 由丑宫起正月，顺数至生月即止。(出生的月)
	* 咸池： 寅午戌在卯宫、申子辰在酉宫、巳酉丑在午宫、亥卯未在子宫。(生年地支)
	* 天刑： 由酉宫起正月，顺数至生月即止。(出生的月)
	*/
	public function little_star_shine(){
		$yearZhi = $this->dateTimeData['lunar']['yearZhi'];
		$monthNum = $this->dateTimeData['lunar']['monthNum'];
		# 咸池
		$xianchiArr = array('子'=>'酉','丑'=>'午','寅'=>'卯','卯'=>'子','辰'=>'酉','巳'=>'午','午'=>'卯','未'=>'子','申'=>'酉','酉'=>'午','戌'=>'卯','亥'=>'子');

		$yearZhiNum = array_search($yearZhi, $this->diZhi);

		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);

		# 由卯宫开始
		$maodizhi = $diZhiTwo;
		$maoStart = array_splice($maodizhi,3);
		array_splice($maoStart,-8);
		# 由酉宫开始
		$youdizhi = $diZhiTwo;
		$youStart = array_splice($youdizhi,9);
		array_splice($youStart,-2);
		# 由丑宫开始
		$choudizhi = $diZhiTwo;
		$chouStart = array_splice($choudizhi,1);
		array_splice($chouStart,-10);

		# 逆序
		$maoRe = array_reverse($maoStart);
		$youRe = array_reverse($youStart);

		# 红鸾
		$hongluanStarPalace = $maoRe[$yearZhiNum];
		# 天喜
		$tianxiStarPalace = $youRe[$yearZhiNum];
		# 天姚
		$tianyaoStarPalace = $chouStart[$monthNum-1];
		# 天刑
		$tianxingStarPalace = $youStart[$monthNum-1];
		# 咸池
		$xianchiStarPalace = $xianchiArr[$yearZhi];

		# 整合
		$this->dateTimeData['palace'][$hongluanStarPalace]['starMin'][] = '红鸾星';
		$this->dateTimeData['palace'][$tianxiStarPalace]['starMin'][] = '天喜星';
		$this->dateTimeData['palace'][$tianyaoStarPalace]['starMin'][] = '天姚星';
		$this->dateTimeData['palace'][$tianxingStarPalace]['starMin'][] = '天刑星';
		$this->dateTimeData['palace'][$xianchiStarPalace]['starMin'][] = '咸池星';
	}

	# 丙丁级其他星耀
	/*
	* 龙池、凤阁、三台、八座、恩光、天贵、台辅、封诰、天哭、天虚、孤城、寡宿、丧门、白虎、官符。
	* 龙池： 由辰宫起子年，顺数至生年即止（按照生年地支）
	* 凤阁： 由戌宫起子年，逆数至生年即止（按照生年地支）
	* 三台： 从左辅上起初一，顺数至生日即止（按照生日）
	* 八座： 从右弼上起初一，逆数至生日即止（按照生日）
 	* 恩光： 从文昌上起初一，顺数至生日再后退一宫（按照生日）
 	* 天贵： 从文曲上起初一，逆数至生日再后退一宫（按照生日）
 	* 台辅： 由午宫起子时，顺数至生时即止（按照生时）
 	* 封诰： 由寅宫起子时，顺数至生时即止（按照生时）
 	* 天哭： 由午宫起子年，逆数至生年即止（按照生年地支）
 	* 天虚： 由午宫起子年，顺数至生年即止（按照生年地支）
 	* 孤城： 亥子丑年在寅、寅卯辰年在巳、巳午未年在申、申酉戌年在亥（按照生年地支）
 	* 寡宿： 亥子丑年在戌、寅卯辰年在丑、巳午未年在辰、申酉戌年在未（按照生年地支）
 	* 丧门： 由寅宫起子年，顺数至生年即止（按照生年地支）
 	* 白虎： 由申宫起子年，顺数至生年即止（按照生年地支）
 	* 官符： 由辰宫起子年，顺数至生年即止（按照生年地支）
	*/
	public function little_star_other(){
		$yearZhi = $this->dateTimeData['lunar']['yearZhi'];
		$yearZhiNum = array_search($yearZhi, $this->diZhi);
		$dayNum = $this->dateTimeData['lunar']['dayNum'];
		$hourShiChenNum = $this->dateTimeData['lunar']['hourShiChenNum'];

		# 孤城
		$guchengArr = array('子'=>'寅','丑'=>'寅','寅'=>'巳','卯'=>'巳','辰'=>'巳','巳'=>'申','午'=>'申','未'=>'申','申'=>'亥','酉'=>'亥','戌'=>'亥','亥'=>'寅');
		# 寡宿
		$guasuArr = array('子'=>'戌','丑'=>'戌','寅'=>'丑','卯'=>'丑','辰'=>'丑','巳'=>'辰','午'=>'辰','未'=>'辰','申'=>'未','酉'=>'未','戌'=>'未','亥'=>'戌');

		# 
		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);

		# 由辰宫开始
		$chendizhi = $diZhiTwo;
		$chenStart = array_splice($chendizhi, 4);
		array_splice($chenStart,-7);
		# 由戌宫开始
		$xudizhi = $diZhiTwo;
		$xuStart = array_splice($xudizhi,10);
		array_splice($xuStart,-1);
		# 由午宫开始
		$wudizhi = $diZhiTwo;
		$wuStart = array_splice($wudizhi,6);
		array_splice($wuStart,-5);
		# 由寅宫开始
		$yingdizhi = $diZhiTwo;
		$yingStart = array_splice($yingdizhi,2);
		array_splice($yingStart,-9);
		# 由申宫开始
		$shendizhi = $diZhiTwo;
		$shenStart = array_splice($shendizhi,8);
		array_splice($shenStart,-3);

		# 左辅
		$zuofudizhi = $diZhiTwo;
		$zuofuNum = array_search($this->dateTimeData['zuofuStar'], $zuofudizhi);
		$zuofuStart = array_splice($zuofudizhi, $zuofuNum);
		array_splice($zuofuStart, $zuofuNum - 11);

		# 右弼
		$youbidizhi = $diZhiTwo;
		$youbiNum = array_search($this->dateTimeData['youbiStar'], $youbidizhi);
		$youbiStart = array_splice($youbidizhi, $youbiNum);
		array_splice($youbiStart, $youbiNum - 11);

		# 文昌
		$wenchangdizhi = $diZhiTwo;
		$wenchangNum = array_search($this->dateTimeData['wenchangStar'], $wenchangdizhi);
		$wenchangStart = array_splice($wenchangdizhi, $wenchangNum);
		array_splice($wenchangStart, $wenchangNum - 11);

		# 文曲
		$wenqudizhi = $diZhiTwo;
		$wenquNum = array_search($this->dateTimeData['wenquStar'], $wenqudizhi);
		$wenquStart = array_splice($wenqudizhi, $wenquNum);
		array_splice($wenquStart, $wenquNum - 11);

		# 龙池
		$longchiStarPalace = $chenStart[$yearZhiNum];
		# 凤阁
		$xuStartRe = array_reverse($xuStart);
		$fenggeStarPalace = $xuStartRe[$yearZhiNum];
		# 三台
		array_pop($zuofuStart);
		$zuofuAll = array_merge($zuofuStart,$zuofuStart,$zuofuStart);
		$santaiStarPalace = $zuofuAll[$dayNum];
		# 八座
		$youbiStartRe = array_reverse($youbiStart);
		array_pop($youbiStartRe);
		$youbiAll = array_merge($youbiStartRe,$youbiStartRe,$youbiStartRe);
		$bazuoStarPalace = $youbiAll[$dayNum];
		# 恩光
		array_pop($wenchangStart);
		$wenchangAll = array_merge($wenchangStart,$wenchangStart,$wenchangStart);
		$enguangStarPalace = $wenchangAll[$dayNum - 1];
		# 天贵
		$wenquStartRe = array_reverse($wenquStart);
		array_pop($wenquStartRe);
		$wenquAll = array_merge($wenquStartRe,$wenquStartRe,$wenquStartRe);
		$tianguiStarPalace = $wenquAll[$dayNum - 1];
		# 台辅
		$taifuStarPalace = $wuStart[$hourShiChenNum - 1];
		# 封诰
		$fenggaoStarPalace = $yingStart[$hourShiChenNum - 1];
		# 天哭
		$wuStartRe = array_reverse($wuStart);
		$tiankuStarPalace = $wuStartRe[$yearZhiNum];
		# 天虚
		$tianxuStarPalace = $wuStart[$yearZhiNum];
		# 孤城
		$guchengStarPalace = $guchengArr[$yearZhi];
		# 寡宿
		$guasuStarPalace = $guasuArr[$yearZhi];
		# 丧门
		$sangmenStarPalace = $yingStart[$yearZhiNum];
		# 白虎
		$baihuStarPalace = $shenStart[$yearZhiNum];
		# 官符
		$guanfuStarPalace = $chenStart[$yearZhiNum];

		# 整合
		$this->dateTimeData['palace'][$longchiStarPalace]['starMin'][] = '龙池星';
		$this->dateTimeData['palace'][$fenggeStarPalace]['starMin'][] = '凤阁星';
		$this->dateTimeData['palace'][$santaiStarPalace]['starMin'][] = '三台星';
		$this->dateTimeData['palace'][$bazuoStarPalace]['starMin'][] = '八座星';
		$this->dateTimeData['palace'][$enguangStarPalace]['starMin'][] = '恩光星';
		$this->dateTimeData['palace'][$tianguiStarPalace]['starMin'][] = '天贵星';
		$this->dateTimeData['palace'][$taifuStarPalace]['starMin'][] = '台辅星';
		$this->dateTimeData['palace'][$fenggaoStarPalace]['starMin'][] = '封诰星';
		$this->dateTimeData['palace'][$tiankuStarPalace]['starMin'][] = '天哭星';
		$this->dateTimeData['palace'][$tianxuStarPalace]['starMin'][] = '天虚星';
		$this->dateTimeData['palace'][$guchengStarPalace]['starMin'][] = '孤城星';
		$this->dateTimeData['palace'][$guasuStarPalace]['starMin'][] = '寡宿星';
		$this->dateTimeData['palace'][$sangmenStarPalace]['starMin'][] = '丧门星';
		$this->dateTimeData['palace'][$baihuStarPalace]['starMin'][] = '白虎星';
		$this->dateTimeData['palace'][$guanfuStarPalace]['starMin'][] = '官符星';
	} 

	# 起大限
	/*
	* 《骨髓赋》 命好身好限好，到老荣昌，命衰身衰限衰，终身乞丐。
	* 生年天干：阳年 -- 偶数年份（甲、丙、戊、庚、壬），阴年 -- 奇数年份（乙、丁、己、辛、癸）
	* 阳年出生的：男 -> 阳男， 女 -> 阳女； 阴年出生的：男 -> 阴男， 女 -> 阴女
	* 口诀：
	*  阳男阴女顺方走、阳女阴男逆方行、五行局数起首限、每隔十年过一宫。
	* 
	*/
	public function rise_deadline(){
		$yearGan = $this->dateTimeData['lunar']['yearGan'];
		# sort 1为顺时针排序 2为逆时针排序
		$sort = 1;
		# 阳女 或者 阴男 为逆时针
		if((in_array($yearGan,array('甲','丙','戊','庚','壬')) && $this->params['sex'] == 2) || (in_array($yearGan,array('乙','丁','己','辛','癸')) && $this->params['sex'] == 1)){
			$sort = 2;
		}

		# 计算所有年限
		$yearAllNum = array();
		for($i=0;$i<12;$i++){
			$yearAllNum[$i]['start'] = $this->dateTimeData['fiveBranchesBureau']['num'] + 10 * $i;
			$yearAllNum[$i]['end'] = $this->dateTimeData['fiveBranchesBureau']['num'] + 10 * ($i + 1) - 1;
		}

		$diZhiTwo = array_merge($this->diZhi, $this->diZhi);
		# 由命宫开始
		$mingdizhi = $diZhiTwo;
		$mingdizhiNum = array_search($this->dateTimeData['lifePlaceZhi'], $mingdizhi);
		$mingStart = array_splice($mingdizhi, $mingdizhiNum);
		$mingAll = $mingStart;
		$mingEnd = array_search($this->dateTimeData['lifePlaceZhi'], array_reverse($mingStart));
		array_splice($mingAll,-$mingEnd);

		if($sort == 2){
			$mingAll = array_reverse($mingAll);
		}
		$palaceYearNum = array();
		foreach($mingAll as $key=>$val){
			if(empty($palaceYearNum[$val])){
				$palaceYearNum[$val] = $yearAllNum[$key];
				$this->dateTimeData['palace'][$val]['year'] = $yearAllNum[$key];
			}
		}
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

		# 星星排列
		$palaceStar = array();
		foreach($this->dateTimeData['palace'] as $pkey=>$pval){
			$palaceStarMain[$pkey] = '';
			$palaceStarOther[$pkey] = '';

			$foreturn = array();
			if(!empty($pval['foreTurn'])){
				foreach($pval['foreTurn'] as $foreKey=>$foreVal){
					$foreturn[$foreKey] = '<div class="fore-turn">'.$foreVal.'</div>';
				}
			}
			$starMain = array();
			if(!empty($pval['starMain'])){
				foreach($pval['starMain'] as $starKey=>$starVal){
					if(in_array($starVal, array_keys($foreturn))){
						$starMain[] = '<span class="star-main">'.$starVal.'</span>'.' '.$foreturn[$starVal];
					}else{
						$starMain[] = '<span class="star-main">'.$starVal.'</span>';
					}
				}
			}
			if(!empty($pval['star'])){
				foreach($pval['star'] as $starKey=>$starVal){
					$starMain[] = '<span class="star-second">'.$starVal.'</span>';
				}
			}

			$starOther = array();
			if(!empty($pval['starMin'])){
				foreach($pval['starMin'] as $starKey=>$starVal){
					$starOther[] = '<span class="star-min">'.$starVal.'</span>';
				}
			}
			if(!empty($starMain)){
				$palaceStarMain[$pkey] = '<div class="palace-star-main">'.implode('</div><div class="palace-star-main">',$starMain).'</div>';
			}
			if(!empty($starOther)){
				$palaceStarOther[$pkey] = '<div class="palace-star-other">'.implode('</div><div class="palace-star-other">',$starOther).'</div>';
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
			.empress-footer{position: relative;}
			.empress-footer-ju{position: absolute;bottom: 4px;left: 45%;background: red;padding: 2px 6px;color: white;font-size: 14px;}
			
			.star-main-div{width: auto;font-size: 12px;position: absolute;padding: 3px 6px;left: 0px;}
			.star-other-div{width: auto;font-size: 12px;position: absolute;padding: 3px 6px;right: 0px;}
			.palace-star-main,.palace-star-other{padding: 2px 0px;}

			.star-main{color:red}
			.star-second{color:blue}
			.star-min{color: #5389BA;}
			.fore-turn{display: inline-block;background: red;padding: 2px 4px;color: white;margin-left: 2px;}
			.palace-year{position: absolute;bottom: 6px;font-size: 12px;left: 3px;padding: 2px 3px;}
		</style>';

		$html = 
		'<div class="main">
			<div class="line">
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['巳'].'</div>
					<div class="star-other-div">'.$palaceStarOther['巳'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['巳']['palaceName'].'</div>
					<div class="'.$shenPalace['巳']['class'].'">'.$shenPalace['巳']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['巳']['year']['start'].'~'.$this->dateTimeData['palace']['巳']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['巳']['gan'].'</div>
						<div class="palace-zhi">巳</div>
					</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['午'].'</div>
					<div class="star-other-div">'.$palaceStarOther['午'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['午']['palaceName'].'</div>
					<div class="'.$shenPalace['午']['class'].'">'.$shenPalace['午']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['午']['year']['start'].'~'.$this->dateTimeData['palace']['午']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['午']['gan'].'</div>
						<div class="palace-zhi">午</div>
					</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['未'].'</div>
					<div class="star-other-div">'.$palaceStarOther['未'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['未']['palaceName'].'</div>
					<div class="'.$shenPalace['未']['class'].'">'.$shenPalace['未']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['未']['year']['start'].'~'.$this->dateTimeData['palace']['未']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['未']['gan'].'</div>
						<div class="palace-zhi">未</div>
					</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['申'].'</div>
					<div class="star-other-div">'.$palaceStarOther['申'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['申']['palaceName'].'</div>
					<div class="'.$shenPalace['申']['class'].'">'.$shenPalace['申']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['申']['year']['start'].'~'.$this->dateTimeData['palace']['申']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['申']['gan'].'</div>
						<div class="palace-zhi">申</div>
					</div>
				</div>
			</div>
			<div class="line">
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['辰'].'</div>
					<div class="star-other-div">'.$palaceStarOther['辰'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['辰']['palaceName'].'</div>
					<div class="'.$shenPalace['辰']['class'].'">'.$shenPalace['辰']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['辰']['year']['start'].'~'.$this->dateTimeData['palace']['辰']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['辰']['gan'].'</div>
						<div class="palace-zhi">辰</div>
					</div>
				</div>
				<div class="empress-top"></div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['酉'].'</div>
					<div class="star-other-div">'.$palaceStarOther['酉'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['酉']['palaceName'].'</div>
					<div class="'.$shenPalace['酉']['class'].'">'.$shenPalace['酉']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['酉']['year']['start'].'~'.$this->dateTimeData['palace']['酉']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['酉']['gan'].'</div>
						<div class="palace-zhi">酉</div>
					</div>
				</div>
			</div>
			<div class="line">
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['卯'].'</div>
					<div class="star-other-div">'.$palaceStarOther['卯'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['卯']['palaceName'].'</div>
					<div class="'.$shenPalace['卯']['class'].'">'.$shenPalace['卯']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['卯']['year']['start'].'~'.$this->dateTimeData['palace']['卯']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['卯']['gan'].'</div>
						<div class="palace-zhi">卯</div>
					</div>
				</div>
				<div class="empress-footer"> 
					<div class="empress-footer-ju">'.$this->dateTimeData['fiveBranchesBureau']['chinese'].'</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['戌'].'</div>
					<div class="star-other-div">'.$palaceStarOther['戌'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['戌']['palaceName'].'</div>
					<div class="'.$shenPalace['戌']['class'].'">'.$shenPalace['戌']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['戌']['year']['start'].'~'.$this->dateTimeData['palace']['戌']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['戌']['gan'].'</div>
						<div class="palace-zhi">戌</div>
					</div>
				</div>
			</div>
			<div class="line">
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['寅'].'</div>
					<div class="star-other-div">'.$palaceStarOther['寅'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['寅']['palaceName'].'</div>
					<div class="'.$shenPalace['寅']['class'].'">'.$shenPalace['寅']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['寅']['year']['start'].'~'.$this->dateTimeData['palace']['寅']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['寅']['gan'].'</div>
						<div class="palace-zhi">寅</div>
					</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['丑'].'</div>
					<div class="star-other-div">'.$palaceStarOther['丑'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['丑']['palaceName'].'</div>
					<div class="'.$shenPalace['丑']['class'].'">'.$shenPalace['丑']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['丑']['year']['start'].'~'.$this->dateTimeData['palace']['丑']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['丑']['gan'].'</div>
						<div class="palace-zhi">丑</div>
					</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['子'].'</div>
					<div class="star-other-div">'.$palaceStarOther['子'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['子']['palaceName'].'</div>
					<div class="'.$shenPalace['子']['class'].'">'.$shenPalace['子']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['子']['year']['start'].'~'.$this->dateTimeData['palace']['子']['year']['end'].'</div>
					<div class="palace-ganzhi">
						<div class="palace-gan">'.$this->dateTimeData['palace']['子']['gan'].'</div>
						<div class="palace-zhi">子</div>
					</div>
				</div>
				<div class="palace">

					<div class="star-main-div">'.$palaceStarMain['亥'].'</div>
					<div class="star-other-div">'.$palaceStarOther['亥'].'</div>
					<div class="palace-name">'.$this->dateTimeData['palace']['亥']['palaceName'].'</div>
					<div class="'.$shenPalace['亥']['class'].'">'.$shenPalace['亥']['name'].'</div>
					<div class="palace-year">'.$this->dateTimeData['palace']['亥']['year']['start'].'~'.$this->dateTimeData['palace']['亥']['year']['end'].'</div>
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