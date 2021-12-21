<?php
/**
 * +----------------------------------------------------------------------
 * 工具
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

namespace org;

class Tool
{
    public static function http($url, $head=[]) {
        $httpClient = new \x\Client();
        return $httpClient->http()
                ->domain($url)
                ->setHeaders($head)
                ->get();
    }

    public static function expLeft($str, $rule) {
        $arr = explode($rule, $str);
        return $arr[0];
    }

    public static function exp($str, $left, $right) {
        $arr = explode($left, $str);
        if (!isset($arr[1])) return false;
        $arr = explode($right, $arr[1]);
        return $arr[0];
    }

    public static function exps($str, $left, $right, $key=null) {
        $str = self::rep($str, $right);
        $arr = explode($left, $str);
        if (!isset($arr[1])) return false;
        if ($key !== null) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    unset($arr[$k]);
                }
            } else {
                unset($arr[$key]);
            }
        }
        return $arr;
    }
    
    public static function rep($str, $rule=[" ","　","\t","\n","\r"], $exp='') {
        return str_replace($rule, $exp, $str);
    }

    public static function str_url($url, $array){
        foreach ($array as $v){
            $url = preg_replace('/%s%/', $v, $url, 1);
        }
        return $url;
    }
}