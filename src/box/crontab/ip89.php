<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class ip89 extends Crontab
{
    private $url = 'http://api.89ip.cn/tqdl.html?api=1&num=9999&port=&address=&isp=';

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $res = Tool::http($this->url, [
			'User-Agent' => 'Chrome/49.0.2587.3',
			'Accept' => 'text/html,application/xhtml+xml,application/xml',
		]);
        $res = explode('<br>', $res);
        unset($res[0]);
        unset($res[1]);

        $num = 0;
        if ($res) {
            foreach ($res as $v) {
                if (empty($v)) break;
                $arr = explode(':', $v);
                if ($arr) {
                    $res = $Db->name('china_ip_verify')->where('ip', $arr[0])->where('port', $arr[1])->find();
                    if ($res) continue;
                    $res = $Db->name('china_ip')->where('ip', $arr[0])->where('port', $arr[1])->find();
                    if ($res) continue;
                    $res = $Db->name('china_ip_verify')->insert([
                        'ip' => $arr[0],
                        'port' => $arr[1],
                        'update_time' => time()
                    ]);
                    if ($res) $num++;
                }
            }
        }
        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
