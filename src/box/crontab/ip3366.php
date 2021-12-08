<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class ip3366 extends Crontab
{
    private $url = [
        'http://www.ip3366.net/free/?stype=1&page=',
        'http://www.ip3366.net/free/?stype=2&page=',
    ];
    private $minPage = 1;
    private $maxPage = 2;

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        foreach ($this->url as $url) {
            for ($i=$this->minPage; $i <= $this->maxPage; $i++) { 
                $res = Tool::http($url.$i, [
                    'User-Agent' => 'Chrome/49.0.2587.3',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml',
                ]);
                $res = Tool::exp($res, '<tbody>', '</tbody>');
                $res= Tool::exps($res, '<tr>', '</tr>', 0);
                if ($res) {
                    foreach ($res as $v) {
                        $str = Tool::rep($v);
                        $arr = Tool::exps($str, '<td>', '</td>');
                        if ($arr) {
                            $res = $Db->name('china_ip_verify')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('china_ip')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('china_ip_verify')->insert([
                                'ip' => $arr[1],
                                'port' => $arr[2],
                                'update_time' => time()
                            ]);
                            if ($res) $num++;
                        }
                    }
                }
            }
        }
        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
