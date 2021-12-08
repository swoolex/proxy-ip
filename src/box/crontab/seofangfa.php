<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class seofangfa extends Crontab
{
    private $url = 'https://proxy.seofangfa.com/';

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        $head = [
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
        ];
        $res = Tool::http($this->url, $head);
        $res = \org\Tool::exp($res, '<table class="table">', '</table>');
        $res = \org\Tool::exp($res, '<tbody>', '<tbody>');
        $res = Tool::exps($res, '<tr>', '</tr>', 0);
        
        if ($res) {
            foreach ($res as $v) {
                $str = Tool::rep($v);
                $arr = Tool::exps($str, '<td>', '</td>');
                if ($arr) {
                    if (stripos($arr[4], '中国') !== false) {
                        $res = $Db->name('china_ip_verify')->where('ip', $arr[1])->where('port', $arr[2])->find();
                        if ($res) continue;
                        $res = $Db->name('china_ip')->where('ip', $arr[1])->where('port', $arr[2])->find();
                        if ($res) continue;
                        $res = $Db->name('china_ip_verify')->insert([
                            'ip' => $arr[1],
                            'port' => $arr[2],
                            'update_time' => time()
                        ]);
                    } else {
                        $res = $Db->name('abroad_ip_verify')->where('ip', $arr[1])->where('port', $arr[2])->find();
                        if ($res) continue;
                        $res = $Db->name('abroad_ip')->where('ip', $arr[1])->where('port', $arr[2])->find();
                        if ($res) continue;
                        $res = $Db->name('abroad_ip_verify')->insert([
                            'ip' => $arr[1],
                            'port' => $arr[2],
                            'update_time' => time()
                        ]);
                    }
                    if ($res) $num++;
                }
            }
        }

        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
