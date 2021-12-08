<?php
/**
 * +----------------------------------------------------------------------
 * 页面
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

namespace app\http;
use x\controller\Http;

class Index extends Http
{
    /**
     * @RequestMapping(route="/", method="get", title="国内")
    */
    public function china() {
        $Db = new \x\Db();
        $this->assign('no', $Db->name('china_ip_verify')->count());
        $this->assign('yes', $Db->name('china_ip')->where('status', 1)->count());
        $update_time = '';
        $res = $Db->name('china_ip')->where('status', 1)->order('update_time DESC')->value('update_time');
        if ($res) {
            $update_time = date('Y-m-d H:i:s', $res);
        }
        $this->assign('update_time', $update_time);
        $this->assign('list', $Db->name('china_ip')->where('status', 1)->order('response_time ASC, update_time DESC')->limit(10)->select());
        return $this->display('china');
    }
    /**
     * @RequestMapping(route="/abroad", method="get", title="国外")
    */
    public function abroad() {
        $Db = new \x\Db();
        $this->assign('no', $Db->name('abroad_ip_verify')->count());
        $this->assign('yes', $Db->name('abroad_ip')->where('status', 1)->count());
        $update_time = '';
        $res = $Db->name('abroad_ip')->where('status', 1)->order('update_time DESC')->value('update_time');
        if ($res) {
            $update_time = date('Y-m-d H:i:s', $res);
        }
        $this->assign('update_time', $update_time);
        $this->assign('list', $Db->name('abroad_ip')->where('status', 1)->order('response_time ASC, update_time DESC')->limit(10)->select());
        return $this->display('abroad');
    }
    /**
     * @RequestMapping(route="/get", method="get", title="获取IP")
    */
    public function get() {
        $Db = new \x\Db();
        $param = \x\Request::get();
        $type = !empty($param['type']) ? $param['type'] : 'china';
        if ($type == 'china') {
            $info = $Db->name('china_ip')->where('status', 1)->where('response_time <= 1')->order('rand()')->field('ip, port')->find();
        } else {
            $info = $Db->name('abroad_ip')->where('status', 1)->where('response_time <= 1')->order('rand()')->field('ip, port')->find();
        }
        if (!$info) {
            return \x\Restful::code(\x\Restful::ERROR())->callback();
        }
        return \x\Restful::code(\x\Restful::SUCCESS())->data($info)->callback();
    }
}