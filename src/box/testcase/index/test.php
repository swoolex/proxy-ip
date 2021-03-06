<?php
/**
 * +----------------------------------------------------------------------
 * Test自定义注解-所有注解类都应该继承Basics基类，并实现run接口
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

namespace box\testcase\index;
// 必须继承至单元测试抽象类
use \x\route\doc\lable\TestBasics;

class test extends TestBasics {
    /**
     * A1-数据库DB
    */
    public $A1 = [
        'name' => '1',
    ];

    // 返回请求数据结构
    public function getData() : array 
    {
        return [];
    } 

    // 返回请求头
    public function getHeaders() : array 
    {
        return [];
    }
}