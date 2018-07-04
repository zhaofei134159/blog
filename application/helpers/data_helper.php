<?php
class Data{
    /**
     * 获取附件地址
     */
    public static function getAttachUrlByAttachId($attachid = false)
    {
        $CI = &get_instance();
        $CI->load->model('attach_model');
        if (empty($attachid)) {
            return null;
        }
        $attachInfo = $CI->attach_model->getOne("`attach_id` = '" . $attachid . "'");
        if (empty($attachInfo)) {
            return null;
        }
        $imageUrl = $attachInfo['save_path'] . $attachInfo['save_name'];
        $imageUrl = 'http://image.doxue.com/data/upload/' . ltrim($imageUrl, '/');
        return $imageUrl;
    }
    /*
    * 获取优惠码使用状态的数量
    * $usestate -1未发放 4可用 1已发放 2已使用未支付 3已使用已支付
    */
    public static function getUseStateNum($agentid = false, $allotid = false, $usestate = false){
        $CI = & get_instance();
        $CI->load->model('code_info_model');
        $where = array();
        $where[] = "`id` > 0";
        if(!empty($agentid)) $where[] = "`agentid` = '".$agentid."'";
        if(!empty($allotid)) $where[] = "`allotid` = '".$allotid."'";
        if($usestate == -1){ //未发放
            $where[] = "`is_use` = 0";
        }else if($usestate == 4){ //可用
            $where[] = "`is_use` <> 3";
        }else if($usestate == 1){ //已发放
            $where[] = "`is_use` in (1,2,3)";
        }else if($usestate == 2){  //2已使用未支付
            $where[] = "`is_use` = 2";
        }else if($usestate == 3){  //3已使用已支付
            $where[] = "`is_use` = 3";
        }
        $condition = implode(' AND ', $where);
        $count = $CI->code_info_model->getCount($condition);
        return $count;
    }

    // 奖金阶梯规则
    public static $money_percents = array();

    /***
    * @desc 阶梯递归计算奖金值
    * @param $money 销售额
    * @param $step 阶梯号
    ***/
    public static function calc_bonus($money, $step)
    { 
        if (!isset(self::$money_percents[$step]))
        {
            if ($step > 0)
            {
                return round(($money - 
                        self::$money_percents[$step - 1]['start']) * 
                            self::$money_percents[$step - 1]['percent'] / 100, 2);
            }
            else
            {
                return 0;
            }
        }

        $bonus = 0;
        if ($money > self::$money_percents[$step]['end'])
        {
            $bonus = round((self::$money_percents[$step]['end'] - 
                        self::$money_percents[$step]['start']) * 
                            self::$money_percents[$step]['percent'] / 100, 2);
            $bonus += $this->_calc_bonus($money, $step + 1);
        }
        else
        {
            $bonus = 
                round(($money - self::$money_percents[$step]['start']) * 
                    self::$money_percents[$step]['percent'] / 100, 2);
        }

        return $bonus;
    }
    
}