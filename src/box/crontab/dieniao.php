<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class dieniao extends Crontab
{
    private $url = [
        'https://www.dieniao.com/FreeProxy/%s%.html'
    ];
    private $minPage = 1;
    private $maxPage = 8;

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
                $res = Tool::exp($res, "<div class='free-main col-lg-12 col-md-12 col-sm-12 col-xs-12'>", '</div>');
                $res = Tool::exp($res, "<ul>", '</ul>');
                $res = Tool::rep($res);
                $res = Tool::rep($res, [
                    "class='f-listcol-lg-12col-md-12col-sm-12col-xs-12'",
                    "class='f-address'",
                    "class='f-port'",
                    "class='f-locat'",
                    "class='f-speed'",
                    "class='f-latime'",
                    "class='f-latime'",
                ]);
                $res = Tool::exps($res, '<li>', '</li>', 0);
                if ($res) {
                    foreach ($res as $v) {
                        $arr = Tool::exps($v, '<span>', '</span>');
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
