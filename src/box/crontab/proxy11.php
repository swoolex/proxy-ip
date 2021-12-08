<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class proxy11 extends Crontab
{
    private $url = [
        'https://proxy11.com/api/demoweb/proxy.json?country=GB&speed=2000',
        'https://proxy11.com/api/demoweb/proxy.json?country=US&speed=2000',
        'https://proxy11.com/api/demoweb/proxy.json?country=DE&speed=2000',
        'https://proxy11.com/api/demoweb/proxy.json?country=JP&speed=2000',
        'https://proxy11.com/api/demoweb/proxy.json?country=RU&speed=2000',
    ];

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        foreach ($this->url as $url) {
            $res = Tool::http($url, [
                'User-Agent' => 'Chrome/49.0.2587.3',
                'Accept' => 'text/html,application/xhtml+xml,application/xml',
            ]);
            $res = json_decode($res, true);
            if (!empty($res['data'])) {
                foreach ($res['data'] as $arr) {
                    $res = $Db->name('abroad_ip_verify')->where('ip', $arr['ip'])->where('port', $arr['port'])->find();
                    if ($res) continue;
                    $res = $Db->name('abroad_ip')->where('ip', $arr['ip'])->where('port', $arr['port'])->find();
                    if ($res) continue;
                    $res = $Db->name('abroad_ip_verify')->insert([
                        'ip' => $arr['ip'],
                        'port' => $arr['port'],
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
