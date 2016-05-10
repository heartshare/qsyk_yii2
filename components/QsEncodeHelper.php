<?php

/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/6
 * Time: 12:31
 */

namespace app\components;


class QsEncodeHelper extends \yii\base\Component
{


    /**
     * bbboo_jobbr_code
     * 包卜通用码
     * $Author: jobbr $
     * $Date: 2009-03-17 $
     */
    public static function getSid($code)
    {
        $offset_array = self::offset_array(2);
        if (strlen($code) != 11) {
            return 0;
        }
        $edition = substr($code, 0, 1);
        if (!isset($offset_array[$edition])) {
            return 0;
        }
        $offset = $offset_array[$edition];
        //处理偏移
        $start_code = substr($code, $offset + 1);
        $end_code = substr($code, 1, $offset);
        $real_code = $start_code . $end_code;

        //将字符转换成64进制数组,第一位是个位
        $numArray = self::code_to_number($real_code);

        //处理混淆
        $numArray = self::obscure($numArray, -$offset, -1);

        //转换成2进制
        $ret = 0;
        foreach ($numArray as $key => $num) {
            $ret += $num * pow(64, $key);
        }
        return $ret;
    }

    public static function setSid($id)
    {
        //取得偏移值和便宜code
        $offset_array = self::offset_array(1);
        $offset = $id % 11;
        $offset_code = $offset_array[$offset];

        //转化何曾64进制数组，第一位是个位
        $numArray = self::number_to_64($id);

        //处理混淆
        $numArray = self::obscure($numArray, $offset, 1);

        //处理转化成字符
        $string = join('', array_reverse(self::number_to_code($numArray)));

        //处理偏移
        $start_code = substr($string, -$offset);
        $end_code = substr($string, 0, -$offset);
        $code = $start_code . $end_code;
        $real_code = $offset_code;
        return $real_code . $code;
    }


    //将数字转换成字符
    private static function number_to_code($numberArr)
    {
        $char_array = self::original_array(1);  //取得数字代表的字符

        $ret = array();
        foreach ($numberArr as $number) {
            $ret[] = $char_array[$number];
        }
        return $ret;
    }


    //字符转换成数字
    private static function code_to_number($code)
    {
        //取得字符代表的数字
        $num_array = self::original_array(2);
        $length = strlen($code);
        $numbers = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $numbers[] = $num_array[$code{$i}];
        }
        return $numbers;
    }

    //混淆数字
    private static function obscure($numArray, $obscureNum, $z)
    {
        foreach ($numArray as $k => &$num) {
            $num = $num + $k * 3 * $obscureNum + $z * $k * 4;
            $num = $num % 64;
            if ($num < 0) {
                $num += 64;
            }
        }
        return $numArray;
    }

    //将十进制数字转换为六十四进制
    //如果传入为数组，则遍历所有元素返回数组
    private static function number_to_64($number)
    {
        $ret = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        //循环处理数字id，得到64进制字符串
        $i = 0;
        do {
            $remainder = $number % 64;
            $number -= $remainder;
            $number = $number / 64;
            $ret[$i] = $remainder;
            $i++;
        } while ($number >= 1);
        unset($i);
        return $ret;
    }


    //六十四进制 to 十进制
    private static function code_to_64($code)
    {
        //取得字符代表的数字
        $num_array = self::original_array(2);
        $length = strlen($code);
        $ret = array();
        //倒序，计算方便 $j 为当前字符倒序位数
        $ret = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $ret[] = $num_array[$code{$i}];
        }
        return $ret;
    }

    /********************
     * $type为1返回10进制=>64进制
     * 2返回64进制=>10进制
     ********************/
    private static function original_array($type)
    {
        $original_array = array(0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => 'a', 11 => 'b', 12 => 'c', 13 => 'd', 14 => 'e', 15 => 'f', 16 => 'g'
        , 17 => 'h', 18 => 'i', 19 => 'j', 20 => 'k', 21 => 'l', 22 => 'm', 23 => 'n', 24 => 'o', 25 => 'p', 26 => 'q', 27 => 'r', 28 => 's', 29 => 't', 30 => 'u', 31 => 'v', 32 => 'w', 33 => 'x', 34 => 'y', 35 => 'z',
            36 => 'A', 37 => 'B', 38 => 'C', 39 => 'D', 40 => 'E', 41 => 'F', 42 => 'G', 43 => 'H', 44 => 'I', 45 => 'J', 46 => 'K', 47 => 'L', 48 => 'M', 49 => 'N', 50 => 'O', 51 => 'P', 52 => 'Q', 53 => 'R', 54 => 'S',
            55 => 'T', 56 => 'U', 57 => 'V', 58 => 'W', 59 => 'X', 60 => 'Y', 61 => 'Z', 62 => '-', 63 => '_');
        if ($type == 1) {
            return $original_array;
        } else {
            return array_flip($original_array);
        }
    }

    private static function get_code_num($code, $offset)
    {
        $codeToNum = self::original_array(2);
        $num = $codeToNum[$code];
        if ($num >= 0) {
        }
    }

    /****************
     * 返回偏移量数组
     * type为1返回数字to字符
     * 2返回字符to数字
     ****************/
    private static function offset_array($type)
    {
        $original_array = array(0 => 'a', 1 => 'f', 2 => 'k', 3 => 'p', 4 => 'u', 5 => 'z', 6 => 'E', 7 => 'J', 8 => 'O', 9 => 'T', 10 => 'Y');
        if ($type == 1) {
            return $original_array;
        } else {
            return array_flip($original_array);
        }
    }

}