<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class taiyanghttp extends Crontab
{
    private $url = [
        'http://www.taiyanghttp.com/free/page%s%/',
    ];
    private $minPage = 1;
    private $maxPage = 2;

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        foreach ($this->url as $url) {
            for ($i=$this->minPage; $i <= $this->maxPage; $i++) { 
                $api = Tool::str_url($url, [$i]);
                $res = Tool::http($api, [
                    'Accept' => 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
                ]);
                $res = Tool::exp($res, '<div class="list" id="ip_list">', '<div class="free_page"');
                $res = explode('<div class="tr ip_tr">', $res);
                unset($res[0]);
                if ($res) {
                    foreach ($res as $v) {
                        $str = Tool::rep($v);
                        $str = Tool::rep($str, ['class="tdtd-2"', 'class="tdtd-4"']);
                        $arr = Tool::exps($str, '<div>', '</div>');
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
