<?php
namespace v3u3i87\tool;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2017/3/13
 * Time: 下午3:17
 * Name: 数字转中文大写
 */
class NumericUppercase
{
    static function uppercaseMoney($money)
    {
        //数字从右到左的顺序取出并转换为汉字，存入$all
        $money = number_format($money, 2, '.', '');
        $cnums = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
        preg_match_all('/(\d)/', $money, $match);
        $all_num = array_reverse($match[0]);
        $all = array();
        foreach ($all_num as $v) {
            array_push($all, $cnums[$v]);
        }
        //把数字分组，小数部分一组，整数部分每4位一组，存入$num_arr
        $num_arr = array(
            0 => array(array_shift($all), array_shift($all))
        );
        $tmp_arr = array();
        foreach ($all as $k => $v) {
            if (($k + 1) % 4 == 0) {
                array_push($tmp_arr, $v);
                array_push($num_arr, $tmp_arr);
                $tmp_arr = array();
            } else {
                array_push($tmp_arr, $v);
            }
        }
        //补齐0
        $tmp_arr_num = count($tmp_arr);
        for ($i = 1; $i <= (4 - $tmp_arr_num); $i++) {
            array_push($tmp_arr, '零');
        }
        array_push($num_arr, $tmp_arr);
        //单位
        $d[0] = array('角', '分');
        $d[1] = array('仟', '佰', '拾', '');
        $d[2] = array('', '圆', '万', '亿');
        //处理小数部分
        $jiaofen_str = '';
        $jiaofen = array_reverse($num_arr[0]);
        foreach ($jiaofen as $k => $v) {
            if ($v !== '零') {
                $jiaofen_str .= $v . $d[0][$k];
            }
        }
        //处理整数部分
        $result = array();
        foreach ($num_arr as $k => $v) {
            if ($k == 0) continue;//跳过分角部分
            $tmp = array_reverse($v);
            $tmp_str = '';
            foreach ($tmp as $kk => $vv) {
                if ($vv !== '零') {
                    $tmp_str .= $vv . $d[1][$kk];
                }
            }
            array_push($result, $tmp_str . $d[2][$k]);
        }

        //合并
        $result = array_reverse($result);
        $str = implode('', $result) . $jiaofen_str;
        return $str . '整';
    }
}
