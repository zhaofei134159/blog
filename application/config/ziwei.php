<?php
$config = array();

# 以下时间是夏令时 当时时间被拨快了一小时 需要注意 
$config['dateTimeframe'][] = array('start'=>'1986-04-13 02:00:00', 'end'=>'1986-09-14 02:00:00');
$config['dateTimeframe'][] = array('start'=>'1986-04-13 02:00:00', 'end'=>'1986-09-14 02:00:00');
$config['dateTimeframe'][] = array('start'=>'1987-04-12 02:00:00', 'end'=>'1987-09-13 02:00:00');
$config['dateTimeframe'][] = array('start'=>'1988-04-10 02:00:00', 'end'=>'1988-09-11 02:00:00');
$config['dateTimeframe'][] = array('start'=>'1989-04-09 02:00:00', 'end'=>'1989-09-10 02:00:00');
$config['dateTimeframe'][] = array('start'=>'1990-04-08 02:00:00', 'end'=>'1990-09-09 02:00:00');
$config['dateTimeframe'][] = array('start'=>'1991-04-07 02:00:00', 'end'=>'1991-09-08 02:00:00');

# 紫微斗数所有得星星
$config['starAll']['jia_main_star'] = array('紫微','天机','太阳','武曲','天同','廉贞','天府','太阴','贪狼','巨门','天相','天梁','七杀','破军');
$config['starAll']['jia_fu_star'] = array('天魁','天钺','左辅','右弼','文昌','文曲','禄存','天马','擎羊','陀罗','火星','铃星','地空','地劫');
$config['starAll']['yi_fu_star'] = array('天刑','天姚','解神','阴煞','天巫','天月','三台','八座','恩光','天贵','台辅','封诰','天福','天官','天厨','天空','天哭','天虚','龙池','凤阁','红鸾','天喜','孤城','寡宿','蜚廉','破碎','华盖','咸池','天德','月德','天才','天寿');
$config['starAll']['bing_fu_star'] = array('天伤','天使','截空','旬空','博士','力士','青龙','小耗','将军','奏书','飞廉','喜神','病符','大耗','伏兵','官府');
$config['starAll']['cs_star'] = array('长生','沐浴','冠带','临官','帝旺','衰','病','死','墓','绝','胎','养');
$config['starAll']['ding_fu_star'] = array('岁建','龙德',/*'将军',*/'攀鞍','岁驿');
$config['starAll']['wu_fu_star'] = array('晦气','丧门','贯索','官符',/*'小耗','大耗',*/'白虎','吊客',/*'病符',*/'息神','劫煞','灾煞','天煞','指背','月煞','亡神');


# 紫微斗数 天干、地支、宫位
$config['tianGan'] = array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸');
$config['diZhi'] = array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥');
$config['palaceLs'] = array('命宫','兄弟宫','夫妻宫','子女宫','财帛宫','疾厄宫','迁移宫','奴仆宫','官禄宫','田宅宫','福德宫','父母宫');

# 五行局
$config['fiveBranchesBureau']['水'] = array('num'=>2, 'chinese'=>'水二局');
$config['fiveBranchesBureau']['木'] = array('num'=>3, 'chinese'=>'木三局');
$config['fiveBranchesBureau']['金'] = array('num'=>4, 'chinese'=>'金四局');
$config['fiveBranchesBureau']['土'] = array('num'=>5, 'chinese'=>'土五局');
$config['fiveBranchesBureau']['火'] = array('num'=>6, 'chinese'=>'火六局');

# 使用五鼠遁时诀 计算时干
$config['hourGanRule']['子'] = array('甲'=>'甲', '乙'=>'丙', '丙'=>'戊', '丁'=>'庚', '戊'=>'壬', '己'=>'甲', '庚'=>'丙', '辛'=>'戊', '壬'=>'庚', '癸'=>'壬');
$config['hourGanRule']['丑'] = array('甲'=>'乙', '乙'=>'丁', '丙'=>'己', '丁'=>'辛', '戊'=>'癸', '己'=>'乙', '庚'=>'丁', '辛'=>'己', '壬'=>'辛', '癸'=>'癸');
$config['hourGanRule']['寅'] = array('甲'=>'丙', '乙'=>'戊', '丙'=>'庚', '丁'=>'壬', '戊'=>'甲', '己'=>'丙', '庚'=>'戊', '辛'=>'庚', '壬'=>'壬', '癸'=>'甲');
$config['hourGanRule']['卯'] = array('甲'=>'丁', '乙'=>'己', '丙'=>'辛', '丁'=>'癸', '戊'=>'乙', '己'=>'丁', '庚'=>'己', '辛'=>'辛', '壬'=>'癸', '癸'=>'乙');
$config['hourGanRule']['辰'] = array('甲'=>'戊', '乙'=>'庚', '丙'=>'壬', '丁'=>'甲', '戊'=>'丙', '己'=>'戊', '庚'=>'庚', '辛'=>'壬', '壬'=>'甲', '癸'=>'丙');
$config['hourGanRule']['巳'] = array('甲'=>'己', '乙'=>'辛', '丙'=>'癸', '丁'=>'乙', '戊'=>'丁', '己'=>'己', '庚'=>'辛', '辛'=>'癸', '壬'=>'乙', '癸'=>'丁');
$config['hourGanRule']['午'] = array('甲'=>'庚', '乙'=>'壬', '丙'=>'甲', '丁'=>'丙', '戊'=>'戊', '己'=>'庚', '庚'=>'壬', '辛'=>'甲', '壬'=>'丙', '癸'=>'戊');
$config['hourGanRule']['未'] = array('甲'=>'辛', '乙'=>'癸', '丙'=>'乙', '丁'=>'丁', '戊'=>'己', '己'=>'辛', '庚'=>'癸', '辛'=>'乙', '壬'=>'丁', '癸'=>'己');
$config['hourGanRule']['申'] = array('甲'=>'壬', '乙'=>'甲', '丙'=>'丙', '丁'=>'戊', '戊'=>'庚', '己'=>'壬', '庚'=>'甲', '辛'=>'丙', '壬'=>'戊', '癸'=>'庚');
$config['hourGanRule']['酉'] = array('甲'=>'癸', '乙'=>'乙', '丙'=>'丁', '丁'=>'己', '戊'=>'辛', '己'=>'癸', '庚'=>'乙', '辛'=>'丁', '壬'=>'己', '癸'=>'辛');
$config['hourGanRule']['戌'] = array('甲'=>'甲', '乙'=>'丙', '丙'=>'戊', '丁'=>'庚', '戊'=>'壬', '己'=>'甲', '庚'=>'丙', '辛'=>'戊', '壬'=>'庚', '癸'=>'壬');
$config['hourGanRule']['亥'] = array('甲'=>'乙', '乙'=>'丁', '丙'=>'己', '丁'=>'辛', '戊'=>'癸', '己'=>'乙', '庚'=>'丁', '辛'=>'己', '壬'=>'辛', '癸'=>'癸');

# 六十甲子纳音歌
$config['sixtyArr'][] = '甲子、乙丑海中金';
$config['sixtyArr'][] = '丙寅、丁卯炉中火';
$config['sixtyArr'][] = '戊辰、己巳大林木';
$config['sixtyArr'][] = '庚午、辛未路旁土';
$config['sixtyArr'][] = '壬申、癸酉剑锋金';
$config['sixtyArr'][] = '甲戌、乙亥山头火';
$config['sixtyArr'][] = '丙子、丁亥涧下水';
$config['sixtyArr'][] = '戊寅、己卯城头土';
$config['sixtyArr'][] = '庚辰、辛巳白腊金';
$config['sixtyArr'][] = '壬午、癸未扬柳木';
$config['sixtyArr'][] = '甲申、乙酉泉中水';
$config['sixtyArr'][] = '丙戌、丁亥屋上土';
$config['sixtyArr'][] = '戊子、己丑霹雳火';
$config['sixtyArr'][] = '庚寅、辛卯松柏木';
$config['sixtyArr'][] = '壬辰、癸巳长流水';
$config['sixtyArr'][] = '甲午、乙未沙中金';
$config['sixtyArr'][] = '丙申、丁酉山下火';
$config['sixtyArr'][] = '戊戌、己亥平地木';
$config['sixtyArr'][] = '庚子、辛丑壁上土';
$config['sixtyArr'][] = '壬寅、癸卯金箔金';
$config['sixtyArr'][] = '甲辰、乙巳佛灯火';
$config['sixtyArr'][] = '丙午、丁未天河水';
$config['sixtyArr'][] = '戊申、己酉大驿土';
$config['sixtyArr'][] = '庚戌、辛亥钗钏金';
$config['sixtyArr'][] = '壬子、癸丑桑柘木';
$config['sixtyArr'][] = '甲寅、乙卯大溪水';
$config['sixtyArr'][] = '丙辰、丁巳沙中土';
$config['sixtyArr'][] = '戊午、己未天上火';
$config['sixtyArr'][] = '庚申、辛酉石榴木';
$config['sixtyArr'][] = '壬戌、癸亥大海水';

# 五行局 描述
$config['fiveBureauInfo']['甲'] = array('子'=>'锦金', '丑'=>'锦金', '午'=>'锦金', '未'=>'锦金', '寅'=>'江水', '卯'=>'江水', '申'=>'江水', '酉'=>'江水', '辰'=>'烟火', '巳'=>'烟火', '戌'=>'烟火', '亥'=>'烟火');
$config['fiveBureauInfo']['乙'] = array('子'=>'锦金', '丑'=>'锦金', '午'=>'锦金', '未'=>'锦金', '寅'=>'江水', '卯'=>'江水', '申'=>'江水', '酉'=>'江水', '辰'=>'烟火', '巳'=>'烟火', '戌'=>'烟火', '亥'=>'烟火');
$config['fiveBureauInfo']['丙'] = array('子'=>'没水', '丑'=>'没水', '午'=>'没水', '未'=>'没水', '寅'=>'谷火', '卯'=>'谷火', '申'=>'谷火', '酉'=>'谷火', '辰'=>'田土', '巳'=>'田土', '戌'=>'田土', '亥'=>'田土');
$config['fiveBureauInfo']['丁'] = array('子'=>'没水', '丑'=>'没水', '午'=>'没水', '未'=>'没水', '寅'=>'谷火', '卯'=>'谷火', '申'=>'谷火', '酉'=>'谷火', '辰'=>'田土', '巳'=>'田土', '戌'=>'田土', '亥'=>'田土');
$config['fiveBureauInfo']['戊'] = array('子'=>'营火', '丑'=>'营火', '午'=>'营火', '未'=>'营火', '寅'=>'堤土', '卯'=>'堤土', '申'=>'堤土', '酉'=>'堤土', '辰'=>'柳木', '巳'=>'柳木', '戌'=>'柳木', '亥'=>'柳木');
$config['fiveBureauInfo']['己'] = array('子'=>'营火', '丑'=>'营火', '午'=>'营火', '未'=>'营火', '寅'=>'堤土', '卯'=>'堤土', '申'=>'堤土', '酉'=>'堤土', '辰'=>'柳木', '巳'=>'柳木', '戌'=>'柳木', '亥'=>'柳木');
$config['fiveBureauInfo']['庚'] = array('子'=>'挂土', '丑'=>'挂土', '午'=>'挂土', '未'=>'挂土', '寅'=>'杖木', '卯'=>'杖木', '申'=>'杖木', '酉'=>'杖木', '辰'=>'钱金', '巳'=>'钱金', '戌'=>'钱金', '亥'=>'钱金');
$config['fiveBureauInfo']['辛'] = array('子'=>'挂土', '丑'=>'挂土', '午'=>'挂土', '未'=>'挂土', '寅'=>'杖木', '卯'=>'杖木', '申'=>'杖木', '酉'=>'杖木', '辰'=>'钱金', '巳'=>'钱金', '戌'=>'钱金', '亥'=>'钱金');
$config['fiveBureauInfo']['壬'] = array('子'=>'林木', '丑'=>'林木', '午'=>'林木', '未'=>'林木', '寅'=>'钟金', '卯'=>'钟金', '申'=>'钟金', '酉'=>'钟金', '辰'=>'满水', '巳'=>'满水', '戌'=>'满水', '亥'=>'满水');
$config['fiveBureauInfo']['癸'] = array('子'=>'林木', '丑'=>'林木', '午'=>'林木', '未'=>'林木', '寅'=>'钟金', '卯'=>'钟金', '申'=>'钟金', '酉'=>'钟金', '辰'=>'满水', '巳'=>'满水', '戌'=>'满水', '亥'=>'满水');

# 紫微天府的宫位对应
$config['ziweiTianfuPalace'] = array('子'=>'辰','丑'=>'卯','寅'=>'寅','卯'=>'丑','辰'=>'子','巳'=>'亥','午'=>'戌','未'=>'酉','申'=>'申','酉'=>'未','戌'=>'午','亥'=>'巳');

# 四化星对应
$config['foreTurn']['甲'] = array('廉贞'=>'禄','破军'=>'权','武曲'=>'科','太阳'=>'忌');
$config['foreTurn']['乙'] = array('天机'=>'禄','天梁'=>'权','紫微'=>'科','太阴'=>'忌');
$config['foreTurn']['丙'] = array('天同'=>'禄','天机'=>'权','文昌'=>'科','廉贞'=>'忌');
$config['foreTurn']['丁'] = array('太阴'=>'禄','天同'=>'权','天机'=>'科','巨门'=>'忌');
$config['foreTurn']['戊'] = array('贪狼'=>'禄','太阴'=>'权','右弼'=>'科','天机'=>'忌');
$config['foreTurn']['己'] = array('武曲'=>'禄','贪狼'=>'权','天梁'=>'科','文曲'=>'忌');
$config['foreTurn']['庚'] = array('太阳'=>'禄','武曲'=>'权','太阴'=>'科','天同'=>'忌');
$config['foreTurn']['辛'] = array('巨门'=>'禄','太阳'=>'权','文曲'=>'科','文昌'=>'忌');
$config['foreTurn']['壬'] = array('天梁'=>'禄','紫微'=>'权','左辅'=>'科','武曲'=>'忌');
$config['foreTurn']['癸'] = array('破军'=>'禄','巨门'=>'权','太阳'=>'科','贪狼'=>'忌');

# 五行长生十二星 (五行局，水二、木三、金四、土五、火六 | 分阴阳男女: 1: 阳男/阴女 2: 阴男/阳女)
$config['fiveLongevity']['2']['1'] = array('长生'=>'申', '沐浴'=>'酉', '冠带'=>'戌', '临官'=>'亥', '帝旺'=>'子', '衰'=>'丑', '病'=>'寅', '死'=>'卯', '墓'=>'辰', '绝'=>'巳', '胎'=>'午', '养'=>'未');
$config['fiveLongevity']['2']['2'] = array('长生'=>'申', '沐浴'=>'未', '冠带'=>'午', '临官'=>'巳', '帝旺'=>'辰', '衰'=>'卯', '病'=>'寅', '死'=>'丑', '墓'=>'子', '绝'=>'亥', '胎'=>'戌', '养'=>'酉');
$config['fiveLongevity']['3']['1'] = array('长生'=>'亥', '沐浴'=>'子', '冠带'=>'丑', '临官'=>'寅', '帝旺'=>'卯', '衰'=>'辰', '病'=>'巳', '死'=>'午', '墓'=>'未', '绝'=>'申', '胎'=>'酉', '养'=>'戌');
$config['fiveLongevity']['3']['2'] = array('长生'=>'亥', '沐浴'=>'戌', '冠带'=>'酉', '临官'=>'申', '帝旺'=>'未', '衰'=>'午', '病'=>'巳', '死'=>'辰', '墓'=>'卯', '绝'=>'寅', '胎'=>'丑', '养'=>'子');
$config['fiveLongevity']['4']['1'] = array('长生'=>'巳', '沐浴'=>'午', '冠带'=>'未', '临官'=>'申', '帝旺'=>'酉', '衰'=>'戌', '病'=>'亥', '死'=>'子', '墓'=>'丑', '绝'=>'寅', '胎'=>'卯', '养'=>'辰');
$config['fiveLongevity']['4']['2'] = array('长生'=>'巳', '沐浴'=>'辰', '冠带'=>'卯', '临官'=>'寅', '帝旺'=>'丑', '衰'=>'子', '病'=>'亥', '死'=>'戌', '墓'=>'酉', '绝'=>'申', '胎'=>'未', '养'=>'午');
$config['fiveLongevity']['5']['1'] = array('长生'=>'申', '沐浴'=>'酉', '冠带'=>'戌', '临官'=>'亥', '帝旺'=>'子', '衰'=>'丑', '病'=>'寅', '死'=>'卯', '墓'=>'辰', '绝'=>'巳', '胎'=>'午', '养'=>'未');
$config['fiveLongevity']['5']['2'] = array('长生'=>'申', '沐浴'=>'未', '冠带'=>'午', '临官'=>'巳', '帝旺'=>'辰', '衰'=>'卯', '病'=>'寅', '死'=>'丑', '墓'=>'子', '绝'=>'亥', '胎'=>'戌', '养'=>'酉');
$config['fiveLongevity']['6']['1'] = array('长生'=>'寅', '沐浴'=>'卯', '冠带'=>'辰', '临官'=>'巳', '帝旺'=>'午', '衰'=>'未', '病'=>'申', '死'=>'酉', '墓'=>'戌', '绝'=>'亥', '胎'=>'子', '养'=>'丑');
$config['fiveLongevity']['6']['2'] = array('长生'=>'寅', '沐浴'=>'丑', '冠带'=>'子', '临官'=>'亥', '帝旺'=>'戌', '衰'=>'酉', '病'=>'申', '死'=>'未', '墓'=>'午', '绝'=>'巳', '胎'=>'辰', '养'=>'卯');

# 小限 1:男 2:女
$config['smallDeadline']['寅']['1'] = array('辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子', '丑', '寅', '卯');
$config['smallDeadline']['寅']['2'] = array('辰', '卯', '寅', '丑', '子', '亥', '戌', '酉', '申', '未', '午', '巳');
$config['smallDeadline']['午']['1'] = array('辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子', '丑', '寅', '卯');
$config['smallDeadline']['午']['2'] = array('辰', '卯', '寅', '丑', '子', '亥', '戌', '酉', '申', '未', '午', '巳');
$config['smallDeadline']['戌']['1'] = array('辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子', '丑', '寅', '卯');
$config['smallDeadline']['戌']['2'] = array('辰', '卯', '寅', '丑', '子', '亥', '戌', '酉', '申', '未', '午', '巳');
$config['smallDeadline']['申']['1'] = array('戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉');
$config['smallDeadline']['申']['2'] = array('戌', '酉', '申', '未', '午', '巳', '辰', '卯', '寅', '丑', '子', '亥');
$config['smallDeadline']['子']['1'] = array('戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉');
$config['smallDeadline']['子']['2'] = array('戌', '酉', '申', '未', '午', '巳', '辰', '卯', '寅', '丑', '子', '亥');
$config['smallDeadline']['辰']['1'] = array('戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉');
$config['smallDeadline']['辰']['2'] = array('戌', '酉', '申', '未', '午', '巳', '辰', '卯', '寅', '丑', '子', '亥');
$config['smallDeadline']['巳']['1'] = array('未', '申', '酉', '戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午');
$config['smallDeadline']['巳']['2'] = array('未', '午', '巳', '辰', '卯', '寅', '丑', '子', '亥', '戌', '酉', '申');
$config['smallDeadline']['酉']['1'] = array('未', '申', '酉', '戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午');
$config['smallDeadline']['酉']['2'] = array('未', '午', '巳', '辰', '卯', '寅', '丑', '子', '亥', '戌', '酉', '申');
$config['smallDeadline']['丑']['1'] = array('未', '申', '酉', '戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午');
$config['smallDeadline']['丑']['2'] = array('未', '午', '巳', '辰', '卯', '寅', '丑', '子', '亥', '戌', '酉', '申');
$config['smallDeadline']['亥']['1'] = array('丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子');
$config['smallDeadline']['亥']['2'] = array('丑', '子', '亥', '戌', '酉', '申', '未', '午', '巳', '辰', '卯', '寅');
$config['smallDeadline']['卯']['1'] = array('丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子');
$config['smallDeadline']['卯']['2'] = array('丑', '子', '亥', '戌', '酉', '申', '未', '午', '巳', '辰', '卯', '寅');
$config['smallDeadline']['未']['1'] = array('丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥', '子');
$config['smallDeadline']['未']['2'] = array('丑', '子', '亥', '戌', '酉', '申', '未', '午', '巳', '辰', '卯', '寅');


#  飞星
$config['flyStar']['甲'] = array('禄'=>'廉贞', '权'=>'破军', '科'=>'武曲', '忌'=>'太阳');
$config['flyStar']['乙'] = array('禄'=>'天机', '权'=>'天梁', '科'=>'紫微', '忌'=>'太阴');
$config['flyStar']['丙'] = array('禄'=>'天同', '权'=>'天机', '科'=>'文昌', '忌'=>'廉贞');
$config['flyStar']['丁'] = array('禄'=>'太阴', '权'=>'天同', '科'=>'天机', '忌'=>'巨门');
$config['flyStar']['戊'] = array('禄'=>'贪狼', '权'=>'太阴', '科'=>'右弼', '忌'=>'天机');
$config['flyStar']['己'] = array('禄'=>'武曲', '权'=>'贪狼', '科'=>'天梁', '忌'=>'文曲');
$config['flyStar']['庚'] = array('禄'=>'太阳', '权'=>'武曲', '科'=>'太阴', '忌'=>'天同');
$config['flyStar']['辛'] = array('禄'=>'巨门', '权'=>'太阳', '科'=>'文曲', '忌'=>'文昌');
$config['flyStar']['壬'] = array('禄'=>'天梁', '权'=>'紫微', '科'=>'左辅', '忌'=>'武曲');

#  三方四正
$config['sanFangSiZheng'] = [
	{'zi': [['chen', 'shen'], ['wu']]},
	{'chou': [['si', 'you'], ['wei']]},
	{'yin': [['wu', 'xu'], ['shen']]},
	{'mao': [['wei', 'hai'], ['you']]},
	{'chen': [['zi', 'shen'], ['xu']]},
	{'si': [['chou', 'you'], ['hai']]},
	{'wu': [['yin', 'xu'], ['zi']]},
	{'wei': [['mao', 'hai'], ['chou']]},
	{'shen': [['zi', 'chen'], ['yin']]},
	{'you': [['chou', 'si'], ['mao']]},
	{'xu': [['yin', 'wu'], ['chen']]},
	{'hai': [['mao', 'wei'], ['si']]},
]