<?php
/**
 * +----------------------------------------------------------------------
 * 单元测试基类
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

namespace x\route\doc\lable;

abstract class TestBasics
{
    /**
     * 必须要实现的抽象
    */
    abstract public function getData() : array; // 返回请求数据结构
    abstract public function getHeaders() : array; // 返回请求头

}