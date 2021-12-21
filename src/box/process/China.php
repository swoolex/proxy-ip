<?php
/**
 * +----------------------------------------------------------------------
 * 国内IP检测
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/
namespace box\process;
use design\AbstractProcess;
use Swoole\Process;


class China extends AbstractProcess
{
    /**
     * 进程名称
     */
    public $name = 'china-process';
    
    /**
     * 是否需要while(true) 永久堵塞
    */
    public $onWhile = true;

    /**
     * 等待间隔时间(毫秒)  0不堵塞
    */
    public $sleepS = 2000;

    // 进程逻辑接口方法
    public function run() {
        $num = 300;
        $http = 'http://www.qq.com/';
        $https = 'https://blog.junphp.com/';
        $Db = new \x\Db();
        $list = $Db->name('china_ip_verify')->order('update_time ASC')->limit($num)->select();
        $channel = new \Swoole\Coroutine\Channel;

        foreach ($list as $k=>$v) {
            //创建子进程
            go(function () use ($channel, $k, $v, $http, $https, $Db){
                $httpClient = (new \x\Client())->http();
                $ip = [
                    'timeout' => 3, 
                    'http_proxy_host' => $v['ip'],
                    'http_proxy_port' => $v['port'],
                ];
                $t1 = microtime(true);
                $res = $httpClient->domain($https)->set($ip)->get();
                $t2 = microtime(true);
                if ($httpClient->statusCode() == 200) {
                    $Db->name('china_ip_verify')->where('id', $v['id'])->delete();
                    $Db->name('china_ip')->insert([
                        'ip' => $v['ip'],
                        'port' => $v['port'],
                        'response_time' => round($t2-$t1, 3),
                        'is_ssl' => 1,
                        'create_time' => time(),
                        'update_time' => time(),
                        'status' => 1,
                        'success_num' => 1,
                    ]);
                } else {
                    $Db->name('china_ip_verify')->where('id', $v['id'])->delete();
                    $t1 = microtime(true);
                    $httpClient->domain($http)->set($ip)->get();
                    $t2 = microtime(true);
                    if ($httpClient->statusCode() == 302) {
                        $Db->name('china_ip')->insert([
                            'ip' => $v['ip'],
                            'port' => $v['port'],
                            'response_time' => round($t2-$t1, 3),
                            'is_ssl' => 0,
                            'create_time' => time(),
                            'update_time' => time(),
                            'status' => 1,
                            'success_num' => 1,
                        ]);
                    }
                }
                $channel->push(1);
            });
        }

        $num = count($list);
        for($i = 0; $i < $num; ++$i) {
            $channel->pop();
        }
        $channel->close();

        $Db->return();
    }
}