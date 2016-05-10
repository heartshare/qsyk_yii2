<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/9
 * Time: 15:58
 */

namespace app\components;


use yii\base\Component;

class QsBaseTime extends Component
{

    /* 从某天时间返回当天起始时间和结束时间 */
    /* 支持两种格式，Y-m-d和unix时间戳 */
    function time_to_day_0_24($time){
        $dates = explode('-', $time);
        if (count($dates) != 3){
            $date = date('Y-m-d', $time);
            $dates = explode('-', $date);
        }
        $time_start = mktime(0, 0, 0, $dates[1], $dates[2], $dates[0]);
        $time_end = $time_start + 86400;

        return array($time_start, $time_end);
    }
    /* 取得距离现在的时间 */
    public static function time_get_past($time){
        $time_past = time() - $time;
        if ($time_past > 31536000){
            $time_past = floor($time_past/31536000) . '年前';
        }
        elseif($time_past > 2592000){
            $time_past = floor($time_past/2592000) . '个月前';
        }
        elseif($time_past > 86400){
            $time_past = floor($time_past/86400) . '天前';
        }
        elseif($time_past > 3600){
            $time_past = floor($time_past/3600) . '小时前';
        }
        elseif($time_past > 60){
            $time_past = floor($time_past/60) . '分钟前';
        }
        else{
            $time_past = $time_past . '秒前';
        }
        return $time_past;
    }
    //将月份字符串返回数字
    function time_get_month_from_english($value){
        $value = trim(strtolower($value));
        switch ($value){
            case 'january':
                return 1;
            case 'jan':
                return 1;
            case 'february':
                return 2;
            case 'feb':
                return 2;
            case 'march':
                return 3;
            case 'mar':
                return 3;
            case 'april':
                return 4;
            case 'apr':
                return 4;
            case 'may':
                return 5;
            case 'june':
                return 6;
            case 'jun':
                return 6;
            case 'july':
                return 7;
            case 'jul':
                return 7;
            case 'august':
                return 8;
            case 'aug':
                return 8;
            case 'september':
                return 9;
            case 'sep':
                return 9;
            case 'october':
                return 10;
            case 'oct':
                return 10;
            case 'november':
                return 11;
            case 'nov':
                return 11;
            case 'dec':
                return 12;
            case 'december':
                return 12;
        }
    }
    //年月日标准化函数，显示周几
    function getWeekdayZhou($time = false){
        if ($time === false)
            $time = time();
        switch (date("D", $time)){
            case 'Mon':
                return '周一';
                break;
            case 'Tue':
                return '周二';
                break;
            case 'Wed':
                return '周三';
                break;
            case 'Thu':
                return '周四';
                break;
            case 'Fri':
                return '周五';
                break;
            case 'Sat':
                return '周六';
                break;
            case 'Sun':
                return '周日';
                break;
            default:
                echo '';
        }
    }
    //年月日标准化函数，显示星期几
    function getWeekday($time = false){
        if ($time === false)
            $time = time();
        switch (date("D", $time)){
            case 'Mon':
                return '星期一';
                break;
            case 'Tue':
                return '星期二';
                break;
            case 'Wed':
                return '星期三';
                break;
            case 'Thu':
                return '星期四';
                break;
            case 'Fri':
                return '星期五';
                break;
            case 'Sat':
                return '星期六';
                break;
            case 'Sun':
                return '星期日';
                break;
            default:
                echo '';
        }
    }
    //将标准时间转化为时间戳
    function time_to_unix($time){
        if (strlen($time) != 19 || !preg_match('/[\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2}/', $time)){
            die(__FUNCTION__ . '：错误的参数' . $time);
        }
        $year = substr($time, 0, 4);
        $month = substr($time, 5, 2);
        $date = substr($time, 8, 2);
        $hour = substr($time, 11, 2);
        $minute = substr($time, 14, 2);
        $second = substr($time, 17, 2);
        return mktime($hour, $minute, $second, $month, $date, $year);
    }
    //年月日标准化函数，显示星期几
    function time_get_date($type, $time = false){
        if ($time === false)
            $time = time();
        $times = explode('-', $time);
        if (count($times) == 3){
            $time = mktime('1', '1', '1', $times[1], $times[2], $times[0]);
        }
        switch ($type){
            case '1':
                return date("Y年n月j日", $time);
            case '2':
                return time_get_weekday($time);
            case '3':
                return date("n月j日", $time);
            case '4':
                return $time;
        }
    }
}