<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class ip66 extends Crontab
{
    private $url = 'http://www.66ip.cn/';

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $res = Tool::http($this->url);
        $res = \org\Tool::exp($res, 'bordercolor="#6699ff">', '</table>');
        $res = Tool::exps($res, '<tr', '</tr>', [0, 1]);
        $num = 0;

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
        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
