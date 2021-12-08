<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class proxylistplus extends Crontab
{
    private $url = [
        'https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-'
    ];
    private $minPage = 1;
    private $maxPage = 6;

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
                
                $res = Tool::exp($res, 'class="bg">', '</table>');
                $res= Tool::exps($res, '<tr>', '</tr>', [0, 1]);
                if ($res) {
                    $res= Tool::exps($res[2], '">', '<span', [0, 1, 2]);
                    foreach ($res as $v) {
                        $str = Tool::rep($v);
                        $arr = Tool::exps($str, '<td>', '</td>');
                        if (!empty($arr[3])) {
                            $res = $Db->name('abroad_ip_verify')->where('ip', $arr[2])->where('port', $arr[3])->find();
                            if ($res) continue;
                            $res = $Db->name('abroad_ip')->where('ip', $arr[2])->where('port', $arr[3])->find();
                            if ($res) continue;
                            $res = $Db->name('abroad_ip_verify')->insert([
                                'ip' => $arr[2],
                                'port' => $arr[3],
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
